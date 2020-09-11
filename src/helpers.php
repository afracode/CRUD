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
