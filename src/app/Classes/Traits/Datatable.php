<?php

namespace Afranext\Crud\App\Classes\Traits;

trait Datatable
{

    public function format($key)
    {
        $changeTo = config('crud.format.' . $key);
        $this->editColumn($changeTo);
    }


    public function default($default)
    {
        $changeTo = function ($row) use ($default) {
            return $row->day ?? $default;
        };

        $this->editColumn($changeTo);
    }


    public function isStatus($config , $field = "status")
    {
        $changeTo = function ($row) use ($config , $field) {
            return config('crud.status.' . $config)[$row->{$field}];
        };

        $this->editColumn($changeTo);
    }

}
