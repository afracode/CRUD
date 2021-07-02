<?php


namespace Afranext\Crud\App\Classes;


use Afranext\Crud\App\Classes\Traits\Datatable;
use Illuminate\Support\Arr;

class Crud
{
    use Datatable;

    public $model;
    public $entities;
    public $columns = [];
    public $filters = [];
    public $fields = [];
    public $object;
    public $tmpPath;
    public $row;
    public $inputs = [];
    public $query;
    private $reserved_field_key;
    private $actions;
    private $loaded_fields = [];

    public function __construct()
    {
        $this->tmpPath = storage_path('tmp');
        $this->actions = ['read', 'create', 'edit', 'delete'];
    }


    public function route($route, $id = null)
    {
        $entities = $this->entities;


        $prefix = '/' . config('crud.base.prefix') . '/';

        if (in_array($route, ['update', 'show', 'delete']))
            return $prefix . $entities . '/' . $id;
        elseif ($route == 'edit')
            return $prefix . $entities . '/' . $id . '/edit';
        elseif ($route == 'create')
            return $prefix . $entities . '/create';
        elseif (in_array($route, ['index', 'store']))
            return $prefix . $entities;
        elseif ($route == 'datatable')
            return $prefix  . 'datatable/' . $entities;
        elseif ($route == 'deleteMedia')
            return $prefix . $entities . '/' . $id . '/media';
        elseif ($route == 'storeMedia')
            return route('crud.storeMedia');
    }


    public function permission($action)
    {
        return $this->entities . '-' . $action;
    }


    public function isMultiple($relation_type)
    {
        if (in_array($relation_type, [
            'BelongsToMany',
            'HasMany',
            'HasManyThrough',
            'HasOneOrMany',
            'MorphMany',
            'MorphOneOrMany',
            'MorphToMany',
        ]))
            return true;
        else
            return false;

    }


    public function setEntity(string $entities)
    {
        $this->entities = $entities;
    }


    public function setModel(string $model)
    {
        $this->model = $model;
        $this->object = new $model();
        $this->query = $this->model::select('*')->orderBy('id', 'Desc');
    }


    public function setRow(int $id)
    {
        $this->row = $this->model::find($id);
        return $this;
    }


    public function setColumns($columns)
    {
        $this->columns = [];

        foreach ($columns as $column) {
            array_push($this->columns,
                [
                    'data' => $column,
                    'name' => $column,
                    'orderable' => 1,
                    'searchable' => 1,
                ]
            );
        }

        return $this;
    }


    public function addFilter($name, $func)
    {

        array_push($this->filters,
            [
                'name' => $name,
                'func' => $func,
            ]
        );

        return $this;
    }


    public function setColumn($data, $title = null, $orderable = null, $searchable = null)
    {
        array_push($this->columns,
            [
                'data' => $data,
                'name' => $title ?? trans('crud.db.' . $data),
                'orderable' => $orderable ?? 1,
                'searchable' => $searchable ?? 1,
                'change_to' => null,
            ]
        );

        return $this;
    }


    public function editColumn($changeTo)
    {
        $index = count($this->columns) - 1;
        $this->columns[$index]['change_to'] = $changeTo;
    }


    public function initFieldType($field, $defaultType = "text")
    {
        if (!isset($field['type']))
            $field['type'] = "text";

        return $field;
    }


    public function setField($field)
    {
        $field = $this->initFieldType($field);

        Arr::where($this->fields, function ($value, $key) use ($field) {
            if (isset($value['name']) && isset($field["name"])) {
                if (($value['name'] == $field["name"]))
                    $this->reserved_field_key = $key;
            } elseif (isset($value['method']) && isset($field["method"])) {
                if (($value['method'] == $field["method"]))
                    $this->reserved_field_key = $key;
            }

        });

        $field = $this->initFieldLabel($field);


        if ($this->reserved_field_key)
            $this->fields[$this->reserved_field_key] = $field;
        else
            array_push($this->fields, $field);

        return $this;
    }


    public function setSecureField($field)
    {
        $secureField = [
            $field['key'] => $field['value']
        ];

        $this->inputs = array_merge($this->inputs, $secureField);

        return $this;
    }


    public function initFieldLabel($field)
    {

        $field['name'] = isset($field['name']) ? $field['name'] : $field['method'];

        $field['label'] = $field['label'] ?? trans('crud.db.' . $field['name']);

        return $field;
    }

    public function removeField($fieldName)
    {
        Arr::where($this->fields, function ($value, $key) use ($fieldName) {
            if ($value['name'] == $fieldName)
                unset($this->fields[$key]);
        });


        return $this;
    }


