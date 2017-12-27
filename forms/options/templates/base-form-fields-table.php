<?php

use mp_ssv_forms\models\SSV_Forms;
use mp_ssv_general\base\BaseFunctions;

if (!defined('ABSPATH')) {
    exit;
}

/** @var wpdb $wpdb */
global $wpdb;
$order = BaseFunctions::sanitize(isset($_GET['order']) ? $_GET['order'] : 'asc', 'text');
$orderBy = BaseFunctions::sanitize(isset($_GET['orderby']) ? $_GET['orderby'] : 'bf_title', 'text');
$baseTable = SSV_Forms::SHARED_BASE_FIELDS_TABLE;
$baseFields = $wpdb->get_results("SELECT * FROM $baseTable ORDER BY $orderBy $order");
$order = $order === 'asc' ? 'desc' : 'asc';
echo BaseFunctions::getInputTypeDataList(['Role Checkbox', 'Role Select']);
?>
<h1 class="wp-heading-inline">Shared Form Fields</h1>
<a href="javascript:void(0)" class="page-title-action" onclick="mp_ssv_add_new_base_input_field()">Add New</a>
<p>These fields will be available for all sites.</p>
<?php if (!current_user_can('add_custom_fields')): ?>
    <div class="notice">
        <p>You are not allowed to add custom fields.</p>
    </div>
<?php endif; ?>
<?php if (!current_user_can('edit_custom_fields')): ?>
    <div class="notice">
        <p>You are not allowed to edit existing custom fields.</p>
    </div>
<?php endif; ?>
<?php if (!current_user_can('remove_custom_fields')): ?>
    <div class="notice">
        <p>You are not allowed to remove custom fields.</p>
    </div>
