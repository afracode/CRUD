<?php

if (! function_exists('crudView')) {
    function crudView($view)
    {
        $originalTheme = 'crud::';
        $theme = config('crud.view_namespace');



        if (is_null($theme)) {
            $theme = $originalTheme;
        }

        $returnView = $theme.$view;

        if (! view()->exists($returnView)) {
            $returnView = $originalTheme.$view;
        }


        return $returnView;
    }
}


if (!function_exists('crudRequired')) {
    function crudFieldRequired($field)
    {
        if (isset($field['validation']) && (strpos($field['validation'],'required') !== false))
            return config('crud.field_required_span');
    }
}


if (!function_exists('menuName')) {
    function menuName($menu)
    {
        $name = 'menu';
        $name .= '.';
        $name .= $menu->href;

        return trans($name);

    }
}


