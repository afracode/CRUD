<?php

if (!function_exists('crudView')) {
    function crudView($view, $crud = null)
    {
        $originalTheme = 'crud::';
        $theme = config('crud.base.view_namespace');


        if (is_null($theme)) {
            $theme = $originalTheme;
        }

        $returnView = $theme . $view;


        if ($crud)
            $returnView = config('crud.base.customized_views_path') . $crud->entities . '.' . $view;


        if (!view()->exists($returnView)) {
            $returnView = $originalTheme . $view;
        }


        return $returnView;
    }
}


if (!function_exists('crudRequired')) {
    function crudFieldRequired($field)
    {
        if (isset($field['validation']) && (strpos($field['validation'], 'required') !== false))
            return config('crud.base.field_required_span');
    }
}


if (!function_exists('menuName')) {
    function menuName($menu)
    {
        $name = 'menu';
        $name .= '.';
        $name .= $menu->href;

        return trans('crud.' . $name);

    }
}


if (!function_exists('crudShowLabel')) {
    function crudShowLabel($field)
    {
        $trans = 'crud.db.';
        $trans .= $field['name'];
        $value = "<strong>";
        $value .= trans($trans);
        $value .= ':';
        $value .= "</strong>";


        return $value;
    }
}

if (!function_exists('numberFormatPrecision')) {
    function numberFormatPrecision($number, $precision = 2, $separator = '.')
    {
        $numberParts = explode($separator, $number);
        $response = $numberParts[0];
        if (count($numberParts) > 1 && $precision > 0) {
            $response .= $separator;
            $response .= substr($numberParts[1], 0, $precision);
        }
        return $response;
    }
}


if (!function_exists('queryAdaptToSelect')) {
    function queryAdaptToSelect($query, $attribute, $key = 'id'): array
    {

        $result = [];

        foreach ($query as $row)
            $result[$row->{$key}] = $row->{$attribute};

        return $result;
    }
}
