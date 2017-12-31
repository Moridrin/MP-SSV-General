<?php

if (!defined('ABSPATH')) {
    exit;
}

function show_customized_form_fields_table(array $fields)
{
    $canManage = false;
    ?>
    <div style="overflow-x: auto;">
        <table id="formFieldsContainer" class="wp-list-table widefat striped">
            <thead>
            <tr id="formFieldsListTop">
                <th scope="col" id="author" class="manage-column column-author">Title</th>
                <th scope="col" id="author" class="manage-column column-author">Field Type</th>
                <th scope="col" id="author" class="manage-column column-author">Input Type</th>
                <th scope="col" id="author" class="manage-column column-author">Value</th>
            </tr>
            </thead>
            <tbody id="formFieldsList">
            <?php if (!empty($fields)): ?>
                <?php foreach ($fields as $field): ?>
                    <tr id="<?= $field->bf_id ?>_tr" class="inactive formField">
                        <td id="<?= $field->bf_id ?>_field_title_td">
                            <strong><?= $field->bf_title ?></strong>
                            <div class="row-actions">
                                <?php if ($canManage): ?>
                                    <span class="inline hide-if-no-js"><a href="javascript:void(0)" onclick="fieldsManager.inlineEdit('<?= $field->bf_id ?>')" class="editinline" aria-label="Quick edit “Hello world!” inline">Quick&nbsp;Edit</a> | </span>
                                    <span class="trash"><a href="javascript:void(0)" onclick="fieldsManager.deleteRow('<?= $field->bf_id ?>')" class="submitdelete" aria-label="Move “Hello world!” to the Trash">Trash</a></span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td id="<?= $field->bf_id ?>_name_td">
                            <?= $field->bf_name ?>
                        </td>
                        <td id="<?= $field->bf_id ?>_inputType_td">
                            <?= $field->bf_inputType ?>
                        </td>
                        <?php if ($field->bf_inputType === 'select' || $field->bf_inputType === 'hidden' || $field->bf_inputType === 'role_select'): ?>
                            <td class="value_td" id="<?= $field->bf_id ?>_value_td">
                                <?= $field->bf_value ?>
                            </td>
                        <?php else: ?>
                            <td id="<?= $field->bf_id ?>_empty_td"></td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr id="no-items" class="no-items">
                    <td class="colspanchange" colspan="8">There are no fields in the form yet.<br/>Drag and drop a field from the fields list to add it to the form.</td>
                </tr>
            <?php endif; ?>
            </tbody>
            <tfoot>
            <tr id="formFieldsListBottom">
                <th scope="col" id="author" class="manage-column column-author">Title</th>
                <th scope="col" id="author" class="manage-column column-author">Field Type</th>
                <th scope="col" id="author" class="manage-column column-author">Input Type</th>
                <th scope="col" id="author" class="manage-column column-author">Value</th>
            </tr>
            </tfoot>
        </table>
    </div>
    <?php
}
