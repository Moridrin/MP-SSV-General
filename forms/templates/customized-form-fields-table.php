<?php

use mp_ssv_general\forms\models\Field;
use mp_ssv_general\base\BaseFunctions;

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
                <th scope="col" id="author" class="manage-column column-author">Input Type</th>
                <th scope="col" id="author" class="manage-column column-author">Default Value</th>
            </tr>
            </thead>
            <tbody id="formFieldsList">
            <?php if (!empty($fields)): ?>
                <?php /** @var Field $field */ ?>
                <?php foreach ($fields as $field): ?>
                    <tr
                            id="<?= BaseFunctions::escape(get_class($field) . '_' . $field->getId(), 'attr') ?>_tr"
                            draggable="true"
                            class="formField"
                            data-base-field-name="<?= BaseFunctions::escape($field->getName(), 'attr') ?>"
                            data-properties='<?= BaseFunctions::escape(json_encode($field->getProperties()), 'attr') ?>'
                    >
                        <td>
                            <input type="hidden" name="form_fields[]" value="<?= $field->getName() ?>">
                            <strong id="<?= $field->getId() ?>_title"><?= BaseFunctions::escape($field->getProperty('title'), 'html') ?></strong>
                            <?php if ($field->getProperty('type') !== 'hidden'): ?>
                                <span class="inline-actions"> | <a href="javascript:void(0)" onclick="fieldsCustomizer.inlineEdit('<?= BaseFunctions::escape(get_class($field), 'attr') ?>', '<?= $field->getId() ?>')" class="editinline"
                                                                   aria-label="Quick edit “<?= $field->bf_title ?>” inline">Quick Edit</a></span>
                            <?php endif; ?>
                        </td>
                        <td id="<?= $field->getId() ?>_inputType"><?= $field->getProperty('type') ?></td>
                        <?php if ($field->getProperty('type') !== 'hidden'): ?>
                            <td id="<?= $field->getId() ?>_defaultValue"><?= $field->getProperty('defaultValue') ?></td>
                        <?php else: ?>
                            <td id="<?= $field->getId() ?>_value"><?= $field->getProperty('value') ?></td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            <tr id="no-items" class="no-items" <?= !empty($fields) ? 'style="display: none;"' : '' ?>>
                <td class="colspanchange" colspan="8">There are no fields in the form yet.<br/>Drag and drop a field from the fields list to add it to the form.</td>
            </tr>
            <tr id="dropPreview">
                <td></td>
                <td></td>
                <td></td>
            </tr>
            </tbody>
            <tfoot>
            <tr id="formFieldsListBottom">
                <th scope="col" id="author" class="manage-column column-author">Title</th>
                <th scope="col" id="author" class="manage-column column-author">Input Type</th>
                <th scope="col" id="author" class="manage-column column-author">Default Value</th>
            </tr>
            </tfoot>
        </table>
    </div>
    <?php
}
