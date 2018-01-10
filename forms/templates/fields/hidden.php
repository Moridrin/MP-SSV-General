<?php

use mp_ssv_general\base\BaseFunctions;
use mp_ssv_general\Field;

if (!defined('ABSPATH')) {
    exit;
}

function show_hidden_input_field(array $field)
{
    $field += [
        'defaultValue' => null,
        'required'     => false,
    ];
    if (strtolower($field['defaultValue']) === 'now') {
        $field['defaultValue '] = (new DateTime($field['defaultValue']))->format('Y-m-d');
    }
    $inputId                = BaseFunctions::escape('input_' . $field['name'], 'attr');
    $inputElementAttributes = [
        'type'  => true,
        'value' => true,
    ];
    ?><input <?= Field::getElementAttributesString($field, $inputId, $inputElementAttributes, '') ?>/><?php
}
