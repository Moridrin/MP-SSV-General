<?php

use mp_ssv_general\base\BaseFunctions;
use mp_ssv_general\base\models\Model;
use mp_ssv_general\forms\SSV_Forms;

if (!defined('ABSPATH')) {
    exit;
}

function mp_ssv_show_table(?array $columns, array $items, ?string $orderBy = 'id', string $order = 'asc', bool $canManage = false)
{
    $newOrder = ($order === 'asc' ? 'desc' : 'asc');
    ?>
    <form method="post" action="#">
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
                <?php if ($columns !== null): ?>
                    <thead>
                    <tr>
                        <td id="cb" class="manage-column column-cb check-column">
                            <?php if ($canManage): ?>
                                <label class="screen-reader-text" for="cb-select-all-1">Select All</label>
                                <input id="cb-select-all-1" type="checkbox">
                            <?php endif; ?>
                        </td>
                        <?php foreach ($columns as $key => $column): ?>
                            <?php if ($orderBy !== null): ?>
                                <th scope="col" class="manage-column sortable <?= BaseFunctions::escape(($orderBy === $key ? $newOrder : $order), 'attr') ?> <?= $orderBy === $key ? 'sorted' : '' ?>">
                                    <a href="?page=<?= BaseFunctions::escape($_GET['page'], 'attr') ?>&orderby=<?= $key ?>&order=<?= BaseFunctions::escape(($orderBy === $key ? $newOrder : $order), 'attr') ?>"><span><?= BaseFunctions::escape($column, 'attr') ?></span><span class="sorting-indicator"></span></a>
                                </th>
                            <?php else: ?>
                                <th scope="col" class="manage-column">
                                    <span><?= BaseFunctions::escape($column, 'attr') ?></span>
                                </th>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tr>
                    </thead>
                <?php endif; ?>
                <tbody id="the-list">
                <?php if (!empty($items)): ?>
                    <?php /** @var Model $item */ ?>
                    <?php foreach ($items as $item): ?>
                    <?php mp_ssv_show_table_row($item, $canManage); ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr id="no-items" class="no-items">
                        <td class="colspanchange" colspan="8">No Fields found</td>
                    </tr>
                <?php endif; ?>
                </tbody>
                <?php if ($columns !== null): ?>
                    <tfoot>
                    <tr>
                        <td id="cb" class="manage-column column-cb check-column">
                            <?php if ($canManage): ?>
                                <label class="screen-reader-text" for="cb-select-all-1">Select All</label>
                                <input id="cb-select-all-1" type="checkbox">
                            <?php endif; ?>
                        </td>
                        <?php foreach ($columns as $key => $column): ?>
                            <?php if ($orderBy !== null): ?>
                                <th scope="col" class="manage-column sortable <?= BaseFunctions::escape(($orderBy === $key ? $newOrder : $order), 'attr') ?> <?= $orderBy === $key ? 'sorted' : '' ?>">
                                    <a href="?page=<?= BaseFunctions::escape($_GET['page'], 'attr') ?>&orderby=<?= $key ?>&order=<?= BaseFunctions::escape(($orderBy === $key ? $newOrder : $order), 'attr') ?>"><span><?= BaseFunctions::escape($column, 'attr') ?></span><span class="sorting-indicator"></span></a>
                                </th>
                            <?php else: ?>
                                <th scope="col" class="manage-column">
                                    <span><?= BaseFunctions::escape($column, 'attr') ?></span>
                                </th>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tr>
                    </tfoot>
                <?php endif; ?>
            </table>
        </div>
        <?php
        if ($canManage) {
            echo BaseFunctions::getAdminFormSecurityFields(SSV_Forms::ADMIN_REFERER, false, false);
        }
        ?>
    </form>
    <?php
}

function mp_ssv_show_table_row(Model $item, bool $canManage)
{
    $row = $item->getTableRow();
    $rowActions = $item->getRowActions();
    ?>
    <tr id="field_<?= $item->getId() ?>" class="inactive" data-properties='<?= json_encode($item->getProperties()) ?>'><?php // TODO Fix this ?>
        <th class="check-column">
            <?php if ($canManage): ?>
                <input type="checkbox" name="ids[]" value="<?= $item->getId() ?>">
            <?php endif; ?>
        </th>
        <?php $first = true; ?>
        <?php foreach ($row as $cell): ?>
            <td>
                <?php if ($first): ?>
                    <string><?= BaseFunctions::escape($cell, 'html') ?></string>
                    <div class="row-actions">
                        <?php
                        if ($canManage && !empty($rowActions)) {
                            $i = 0;
                            $last = count($rowActions) - 1;
                            foreach ($rowActions as $action) {
                                $isLast = ($i === $last);
                                ?><span class="<?= BaseFunctions::escape($action['spanClass'], 'attr') ?>"><a href="javascript:void(0)" onclick="<?= BaseFunctions::escape($action['onclick'], 'attr') ?>" class="<?= BaseFunctions::escape($action['linkClass'], 'attr') ?>"><?= BaseFunctions::escape($action['linkText'], 'html') ?></a><?= !$isLast ? ' | ' : '' ?></span><?php
                                ++$i;
                            }
                        }
                        ?>
                    </div>
                    <?php $first = false; ?>
                <?php else: ?>
                    <?= BaseFunctions::escape($cell, 'html', ', ') ?>
                <?php endif; ?>
            </td>
        <?php endforeach; ?>
    </tr>
    <?php
}