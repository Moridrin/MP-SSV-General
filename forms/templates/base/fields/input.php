<?php

use mp_ssv_general\base\BaseFunctions;
use mp_ssv_general\forms\models\Field;

if (!defined('ABSPATH')) {
    exit;
}

function mp_ssv_show_default_input_field(Field $field)
{
    ?>
    <div <?= $field->getElementAttributesString('div') ?>>
        <label <?= $field->getElementAttributesString('title', ['for']) ?>><?= BaseFunctions::escape($field->getProperty('title'), 'html') ?><?= $field->getProperty('required') ? '*' : '' ?></label>
        <input <?= $field->getElementAttributesString('input') ?>/>
    </div>
    <?php
}
