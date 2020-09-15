<?php


namespace Afracode\CRUD\App\Classes;


use Afracode\CRUD\App\Classes\Traits\Datatable;
use Illuminate\Support\Arr;

class Crud
{
    use Datatable;

    public $model;
    public $entities;
    public $columns = [];
    public $fields = [];
    public $object;
    public $tmpPath;
    public $row;
    public $query;
    private $reserved_field_key;
    private $actions;

    public function __construct()
    {
        $this->tmpPath = storage_path('tmp');
        $this->actions = ['read', 'create', 'edit', 'delete'];
    }


    public function route($route, $id = null)
    {
        $entities = $this->entities;

        if (in_array($route, ['update', 'show', 'delete']))
            return '/' . $entities . '/' . $id;
        elseif ($route == 'edit')
            return '/' . $entities . '/' . $id . '/edit';
        elseif ($route == 'create')
            return '/' . $entities . '/create';
        elseif (in_array($route, ['index', 'store']))
            return '/' . $entities;
        elseif ($route == 'datatable')
            return '/' . $entities . '/datatable/';
        elseif ($route == 'deleteMedia')
            return '/' . $entities . '/' . $id . '/media';
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


    public function setColumn($data, $title = null, $orderable = null, $searchable = null)
    {
        array_push($this->columns,
            [
                'data' => $data,
                'name' => $title ?? ((stripos(trans('db.' . $data), "db.") === false) ? trans('db.' . $data) : ucfirst($data)),
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


    public function initFieldLabel($field)
    {

        $field['name'] = isset($field['name']) ? $field['name'] : $field['method'];

        $field['label'] = $field['label'] ?? ((stripos(trans('db.' . $field['name']), "db.") === false) ? trans('db.' . $field['name']) : ucfirst($field['name']));

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


            $func = config("crud.fields." . $this->fields[$key]['type'] . ".adaptDefault");


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
            for ($i = 0; $i < count($this->fields); $i++)
                $values[] = $this->checkRelationField($this->fields[$i])[$key] ?? '';
            return $values;
        }

        foreach (array_keys($this->fields) as $key) {
            foreach ($this->fields[$key] as $value) {
                $this->fields[$key] = $this->checkRelationField($this->fields[$key]);
            }
        }


        return $this->fields;
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


    public function checkInputsBeforeSet($fields)
    {
        foreach ($this->getFields() as $field) {
            $key = $field['name'];
            $func = config("crud.fields." . $field['type'] . ".adaptForDatabase");

            if (isset($field['adaptForDatabase'])) {
                $func = $field['adaptForDatabase'];
                $fields[$key] = $func($fields[$key]);
            } elseif (is_callable($func)) {
                $fields[$key] = $func($fields[$key]);
            }
        }

        return $fields;
    }


    public function getFormInputs($request)
    {
        $inputs = $request->only($this->getFields('name'));
        return $this->checkInputsBeforeSet($inputs);
    }

}
