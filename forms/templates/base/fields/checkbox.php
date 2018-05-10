<?php

use mp_general\base\BaseFunctions;
use mp_general\base\SSV_Global;
use mp_general\forms\models\Field;
use mp_general\forms\SSV_Forms;

if (!defined('ABSPATH')) {
    exit;
}

function show_checkbox_input_field(Field $field)
{
    $database = SSV_Global::getDatabase();
    $field    += [
        'defaultValue' => null,
        'required'     => false,
    ];
    if (strtolower($field['defaultValue']) === 'now') {
        $field['defaultValue '] = (new DateTime($field['defaultValue']))->format('Y-m-d');
    }
    $table           = SSV_Forms::CUSTOMIZED_FIELDS_TABLE;
    $name            = $field['name'];
    $formId          = $field['formId'];
    $customizedField = $database->get_var("SELECT cf_json FROM $table WHERE cf_bf_id = $formId AND cf_bf_name = '$name'");
    if ($customizedField !== null) {
        $field             = json_decode($customizedField, true) + $field;
        $field['required'] = filter_var($field['required'], FILTER_VALIDATE_BOOLEAN);
    }
    $field['value']         = 'true';
    $field['classes']       = [
        'div'   => [],
        'input' => ['filled-in'],
        'label' => [],
    ];
    $inputElementAttributes = [
        'type',
        'disabled',
        'checked',
        'required',
    ];
    if (current_theme_supports('materialize')) {
        if (!empty($field['defaultValue'])) {
            ?>
            <div <?= Field::getElementAttributesString($field, 'div') ?>>
                <label <?= Field::getElementAttributesString($field, 'title') ?>><?= BaseFunctions::escape($field['title'], 'html') ?><?= $field['required'] ? '*' : '' ?></label>
                <input <?= Field::getElementAttributesString($field, 'input', $inputElementAttributes, '') ?>/> <label <?= Field::getElementAttributesString($field, 'label', ['for']) ?>><?= BaseFunctions::escape($field['defaultValue'], 'html') ?></label>
            </div>
            <?php
        } else {
            ?>
            <div <?= Field::getElementAttributesString($field, 'div') ?>>
                <input <?= Field::getElementAttributesString($field, 'input', $inputElementAttributes, '') ?>/> <label <?= Field::getElementAttributesString($field, 'title', ['for']) ?>><?= BaseFunctions::escape($field['title'], 'html') ?></label>
            </div>
            <?php
        }
    } else {
        ?>
        <div <?= Field::getElementAttributesString($field, 'div') ?>>
            <label <?= Field::getElementAttributesString($field, 'title') ?>><?= BaseFunctions::escape($field['title'], 'html') ?><?= $field['required'] ? '*' : '' ?></label><br/>
            <input <?= Field::getElementAttributesString($field, 'input', $inputElementAttributes, '') ?>/> <label <?= Field::getElementAttributesString($field, 'label', ['for']) ?>><?= BaseFunctions::escape($field['defaultValue'], 'html') ?></label>
        </div>
        <?php
    }
}
