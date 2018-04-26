<?php

use mp_ssv_general\base\BaseFunctions;
use mp_ssv_general\forms\models\Field;

if (!defined('ABSPATH')) {
    exit;
}

function mp_ssv_show_fields_table(array $fields, string $order = 'asc', string $orderBy = 'bf_title', bool $canManage = false)
{
    $newOrder         = ($order === 'asc' ? 'desc' : 'asc');
    $orderByName      = '';
    $orderByInputType = '';
    $orderByValue     = '';
    $orderName        = $order;
    $orderInputType   = $order;
    $orderValue       = $order;
    switch ($orderBy) {
        case 'f_properties/inputType':
            $orderByInputType = 'sorted';
            $orderInputType   = $newOrder;
            break;
        case 'f_properties/value':
            $orderByValue = 'sorted';
            $orderValue   = $newOrder;
            break;
        case 'f_name':
        default:
            $orderByName = 'sorted';
            $orderName   = $newOrder;
            break;
    }
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
            <tbody id="the-list" >
            <?php if (!empty($fields)): ?>
                <?php /** @var Field $field */ ?>
                <?php foreach ($fields as $field): ?>
                    <?php
//                \mp_ssv_general\forms\models\SharedField::deleteByIds([15]);
//                BaseFunctions::var_export($field);
                    ?>
                    <tr id="field_<?= $field->getId() ?>" class="inactive" data-properties='<?= json_encode($field->getProperties()) ?>'>
                        <th class="check-column">
                            <?php if ($canManage): ?>
                                <input type="checkbox" name="ids[]" value="<?= $field->getId() ?>">
                            <?php endif; ?>
                        </th>
                        <td>
                            <strong><?= $field->getName() ?></strong>
                            <div class="row-actions">
                                <?php if ($canManage): ?>
                                    <span class="inline"><a href="javascript:void(0)" onclick="fieldsManager.edit('<?= $field->getId() ?>')" class="editinline">Edit</a> | </span>
                                    <span class="inline"><a href="javascript:void(0)" onclick="fieldsManager.customize('<?= $field->getId() ?>')" class="editinline">Customize</a> | </span>
                                    <span class="trash"><a href="javascript:void(0)" onclick="fieldsManager.deleteRow('<?= $field->getId() ?>')" class="submitdelete">Trash</a></span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <?= $field->getProperty('type') ?>
                        </td>
                        <?php if ($field->getProperty('type') === 'select' || $field->getProperty('type') === 'role_select' || $field->getProperty('type') === 'hidden'): ?>
                            <td class="value_td">
                                <?php
                                $value = $field->getProperty('value');
                                if (is_array($value)) {
                                    $value = implode(',', $value);
                                }
                                echo $value;
                                ?>
                            </td>
                        <?php else: ?>
                            <td></td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr id="no-items" class="no-items">
                    <td class="colspanchange" colspan="8">No Fields found</td>
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
