<?php

use mp_general\base\BaseFunctions;
use mp_general\forms\models\Field;

if (!defined('ABSPATH')) {
    exit;
}

function mp_ssv_show_select_input_field(Field $field)
{
    $selected = \mp_general\base\models\User::getCurrent()->getMeta($field->getName());
    ?>
    <div <?= $field->getElementAttributesString('div') ?>>
        <label <?= $field->getElementAttributesString('label', ['for']) ?>><?= BaseFunctions::escape($field->getProperty('title'), 'html') ?><?= $field->getProperty('required') ? '*' : '' ?><br/>
            <select <?= $field->getElementAttributesString('input', array_keys(Field::INPUT_ATTRIBUTES)) ?>>
                <?php foreach ($field->getProperty('value') as $option): ?>
                    <option value="<?= BaseFunctions::escape($option, 'attr') ?>" <?= selected($selected, $option) ?>><?= BaseFunctions::escape($option, 'html') ?></option>
                <?php endforeach; ?>
            </select>
        </label>
    </div>
    <?php
}
