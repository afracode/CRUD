<input
    type="hidden"
    name="{{ $field['name'] }}"
    value="{{ old($field['name']) ?? $field['value'] ?? $field['default'] ?? '' }}"
>
