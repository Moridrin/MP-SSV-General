<?php

if (!defined('ABSPATH')) {
    exit;
}

function show_customized_form_fields_table(array $fields)
{
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
            <tbody id="the-list">
            <?php if (!empty($fields)): ?>
                <?php foreach ($fields as $field): ?>
                    <tr id="<?= $field->bf_id ?>_tr" draggable="true" class="formField" data-properties='{"title":"Username","fieldType":"Input","inputType":"text","value":"","classes":{"div":"class a","label":"b d","input":"c"},"styles":{"div":"height: 10px;","label":"height: 11px;","input":"height: 12px;"}}'>
                        <td>
                            <input type="hidden" name="form_fields[]" value="<?= $field->bf_name ?>">
                            <strong id="<?= $field->bf_id ?>_title"><?= $field->bf_title ?></strong>
                            <span class="inline-actions"> | <a href="javascript:void(0)" onclick="fieldsCustomizer.inlineEdit('<?= $field->bf_id ?>')" class="editinline" aria-label="Quick edit “<?= $field->bf_title ?>” inline">Quick Edit</a></span>
                        </td>
                        <td id="<?= $field->bf_id ?>_fieldType">Input</td>
                        <td id="<?= $field->bf_id ?>_inputType"><?= $field->bf_inputType ?></td>
                        <td id="<?= $field->bf_id ?>_value"><?= $field->bf_value ?></td>
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
