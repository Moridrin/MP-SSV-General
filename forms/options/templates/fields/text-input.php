<?php

use mp_ssv_general\base\BaseFunctions;
use mp_ssv_general\Field;

if (!defined('ABSPATH')) {
    exit;
}

function show_text_input_field(array $field)
{
    $field += [
        'defaultValue' => null,
        'required' => false,
    ];
    if (strtolower($field['defaultValue']) === 'now') {
        $field['defaultValue '] = (new DateTime($field['defaultValue']))->format('Y-m-d');
    }
    $divId                  = BaseFunctions::escape('div_' . $field['name'], 'attr');
    $inputId                = BaseFunctions::escape('input_' . $field['name'], 'attr');
    $labelId                = BaseFunctions::escape('label_' . $field['name'], 'attr');
    $inputElementAttributes = [
        'type'     => true,
        'disabled' => true,
        'checked'  => true,
        'required' => true,
    ];
    if (current_theme_supports('materialize')) {
        ?>
        <div <?= Field::getElementAttributesString($field, $divId) ?>>
            <input type="text" <?= Field::getElementAttributesString($field, $inputId, '', $inputElementAttributes) ?>/>
            <label <?= Field::getElementAttributesString($field, $labelId) ?>for="<?= $labelId ?>"><?= BaseFunctions::escape($field['title'], 'html') ?><?= $field['required'] ? '*' : '' ?></label>
        </div>
        <?php
    } else {
        ?>
        <label <?= Field::getElementAttributesString($field, $labelId) ?>for="<?= $labelId ?>"><?= BaseFunctions::escape($field['title'], 'html') ?><?= $field['required'] ? '*' : '' ?></label>
        <input type="text" <?= Field::getElementAttributesString($field, $inputId, '', ['disabled' => true, 'checked' => true, 'required' => true]) ?>/>
        <?php
    }
}
