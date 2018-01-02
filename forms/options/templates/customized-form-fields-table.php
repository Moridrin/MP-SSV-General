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
            <tbody id="formFieldsList">
            <?php if (!empty($fields)): ?>
                <?php foreach ($fields as $field): ?>
                    <tr draggable="true" class="formField">
                        <td>
                            <input type="hidden" name="form_fields[]" value="<?= $field->bf_name ?>">
                            <strong><?= $field->bf_title ?></strong>
                        </td>
                        <td>Input</td>
                        <td><?= $field->bf_inputType ?></td>
                        <td><?= $field->bf_value ?></td>
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