    public function setDefaults()
    {

        if (!$this->row)
            return 0;


        foreach (array_keys($this->fields) as $key) {
            if (in_array($this->fields[$key]['type'], ['relation_multiple'])) {
                continue;
            }


            $name = $this->fields[$key]['name'] ?? $this->fields[$key]['method'];


            $this->fields[$key]['value'] = $this->row->$name ?? null;


            $func = config("crud.fields." . $this->fields[$key]['type'] . ".beforeGet");


            if (is_callable($func))
                $this->fields[$key]['value'] = $func($this->fields[$key]['value']);


        }


    }


    public function resetFields()
    {
        $this->fields = [];
        return $this;
    }


    public function getRelated($object, $methodName)
    {
        return get_class($object->{$methodName}()->getRelated());
    }


    public function hasTrait($traitName)
    {
        $traits = class_uses($this->object, true);

        $traits = array_map(function ($n) {
            $class_parts = explode('\\', $n);
            return end($class_parts);
        }, $traits);

        return array_search($traitName, $traits) !== false;
    }


    public function getRelationType($object, $methodName)
    {
        $relationType = new \ReflectionClass($object->{$methodName}());
        return $relationType->getShortName();
    }


    public function reflectionMethod($object, $methodName)
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($object, []);
    }


    public function reflectionProperty($object, $propertyName)
    {
        $property = new \ReflectionProperty($object, $propertyName);
        $property->setAccessible(true);
        return $property->getValue($object);
    }


    public function getFields($key = null)
    {
        if ($key) {
            $values = [];
            foreach ($this->fields as $field) {
                $values[] = $this->checkRelationField($field)[$key] ?? '';
            }
            return $values;
        }

        foreach (array_keys($this->fields) as $key) {
            foreach ($this->fields[$key] as $value) {
                $this->fields[$key] = $this->checkRelationField($this->fields[$key]);
            }
        }


        return $this->fields;
    }


    public function getFieldValue($name)
    {
        foreach ($this->getFields() as $field) {
            if ($field['name'] == $name)
                return $field;
        }
    }


    public function checkRelationField($field)
    {

        if ($field['type'] !== 'relation')
            return $field;


        foreach (array_keys($field) as $key) {
            $relationType = $this->getRelationType($this->object, $field['method']);

            $field['type'] = $this->isMultiple($relationType) ? 'relation_multiple' : 'relation';
            $field['attribute'] = $field['attribute'] ?? 'id';
            $field['model'] = $this->getRelated($this->object, $field['method']);

            if (!$this->isMultiple($relationType))
                $field['name'] = $this->reflectionProperty($this->reflectionMethod($this->object, $field['method']), 'foreignKey');
        }

        return $field;
    }


    public function getValidations()
    {
        $validations = [];

        foreach ($this->getFields() as $field) {
            $validations[$field['name'] ?? $field['method']] = $field['validation'] ?? null;
        }
        return array_filter($validations);
    }


    public function getDatatableColumns()
    {
        $datable_columns = "[";
        foreach ($this->columns as $field):
            $datable_columns .= "{data: '" . $field['data'] . "', name: '" . $field['data'] . "', orderable: " . ($field['orderable'] ? 'true' : 'false') . ", searchable: " . ($field['searchable'] ? 'true' : 'false') . "},";
        endforeach;
        $datable_columns .= "]";

        return $datable_columns;
    }


    public function hasAction($action)
    {
        return in_array($action, $this->actions);

    }


    public function customActions($actions)
    {
        $this->actions = $actions;
    }


    public function checkInputsBeforeSet($inputs)
    {
        foreach ($this->getFields() as $field) {
            $key = $field['name'];
            $func = config("crud.fields." . $field['type'] . ".beforeSet");

            if (isset($field['beforeSet'])) {
                $func = $field['beforeSet'];
                $inputs[$key] = $func($this->createKeyValue($key, $inputs[$key]));
            } elseif (is_callable($func)) {
                $inputs[$key] = $func($this->createKeyValue($key, $inputs[$key]), $this);
            }
        }


        $this->inputs = array_merge($this->inputs, $inputs);

    }

    public function createKeyValue($key, $value)
    {
        $obj = new \stdClass();

        $obj->key = $key;
        $obj->value = $value;


        return $obj;

    }


    public function getFormInputs($request)
    {
        $inputs = $request->only($this->getFields('name'));


        $this->checkInputsBeforeSet($inputs);

        return $this->inputs;
    }


    public function notLoaded($field)
    {
        if (!in_array($field['type'], $this->loaded_fields)) {
            array_push($this->loaded_fields, $field['type']);
            return true;
        }
    }

}
