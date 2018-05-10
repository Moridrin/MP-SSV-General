<?php

use mp_general\base\BaseFunctions;
use mp_general\base\models\Model;
use mp_general\forms\SSV_Forms;

if (!defined('ABSPATH')) {
    exit;
}

function mp_ssv_show_table(string $class, string $orderBy = 'id', string $order = 'asc', bool $canManage = false)
{
    if (!is_subclass_of($class, Model::class)) {
        throw new InvalidArgumentException('The class ' . $class . ' is not a subclass of ' . Model::class);
    }
    $columns  = call_user_func([$class, 'getTableColumns']);
    $items    = call_user_func([$class, 'getAll']);
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
                <thead>
                <tr>
                    <td id="cb" class="manage-column column-cb check-column">
                        <?php if ($canManage): ?>
                            <label class="screen-reader-text" for="cb-select-all-1">Select All</label>
                            <input id="cb-select-all-1" type="checkbox">
                        <?php endif; ?>
                    </td>
                    <?php foreach ($columns as $key => $column): ?>
                        <th scope="col" class="manage-column sortable <?= BaseFunctions::escape(($orderBy === $key ? $newOrder : $order), 'attr') ?> <?= $orderBy === $key ? 'sorted' : '' ?>">
                            <a href="?page=<?= BaseFunctions::escape($_GET['page'], 'attr') ?>&orderby=<?= $key ?>&order=<?= BaseFunctions::escape(($orderBy === $key ? $newOrder : $order), 'attr') ?>">
                                <span><?= BaseFunctions::escape($column, 'attr') ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>
                    <?php endforeach; ?>
                </tr>
                </thead>
                <tbody id="the-list">
                <?php if (!empty($items)): ?>
                    <?php /** @var Model $item */ ?>
                    <?php foreach ($items as $item): ?>
                        <?php $row = $item->getTableRow(); ?>
                        <?php $rowActions = $item->getRowActions(); ?>
                        <tr id="field_<?= $item->getId() ?>" data-properties='<?= json_encode($item->getData()) ?>'>
                            <th class="check-column">
                                <?php if ($canManage): ?>
                                    <input type="checkbox" name="ids[]" value="<?= $item->getId() ?>">
                                <?php endif; ?>
                            </th>
                            <?php $first = true; ?>
                            <?php foreach ($row as $cell): ?>
                                <td>
                                    <?php if ($first): ?>
                                        <strong><?= BaseFunctions::escape($cell, 'html') ?></strong>
                                        <div class="row-actions">
                                            <?php
                                            if ($canManage && !empty($rowActions)) {
                                                $i    = 0;
                                                $last = count($rowActions) - 1;
                                                foreach ($rowActions as $action) {
                                                    $isLast = ($i === $last);
                                                    ?>
                                                    <span class="<?= BaseFunctions::escape($action['spanClass'], 'attr') ?>">
                                                        <a href="<?= BaseFunctions::escape($action['href'] ?? 'javascript:void(0)', 'attr') ?>"
                                                           onclick="<?= BaseFunctions::escape($action['onclick'] ?? '', 'attr') ?>"
                                                           class="<?= BaseFunctions::escape($action['linkClass'], 'attr') ?>"
                                                        ><?= BaseFunctions::escape($action['linkText'], 'html') ?></a><?= !$isLast ? ' | ' : '' ?>
                                                    </span>
                                                    <?php
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
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr id="no-items" class="no-items">
                        <td class="colspanchange" colspan="8">No Items found</td>
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
                    <?php foreach ($columns as $key => $column): ?>
                        <th scope="col" class="manage-column sortable <?= BaseFunctions::escape(($orderBy === $key ? $newOrder : $order), 'attr') ?> <?= $orderBy === $key ? 'sorted' : '' ?>">
                            <a href="?page=<?= BaseFunctions::escape($_GET['page'], 'attr') ?>&orderby=<?= $key ?>&order=<?= BaseFunctions::escape(($orderBy === $key ? $newOrder : $order), 'attr') ?>">
                                <span><?= BaseFunctions::escape($column, 'attr') ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>
                    <?php endforeach; ?>
                </tr>
                </tfoot>
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
