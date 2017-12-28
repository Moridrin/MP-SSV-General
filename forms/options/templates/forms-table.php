<?php

use mp_ssv_general\base\BaseFunctions;

if (!defined('ABSPATH')) {
    exit;
}

function show_forms_table(array $forms, string $order = 'asc', string $orderBy = 'bf_title', bool $canManage = false)
{
    $newOrder     = $order === 'asc' ? 'desc' : 'asc';
    $orderByTitle = ($orderBy === 'bf_title' ? 'sorted' : '');
    $orderByTag   = ($orderBy === 'bf_tag' ? 'sorted' : '');
    $orderTitle   = ($orderBy === 'bf_title' ? $newOrder : $order);
    $orderTag     = ($orderBy === 'bf_tag' ? $newOrder : $order);
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
                <th scope="col" id="name" class="manage-column column-name sortable <?= BaseFunctions::escape($orderTag, 'attr') ?> <?= $orderByTag ?>">
                    <a href="?page=<?= BaseFunctions::escape($_GET['page'], 'attr') ?>&orderby=bf_tag&order=<?= BaseFunctions::escape($orderTag, 'attr') ?>"><span>Name</span><span class="sorting-indicator"></span></a>
                </th>
                <td scope="col" id="input_type" class="manage-column column-name">
                    <span>Fields</span>
                </td>
            </tr>
            </thead>
            <tbody id="the-list">
            <?php if (!empty($forms)): ?>
                <?php foreach ($forms as $form): ?>
                    <tr id="<?= $form->bf_id ?>_tr" class="inactive">
                        <th id="<?= $form->bf_id ?>_id_td" class="check-column">
                            <?php if ($canManage): ?>
                                <input type="checkbox" id="<?= $form->bf_id ?>_id" name="fieldIds[]" value="<?= $form->bf_id ?>">
                            <?php endif; ?>
                        </th>
                        <td id="<?= $form->bf_id ?>_field_title_td">
                            <strong><?= $form->bf_title ?></strong>
                            <div class="row-actions">
                                <?php if ($canManage): ?>
                                    <span class="inline hide-if-no-js"><a href="javascript:void(0)" onclick="fieldsManager.inlineEdit('<?= $form->bf_id ?>')" class="editinline"
                                                                          aria-label="Quick edit “Hello world!” inline">Quick&nbsp;Edit</a> | </span>
                                    <span class="trash"><a href="javascript:void(0)" onclick="fieldsManager.deleteRow('<?= $form->bf_id ?>')" class="submitdelete" aria-label="Move “Hello world!” to the Trash">Trash</a></span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td id="<?= $form->bf_id ?>_name_td">
                            <?= $form->bf_tag ?>
                        </td>
                        <td id="<?= $form->bf_id ?>_inputType_td">
                            <?= $form->bf_fields ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr id="no-items" class="no-items">
                    <td class="colspanchange" colspan="4">No Base Fields found</td>
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
                <th scope="col" id="name" class="manage-column column-name sortable <?= BaseFunctions::escape($orderTag, 'attr') ?> <?= $orderByTag ?>">
                    <a href="?page=<?= BaseFunctions::escape($_GET['page'], 'attr') ?>&orderby=bf_tag&order=<?= BaseFunctions::escape($orderTag, 'attr') ?>"><span>Name</span><span class="sorting-indicator"></span></a>
                </th>
                <td scope="col" id="input_type" class="manage-column column-name">
                    <span>Fields</span>
                </td>
            </tr>
            </tfoot>
        </table>
    </div>
    <?php
}