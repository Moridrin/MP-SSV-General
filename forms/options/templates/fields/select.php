<?php

use mp_ssv_general\base\BaseFunctions;
use mp_ssv_general\Field;

if (!defined('ABSPATH')) {
    exit;
}

function show_select_input_field(array $field)
{
    $field += [
        'defaultValue' => null,
        'required'     => false,
    ];
    if (strtolower($field['defaultValue']) === 'now') {
        $field['defaultValue '] = (new DateTime($field['defaultValue']))->format('Y-m-d');
    }
    $divId                  = BaseFunctions::escape('div_' . $field['name'], 'attr');
    $inputId                = BaseFunctions::escape('input_' . $field['name'], 'attr');
    $labelId                = BaseFunctions::escape('label_' . $field['name'], 'attr');
    $options                = json_decode($field['options']);
    $value                  = BaseFunctions::escape($field['value'], 'html');
    $inputElementAttributes = [
        'disabled',
        'multiple',
        'size',
        'type',
        'value',
        'checked',
        'required',
        'autocomplete',
        'placeholder',
        'list',
        'pattern',
    ];
    if (current_theme_supports('materialize')) {
        ?>
        <div <?= Field::getElementAttributesString($field, $divId) ?>>
            <select <?= Field::getElementAttributesString($field, $inputId, $inputElementAttributes, '') ?>>
                <?php foreach ($options as $option): ?>
                    <option value="<?= BaseFunctions::escape($option, 'html') ?>" <?= selected($option, $value) ?>><?= $option ?></option>
                <?php endforeach; ?>
            </select>
            <label <?= Field::getElementAttributesString($field, $labelId) ?> for="<?= $labelId ?>"><?= BaseFunctions::escape($field['title'], 'html') ?><?= $field['required'] ? '*' : '' ?></label>
        </div>
        <?php
    } else {
        ?>
        <label <?= Field::getElementAttributesString($field, $labelId) ?> for="<?= $labelId ?>"><?= BaseFunctions::escape($field['title'], 'html') ?><?= $field['required'] ? '*' : '' ?></label><br/>
        <select <?= Field::getElementAttributesString($field, $inputId, $inputElementAttributes, '') ?>>
            <?php foreach ($options as $option): ?>
                <?php $option = BaseFunctions::escape($option, 'html'); ?>
                <option value="<?= $option ?>" <?= selected($option, $value) ?>><?= $option ?></option>
            <?php endforeach; ?>
        </select><br/>
        <?php
    }
}
