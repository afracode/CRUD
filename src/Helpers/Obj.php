<?php


namespace Afracode\CRUD\Helpers;


class Obj
{
    public function __construct($item)
    {
        foreach ($item as $property => $value) {
            $this->{$property} = $value;
        }
    }
}
