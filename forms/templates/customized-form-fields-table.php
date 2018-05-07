<?php

use mp_ssv_general\base\BaseFunctions;
use mp_ssv_general\forms\models\Field;

if (!defined('ABSPATH')) {
    exit;
}

function mp_ssv_show_form_fields_table(array $fields)
{
    ?>
    <div style="overflow-x: auto;">
        <table id="formFieldsContainer" class="wp-list-table widefat striped">
            <thead>
            <tr id="formFieldsListTop">
                <th scope="col" id="author" class="manage-column">Title</th>
                <th scope="col" id="author" class="manage-column">Input Type</th>
                <th scope="col" id="author" class="manage-column">Default Value</th>
            </tr>
            </thead>
            <tbody id="the-list">
            <?php if (!empty($fields)): ?>
                <?php /** @var Field $field */ ?>
                <?php foreach ($fields as $field): ?>
                    <tr
                            id="field_<?= BaseFunctions::escape($field->getName(), 'attr') ?>"
                            draggable="true"
                            class="formField"
                            data-properties="<?= BaseFunctions::escape(json_encode($field->getData()), 'attr') ?>"
                    >
                        <td>
                            <strong class="fieldName_js"><?= BaseFunctions::escape($field->getProperty('title'), 'html') ?></strong>
                            <input type="hidden" name="fields[]" value="<?= BaseFunctions::escape(json_encode($field->getData()), 'attr') ?>">
                        </td>
                        <td><?= BaseFunctions::escape($field->getProperty('type'), 'html') ?></td>
                        <td>
                            <div class="row-actions" style="float: right">
                                <a href="javascript:void(0)" onclick="formEditor.customize('<?= BaseFunctions::escape($field->getName(), 'attr') ?>')" class="editinline">
                                    <span class="dashicons dashicons-admin-customizer"></span>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            <tr id="no-items" class="no-items" <?= !empty($fields) ? 'style="display: none;"' : '' ?>>
                <td class="colspanchange" colspan="8">There are no fields in the form yet.<br/>Drag and drop a field from the fields list to add it to the form.</td>
            </tr>
            <tr id="dropPreview">
                <td></td>
                <td></td>
                <td></td>
            </tr>
            </tbody>
            <tfoot>
            <tr id="formFieldsListBottom">
                <th scope="col" id="author" class="manage-column">Title</th>
                <th scope="col" id="author" class="manage-column">Input Type</th>
                <th scope="col" id="author" class="manage-column">Default Value</th>
            </tr>
            </tfoot>
        </table>
    </div>
    <?php
}
