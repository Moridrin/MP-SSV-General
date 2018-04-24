<?php

use mp_ssv_general\base\BaseFunctions;
use mp_ssv_general\base\SSV_Global;
use mp_ssv_general\forms\models\Field;
use mp_ssv_general\forms\SSV_Forms;

if (!defined('ABSPATH')) {
    exit;
}

function show_default_input_field(array $field)
{
    $field    += [
        'defaultValue' => null,
        'required'     => false,
    ];
    if (strtolower($field['defaultValue']) === 'now') {
        $field['defaultValue '] = (new DateTime($field['defaultValue']))->format('Y-m-d');
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
        $field['classes']['div'][]   = 'input-field';
        $field['classes']['input'][] = 'validate';
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
