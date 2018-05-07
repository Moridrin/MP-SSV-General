<?php

use mp_ssv_general\base\BaseFunctions;
use mp_ssv_general\forms\models\Field;

if (!defined('ABSPATH')) {
    exit;
}

function mp_ssv_show_select_input_field(Field $field)
{
    ?>
    <div <?= $field->getElementAttributesString('div') ?>>
        <label <?= $field->getElementAttributesString('label', ['for']) ?>><?= BaseFunctions::escape($field->getProperty('title'), 'html') ?><?= $field->getProperty('required') ? '*' : '' ?><br/>
            <select <?= $field->getElementAttributesString('input', array_keys(Field::INPUT_ATTRIBUTES)) ?>>
                <?php foreach ($field->getProperty('value') as $option): ?>
                    <?php $option = BaseFunctions::escape($option, 'html'); ?>
                    <option value="<?= $option ?>" <?= selected($option, $field->getProperty('value')) ?>><?= $option ?></option>
                <?php endforeach; ?>
            </select>
        </label>
    </div>
    <?php
}