<?php endif; ?>
<form method="post" action="#">
    <div style="overflow-x: auto;">
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
        <table class="wp-list-table widefat striped">
            <thead>
            <tr>
                <td id="cb" class="manage-column column-cb check-column">
                    <label class="screen-reader-text" for="cb-select-all-1">Select All</label>
                    <input id="cb-select-all-1" type="checkbox">
                </td>
                <th scope="col" id="title" class="manage-column column-name column-primary sortable <?= BaseFunctions::escape($order, 'attr') ?> <?= $orderBy === 'bf_title' ? 'sorted' : '' ?>">
                    <a href="?page=<?= BaseFunctions::escape($_GET['page'], 'attr') ?>&orderby=bf_title&order=<?= BaseFunctions::escape($order, 'attr') ?>"><span>Title</span><span class="sorting-indicator"></span></a>
                </th>
                <th scope="col" id="name" class="manage-column column-name sortable <?= BaseFunctions::escape($order, 'attr') ?> <?= $orderBy === 'bf_name' ? 'sorted' : '' ?>">
                    <a href="?page=<?= BaseFunctions::escape($_GET['page'], 'attr') ?>&orderby=bf_name&order=<?= BaseFunctions::escape($order, 'attr') ?>"><span>Name</span><span class="sorting-indicator"></span></a>
                </th>
                <th scope="col" id="input_type" class="manage-column column-name sortable <?= BaseFunctions::escape($order, 'attr') ?> <?= $orderBy === 'bf_inputType' ? 'sorted' : '' ?>">
                    <a href="?page=<?= BaseFunctions::escape($_GET['page'], 'attr') ?>&orderby=bf_inputType&order=<?= BaseFunctions::escape($order, 'attr') ?>"><span>Input Type</span><span class="sorting-indicator"></span></a>
                </th>
                <th scope="col" id="value" class="manage-column column-name sortable <?= BaseFunctions::escape($order, 'attr') ?> <?= $orderBy === 'bf_value' ? 'sorted' : '' ?>">
                    <a href="?page=<?= BaseFunctions::escape($_GET['page'], 'attr') ?>&orderby=bf_value&order=<?= BaseFunctions::escape($order, 'attr') ?>"><span>Value</span><span class="sorting-indicator"></span></a>
                </th>
            </tr>
            </thead>
            <tbody id="the-list">
            <?php if(!empty($baseFields)): ?>
                <?php foreach($baseFields as $baseField): ?>
                    <tr id="<?= $baseField->bf_id ?>_tr" class="inactive">
                        <th id="<?= $baseField->bf_id ?>_id_td" class="check-column">
                            <input type="checkbox" id="<?= $baseField->bf_id ?>_id" name="fieldIds[]" value="<?= $baseField->bf_id ?>">
                        </th>
                        <td id="<?= $baseField->bf_id ?>_field_title_td">
                            <strong><?= $baseField->bf_title ?></strong>
                            <div class="row-actions">
                                <span class="inline hide-if-no-js"><a href="javascript:void(0)" onclick="fieldsManager.inlineEdit('<?= $baseField->bf_id ?>')" class="editinline" aria-label="Quick edit “Hello world!” inline">Quick&nbsp;Edit</a> | </span>
                                <span class="trash"><a href="javascript:void(0)" onclick="fieldsManager.deleteRow('<?= $baseField->bf_id ?>')" class="submitdelete" aria-label="Move “Hello world!” to the Trash">Trash</a></span>
                            </div>
                        </td>
                        <td id="<?= $baseField->bf_id ?>_name_td">
                            <?= $baseField->bf_name ?>
                        </td>
                        <td id="<?= $baseField->bf_id ?>_inputType_td">
                            <?= $baseField->bf_inputType ?>
                        </td>
                        <?php if ($baseField->bf_inputType === 'select'): ?>
                            <td class="value_td" id="<?= $baseField->bf_id ?>_value_td">
                                <?= $baseField->bf_value ?>
                            </td>
                        <?php elseif ($baseField->bf_inputType === 'hidden'): ?>
                            <td class="_value_td" id="<?= $baseField->bf_id ?>_value_td">
                                <?= $baseField->bf_value ?>
                            </td>
                        <?php else: ?>
                            <td id="<?= $baseField->bf_id ?>_empty_td"></td>
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
                    <label class="screen-reader-text" for="cb-select-all-1">Select All</label>
                    <input id="cb-select-all-1" type="checkbox">
                </td>
                <th scope="col" id="title" class="manage-column column-name column-primary sortable <?= BaseFunctions::escape($order, 'attr') ?> <?= $orderBy === 'bf_title' ? 'sorted' : '' ?>">
                    <a href="?page=<?= BaseFunctions::escape($_GET['page'], 'attr') ?>&orderby=bf_title&order=<?= BaseFunctions::escape($order, 'attr') ?>"><span>Title</span><span class="sorting-indicator"></span></a>
                </th>
                <th scope="col" id="name" class="manage-column column-name sortable <?= BaseFunctions::escape($order, 'attr') ?> <?= $orderBy === 'bf_name' ? 'sorted' : '' ?>">
                    <a href="?page=<?= BaseFunctions::escape($_GET['page'], 'attr') ?>&orderby=bf_name&order=<?= BaseFunctions::escape($order, 'attr') ?>"><span>Name</span><span class="sorting-indicator"></span></a>
                </th>
                <th scope="col" id="input_type" class="manage-column column-name sortable <?= BaseFunctions::escape($order, 'attr') ?> <?= $orderBy === 'bf_inputType' ? 'sorted' : '' ?>">
                    <a href="?page=<?= BaseFunctions::escape($_GET['page'], 'attr') ?>&orderby=bf_inputType&order=<?= BaseFunctions::escape($order, 'attr') ?>"><span>Input Type</span><span class="sorting-indicator"></span></a>
                </th>
                <th scope="col" id="value" class="manage-column column-name sortable <?= BaseFunctions::escape($order, 'attr') ?> <?= $orderBy === 'bf_value' ? 'sorted' : '' ?>">
                    <a href="?page=<?= BaseFunctions::escape($_GET['page'], 'attr') ?>&orderby=bf_value&order=<?= BaseFunctions::escape($order, 'attr') ?>"><span>Value</span><span class="sorting-indicator"></span></a>
                </th>
            </tr>
            </tfoot>
        </table>
        <table id="shared-base-fields-placeholder" class="form-table"></table>
        <button type="button" onclick="mp_ssv_add_new_base_input_field()" style="margin-top: 10px;">Add Field</button>
    </div>
    <script>
        let i = <?= count($baseFields) > 0 ? max(array_column($baseFields, 'bf_id')) + 1 : 1 ?>;
        function mp_ssv_add_new_base_input_field() {
            event.preventDefault();
            mp_ssv_add_base_input_field('the-list', i, '', '', '');
            document.getElementById(i + '_title').focus();
            i++;
        }
    </script>
    <?= BaseFunctions::getFormSecurityFields(SSV_Forms::OPTIONS_ADMIN_REFERER, false, false) ?>
</form>
