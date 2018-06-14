<?php

use mp_general\forms\models\Field;

if (!defined('ABSPATH')) {
    exit;
}

function mp_ssv_show_hidden_input_field(Field $field)
{
    ?>
    <!--suppress HtmlFormInputWithoutLabel -->
    <input <?= $field->getElementAttributesString('input') ?>/>
    <?php
}
