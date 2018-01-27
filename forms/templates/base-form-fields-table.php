<?php

use mp_ssv_general\base\BaseFunctions;

if (!defined('ABSPATH')) {
    exit;
}

function show_base_form_fields_table(array $fields, string $order = 'asc', string $orderBy = 'bf_title', bool $canManage = false)
{
    $newOrder         = $order === 'asc' ? 'desc' : 'asc';
    $orderByName      = ($orderBy === 'name' ? 'sorted' : '');
    $orderByInputType = ($orderBy === 'inputType' ? 'sorted' : '');
    $orderByValue     = ($orderBy === 'value' ? 'sorted' : '');
    $orderName        = ($orderBy === 'name' ? $newOrder : $order);
    $orderInputType   = ($orderBy === 'inputType' ? $newOrder : $order);
    $orderValue       = ($orderBy === 'value' ? $newOrder : $order);
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
                <th scope="col" id="name" class="manage-column column-name sortable <?= BaseFunctions::escape($orderName, 'attr') ?> <?= $orderByName ?>">
                    <a href="?page=<?= BaseFunctions::escape($_GET['page'], 'attr') ?>&orderby=name&order=<?= BaseFunctions::escape($orderName, 'attr') ?>"><span>Name</span><span class="sorting-indicator"></span></a>
                </th>
                <th scope="col" id="input_type" class="manage-column column-name sortable <?= BaseFunctions::escape($orderInputType, 'attr') ?> <?= $orderByInputType ?>">
                    <a href="?page=<?= BaseFunctions::escape($_GET['page'], 'attr') ?>&orderby=inputType&order=<?= BaseFunctions::escape($orderInputType, 'attr') ?>"><span>Input Type</span><span class="sorting-indicator"></span></a>
                </th>
                <th scope="col" id="value" class="manage-column column-name sortable <?= BaseFunctions::escape($orderValue, 'attr') ?> <?= $orderByValue ?>">
                    <a href="?page=<?= BaseFunctions::escape($_GET['page'], 'attr') ?>&orderby=value&order=<?= BaseFunctions::escape($orderValue, 'attr') ?>"><span>Value</span><span class="sorting-indicator"></span></a>
                </th>
            </tr>
            </thead>
            <tbody id="the-list">
            <?php if (!empty($fields)): ?>
                <?php foreach ($fields as $field): ?>
                    <?php $properties = json_decode($field->bf_properties); ?>
                    <?php $properties->value = isset($properties->value) ? $properties->value : '' ?>
                    <?php if ($field->bf_name !== null): ?>
                        <tr id="field_<?= $field->bf_name ?>" class="inactive" data-properties='<?= json_encode($properties) ?>'>
                            <th class="check-column">
                                <?php if ($canManage): ?>
                                    <input type="checkbox" name="fieldNames[]" value="<?= $field->bf_name ?>">
                                <?php endif; ?>
                            </th>
                            <td>
                                <strong><?= $field->bf_name ?></strong>
                                <div class="row-actions">
                                    <?php if ($canManage): ?>
                                        <span class="inline"><a href="javascript:void(0)" onclick="fieldsManager.edit('<?= $field->bf_name ?>')" class="editinline">Edit</a> | </span>
                                        <span class="inline"><a href="javascript:void(0)" onclick="fieldsManager.customize('<?= $field->bf_name ?>')" class="editinline">Customize</a> | </span>
                                        <span class="trash"><a href="javascript:void(0)" onclick="fieldsManager.deleteRow('<?= $field->bf_name ?>')" class="submitdelete">Trash</a></span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <?= $properties->type ?>
                            </td>
                            <?php if ($properties->type === 'select' || $properties->type === 'role_select' || $properties->type === 'hidden'): ?>
                                <td class="value_td">
                                    <?php
                                    if (is_array($properties->value)) {
                                        $properties->value = implode(',', $properties->value);
                                    }
                                    echo $properties->value;
                                    ?>
                                </td>
                            <?php else: ?>
                                <td></td>
                            <?php endif; ?>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <th class="check-column">
                            </th>
                            <td><strong><?= $properties->title ?></strong></td>
                            <td><?= $properties->name ?></td>
                            <td><?= $properties->inputType ?></td>
                            <td></td>
                        </tr>
                    <?php endif; ?>
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
                <th scope="col" id="name" class="manage-column column-name sortable <?= BaseFunctions::escape($orderName, 'attr') ?> <?= $orderByName ?>">
                    <a href="?page=<?= BaseFunctions::escape($_GET['page'], 'attr') ?>&orderby=name&order=<?= BaseFunctions::escape($orderName, 'attr') ?>"><span>Name</span><span class="sorting-indicator"></span></a>
                </th>
                <th scope="col" id="input_type" class="manage-column column-name sortable <?= BaseFunctions::escape($orderInputType, 'attr') ?> <?= $orderByInputType ?>">
                    <a href="?page=<?= BaseFunctions::escape($_GET['page'], 'attr') ?>&orderby=inputType&order=<?= BaseFunctions::escape($orderInputType, 'attr') ?>"><span>Input Type</span><span class="sorting-indicator"></span></a>
                </th>
                <th scope="col" id="value" class="manage-column column-name sortable <?= BaseFunctions::escape($orderValue, 'attr') ?> <?= $orderByValue ?>">
                    <a href="?page=<?= BaseFunctions::escape($_GET['page'], 'attr') ?>&orderby=value&order=<?= BaseFunctions::escape($orderValue, 'attr') ?>"><span>Value</span><span class="sorting-indicator"></span></a>
                </th>
            </tr>
            </tfoot>
        </table>
    </div>
    <?php
}