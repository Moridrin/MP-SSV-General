<?php

use mp_ssv_general\base\BaseFunctions;
use mp_ssv_general\base\SSV_Global;
use mp_ssv_general\forms\models\Field;
use mp_ssv_general\forms\SSV_Forms;

if (!defined('ABSPATH')) {
    exit;
}

function show_datetime_input_field(string $formId, array $field)
{
    $database        = SSV_Global::getDatabase();
    $field           += [
        'defaultValue' => null,
        'required'     => false,
    ];
    $table           = SSV_Forms::CUSTOMIZED_FIELDS_TABLE;
    $name            = $field['name'];
    $customizedField = $database->get_var("SELECT cf_json FROM $table WHERE cf_bf_id = $formId AND cf_bf_name = '$name'");
    if ($customizedField !== null) {
        $field             = json_decode($customizedField, true) + $field;
        $field['required'] = filter_var($field['required'], FILTER_VALIDATE_BOOLEAN);
    }
    $field['inputType'] = 'text';
    if (!isset($field['classes'])) {
        $field['classes'] = ['div' => '', 'title' => '', 'input' => 'datetimepicker'];
    } elseif (strpos($field['classes']['input'], '!datetimepicker') === false) {
        $field['classes']['input'] = 'datetimepicker';
    } else {
        $field['classes']['input'] = str_replace('!datetimepicker', '', $field['classes']['input']);
        $field['inputType']        = 'datetime'; // TODO also check for date and time (needs customization)
    }
    if (strtolower($field['defaultValue']) === 'now') {
        $field['defaultValue '] = (new DateTime($field['defaultValue']))->format('Y-m-d');
    }
    $inputElementAttributes = [
        'type',
        'value',
        'disabled',
        'checked',
        'required',
    ];
    if (current_theme_supports('materialize')) {
        ?>
        <div <?= Field::getElementAttributesString($field, 'div') ?>>
            <input <?= Field::getElementAttributesString($field, 'input', $inputElementAttributes, '') ?>/>
            <label <?= Field::getElementAttributesString($field, 'title', ['for']) ?>><?= BaseFunctions::escape($field['title'], 'html') ?><?= $field['required'] ? '*' : '' ?></label>
        </div>
        <?php
    } else {
        ?>
        <div <?= Field::getElementAttributesString($field, 'div') ?>>
            <label <?= Field::getElementAttributesString($field, 'title', ['for']) ?>><?= BaseFunctions::escape($field['title'], 'html') ?><?= $field['required'] ? '*' : '' ?></label><br/>
            <input <?= Field::getElementAttributesString($field, 'input', $inputElementAttributes, '') ?>/>
        </div>
        <?php
    }
}
