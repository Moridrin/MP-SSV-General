<?php

use mp_ssv_general\base\BaseFunctions;
use mp_ssv_general\Field;
use mp_ssv_general\forms\SSV_Forms;

if (!defined('ABSPATH')) {
    exit;
}

function show_default_input_field(string $formId, array $field)
{
    /** @var wpdb $wpdb */
    global $wpdb;
    $field += [
        'defaultValue' => null,
        'required'     => false,
    ];
    if (strtolower($field['defaultValue']) === 'now') {
        $field['defaultValue '] = (new DateTime($field['defaultValue']))->format('Y-m-d');
    }
    $table           = SSV_Forms::CUSTOMIZED_FIELDS_TABLE;
    $name            = $field['name'];
    $customizedField = $wpdb->get_var("SELECT cf_json FROM $table WHERE cf_f_id = $formId AND cf_bf_name = '$name'");
    if ($customizedField !== null) {
        $field                 = json_decode($customizedField, true) + $field;
        $field['required']     = filter_var($field['required'], FILTER_VALIDATE_BOOLEAN);
        $field['autocomplete'] = filter_var($field['autocomplete'], FILTER_VALIDATE_BOOLEAN);
    }
    $inputElementAttributes = [
        'type',
        'value',
        'disabled',
        'checked',
        'required',
        'autocomplete',
        'placeholder',
        'list',
        'pattern',
    ];
    if (current_theme_supports('materialize')) {
        ?>
        <div <?= Field::getElementAttributesString($field, 'div') ?>>
            <input <?= Field::getElementAttributesString($field, 'input', $inputElementAttributes, '') ?>/>
            <label <?= Field::getElementAttributesString($field, 'label', ['for']) ?>><?= BaseFunctions::escape($field['title'], 'html') ?><?= $field['required'] ? '*' : '' ?></label>
        </div>
        <?php
    } else {
        ?>
        <div <?= Field::getElementAttributesString($field, 'div') ?>>
            <label <?= Field::getElementAttributesString($field, 'label', ['for']) ?>><?= BaseFunctions::escape($field['title'], 'html') ?><?= $field['required'] ? '*' : '' ?></label><br/>
            <input <?= Field::getElementAttributesString($field, 'input', $inputElementAttributes, '') ?>/><br/>
        </div>
        <?php
    }
}
