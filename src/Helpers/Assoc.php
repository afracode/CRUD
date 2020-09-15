<?php


namespace Afracode\CRUD\Helpers;


class Assoc
{
    public static function setObject($assoc)
    {
        $obj= [];
        foreach ($assoc as $arr) {
            $obj[] = new Obj($arr);
        }
        return $obj;
    }
}

