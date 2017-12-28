<?php

use mp_ssv_general\base\BaseFunctions;

if (!defined('ABSPATH')) {
    exit;
}

function show_base_form_fields_table(array $fields, string $order = 'asc', string $orderBy = 'bf_title', bool $canManage = false)
{
    $newOrder         = $order === 'asc' ? 'desc' : 'asc';
    $orderByTitle     = ($orderBy === 'bf_title' ? 'sorted' : '');
    $orderByName      = ($orderBy === 'bf_name' ? 'sorted' : '');
    $orderByInputType = ($orderBy === 'bf_inputType' ? 'sorted' : '');
    $orderByValue     = ($orderBy === 'bf_value' ? 'sorted' : '');
    $orderTitle       = ($orderBy === 'bf_title' ? $newOrder : $order);
    $orderName        = ($orderBy === 'bf_name' ? $newOrder : $order);
    $orderInputType   = ($orderBy === 'bf_inputType' ? $newOrder : $order);
    $orderValue       = ($orderBy === 'bf_value' ? $newOrder : $order);
    ?>
    <div style="overflow-x: auto;">
        <?php if ($canManage): ?>
            <div class="tablenav top">
                <div class="alignleft actions bulkactions">
                    <label for="bulk-action-selector-top" class="screen-reader-text">Select bulk action</label><select name="action" id="bulk-action-selector-top">
                        <option value="-1">Bulk Actions</option>
                        <option value="delete-selected">Delete</option>
                    </select>
                    <input type="submit" id="doaction" class="button action" value="Apply">
                </div>
                <br class="clear">
            </div>
        <?php endif; ?>
        <table class="wp-list-table widefat striped">
            <thead>
            <tr>
                <td id="cb" class="manage-column column-cb check-column">
                    <?php if ($canManage): ?>
                        <label class="screen-reader-text" for="cb-select-all-1">Select All</label>
                        <input id="cb-select-all-1" type="checkbox">
                    <?php endif; ?>
                </td>
                <th scope="col" id="title" class="manage-column column-name column-primary sortable <?= BaseFunctions::escape($orderTitle, 'attr') ?> <?= $orderByTitle ?>">
                    <a href="?page=<?= BaseFunctions::escape($_GET['page'], 'attr') ?>&orderby=bf_title&order=<?= BaseFunctions::escape($orderTitle, 'attr') ?>"><span>Title</span><span class="sorting-indicator"></span></a>
                </th>
                <th scope="col" id="name" class="manage-column column-name sortable <?= BaseFunctions::escape($orderName, 'attr') ?> <?= $orderByName ?>">
                    <a href="?page=<?= BaseFunctions::escape($_GET['page'], 'attr') ?>&orderby=bf_name&order=<?= BaseFunctions::escape($orderName, 'attr') ?>"><span>Name</span><span class="sorting-indicator"></span></a>
                </th>
                <th scope="col" id="input_type" class="manage-column column-name sortable <?= BaseFunctions::escape($orderInputType, 'attr') ?> <?= $orderByInputType ?>">
                    <a href="?page=<?= BaseFunctions::escape($_GET['page'], 'attr') ?>&orderby=bf_inputType&order=<?= BaseFunctions::escape($orderInputType, 'attr') ?>"><span>Input Type</span><span class="sorting-indicator"></span></a>
                </th>
                <th scope="col" id="value" class="manage-column column-name sortable <?= BaseFunctions::escape($orderValue, 'attr') ?> <?= $orderByValue ?>">
                    <a href="?page=<?= BaseFunctions::escape($_GET['page'], 'attr') ?>&orderby=bf_value&order=<?= BaseFunctions::escape($orderValue, 'attr') ?>"><span>Value</span><span class="sorting-indicator"></span></a>
                </th>
            </tr>
            </thead>
            <tbody id="the-list">
            <?php if (!empty($fields)): ?>
                <?php foreach ($fields as $field): ?>
                    <tr id="<?= $field->bf_id ?>_tr" class="inactive">
                        <th id="<?= $field->bf_id ?>_id_td" class="check-column">
                            <?php if ($canManage): ?>
                                <input type="checkbox" id="<?= $field->bf_id ?>_id" name="fieldIds[]" value="<?= $field->bf_id ?>">
                            <?php endif; ?>
                        </th>
                        <td id="<?= $field->bf_id ?>_field_title_td">
                            <strong><?= $field->bf_title ?></strong>
                            <div class="row-actions">
                                <?php if ($canManage): ?>
                                    <span class="inline hide-if-no-js"><a href="javascript:void(0)" onclick="fieldsManager.inlineEdit('<?= $field->bf_id ?>')" class="editinline"
                                                                          aria-label="Quick edit “Hello world!” inline">Quick&nbsp;Edit</a> | </span>
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
                    <td class="colspanchange" colspan="8">No Base Fields found</td>
                </tr>
            <?php endif; ?>
            </tbody>
            <tfoot>
            <tr>
                <td id="cb" class="manage-column column-cb check-column">
                    <?php if ($canManage): ?>
                        <label class="screen-reader-text" for="cb-select-all-1">Select All</label>
                        <input id="cb-select-all-1" type="checkbox">
                    <?php endif; ?>
                </td>
                <th scope="col" id="title" class="manage-column column-name column-primary sortable <?= BaseFunctions::escape($orderTitle, 'attr') ?> <?= $orderByTitle ?>">
                    <a href="?page=<?= BaseFunctions::escape($_GET['page'], 'attr') ?>&orderby=bf_title&order=<?= BaseFunctions::escape($orderTitle, 'attr') ?>"><span>Title</span><span class="sorting-indicator"></span></a>
                </th>
                <th scope="col" id="name" class="manage-column column-name sortable <?= BaseFunctions::escape($orderName, 'attr') ?> <?= $orderByName ?>">
                    <a href="?page=<?= BaseFunctions::escape($_GET['page'], 'attr') ?>&orderby=bf_name&order=<?= BaseFunctions::escape($orderName, 'attr') ?>"><span>Name</span><span class="sorting-indicator"></span></a>
                </th>
                <th scope="col" id="input_type" class="manage-column column-name sortable <?= BaseFunctions::escape($orderInputType, 'attr') ?> <?= $orderByInputType ?>">
                    <a href="?page=<?= BaseFunctions::escape($_GET['page'], 'attr') ?>&orderby=bf_inputType&order=<?= BaseFunctions::escape($orderInputType, 'attr') ?>"><span>Input Type</span><span class="sorting-indicator"></span></a>
                </th>
                <th scope="col" id="value" class="manage-column column-name sortable <?= BaseFunctions::escape($orderValue, 'attr') ?> <?= $orderByValue ?>">
                    <a href="?page=<?= BaseFunctions::escape($_GET['page'], 'attr') ?>&orderby=bf_value&order=<?= BaseFunctions::escape($orderValue, 'attr') ?>"><span>Value</span><span class="sorting-indicator"></span></a>
                </th>
            </tr>
            </tfoot>
        </table>
    </div>
    <?php
}