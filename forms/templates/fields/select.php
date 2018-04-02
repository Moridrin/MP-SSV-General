<?php

use mp_ssv_general\base\BaseFunctions;
use mp_ssv_general\base\SSV_Global;
use mp_ssv_general\forms\models\Field;
use mp_ssv_general\forms\SSV_Forms;

if (!defined('ABSPATH')) {
    exit;
}

function show_select_input_field(string $formId, array $field)
{
    $database = SSV_Global::getDatabase();
    $field += [
        'defaultValue' => null,
        'required'     => false,
    ];
    if (strtolower($field['defaultValue']) === 'now') {
        $field['defaultValue '] = (new DateTime($field['defaultValue']))->format('Y-m-d');
    }
    $divId           = BaseFunctions::escape('div_' . $field['name'], 'attr');
    $inputId         = BaseFunctions::escape('input_' . $field['name'], 'attr');
    $labelId         = BaseFunctions::escape('label_' . $field['name'], 'attr');
    $table           = SSV_Forms::CUSTOMIZED_FIELDS_TABLE;
    $name            = $field['name'];
    $customizedField = $database->get_var("SELECT cf_json FROM $table WHERE cf_bf_id = $formId AND cf_bf_name = '$name'");
    if ($customizedField !== null) {
        $field             = json_decode($customizedField, true) + $field;
        $field['required'] = filter_var($field['required'], FILTER_VALIDATE_BOOLEAN);
        $field['multiple'] = filter_var($field['multiple'], FILTER_VALIDATE_BOOLEAN);
    }
    $field['options'] = json_decode($field['options']);
    if (empty($field['value']) && !empty($field['defaultValue'])) {
        $value = BaseFunctions::escape($field['defaultValue'], 'html');
    } else {
        $value = BaseFunctions::escape($field['value'], 'html');
    }
    $inputElementAttributes = [
        'disabled',
        'multiple',
        'size',
        'type',
        'value',
        'required',
        'placeholder',
    ];
    if (current_theme_supports('materialize')) {
        ?>
        <div <?= Field::getElementAttributesString($field, $divId) ?>>
            <select <?= Field::getElementAttributesString($field, $inputId, $inputElementAttributes, '') ?>>
                <?php foreach ($field['options'] as $option): ?>
                    <option value="<?= BaseFunctions::escape($option, 'html') ?>" <?= selected($option, $value) ?>><?= $option ?></option>
                <?php endforeach; ?>
            </select>
            <label <?= Field::getElementAttributesString($field, $labelId) ?> for="<?= $labelId ?>"><?= BaseFunctions::escape($field['title'], 'html') ?><?= $field['required'] ? '*' : '' ?></label>
        </div>
        <?php
    } else {
        ?>
        <div <?= Field::getElementAttributesString($field, $divId) ?>>
            <label <?= Field::getElementAttributesString($field, $labelId) ?> for="<?= $labelId ?>"><?= BaseFunctions::escape($field['title'], 'html') ?><?= $field['required'] ? '*' : '' ?></label><br/>
            <select <?= Field::getElementAttributesString($field, $inputId, $inputElementAttributes, '') ?>>
                <?php foreach ($field['options'] as $option): ?>
                    <?php $option = BaseFunctions::escape($option, 'html'); ?>
                    <option value="<?= $option ?>" <?= selected($option, $value) ?>><?= $option ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php
    }
}
