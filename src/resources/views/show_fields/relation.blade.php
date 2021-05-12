<?php

$att = $field['attribute'];
if ($field['value'] && is_subclass_of(get_class($field['value']), 'Illuminate\Database\Eloquent\Model')) {
    echo $field['value']->$att;
} else if (is_object($field['value'])) {
    foreach ($field['value'] as $f) {
        echo $f->$att;
        echo '<br>';
    }
}

?>

