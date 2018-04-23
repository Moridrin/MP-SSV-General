<?php

use mp_ssv_general\base\SSV_Global;
use mp_ssv_general\forms\SSV_Forms;

if (!defined('ABSPATH')) {
    exit;
}

function show_customized_form_fields_table(int $formId, array $fields)
{
    $database = SSV_Global::getDatabase();
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
                <?php foreach ($fields as $field): ?>
                    <?php
                    $table           = SSV_Forms::CUSTOMIZED_FIELDS_TABLE;
                    $name            = $field->bf_name;
                    $properties      = [
                        'title'        => $field->bf_title,
                        'classes'      => ['div' => '', 'title' => '', 'input' => ''],
                        'styles'       => ['div' => '', 'title' => '', 'input' => ''],
                        'defaultValue' => '',
                        'required'     => false,
                        'placeholder'  => '',
                        'autocomplete' => true,
                        'list'         => '',
                        'pattern'      => '',
                        'step'         => null,
                        'min'          => null,
                        'max'          => null,
                        'profileField' => true,
                    ];
                    $customizedField = $database->get_var("SELECT cf_json FROM $table WHERE cf_bf_id = $formId AND cf_bf_name = '$name'");
                    if ($customizedField !== null) {
                        $customizedField = json_decode($customizedField, true);
                        foreach ($customizedField as $key => $value) {
                            $properties[$key] = $value;
                        }
                    }
                    ?>
                    <tr
                            id="<?= $field->bf_list . '_' . $field->bf_id ?>_tr"
                            draggable="true"
                            class="formField"
                            data-base-field-name="<?= $field->bf_name ?>"
                            data-list="<?= $field->bf_list ?>"
                            data-type="<?= $field->bf_type ?>"
                            data-input-type="<?= $field->bf_inputType ?>"
                            data-options='<?= $field->bf_options ?>'
                            data-properties='<?= json_encode($properties) ?>'
                    >
                        <td>
                            <input type="hidden" name="form_fields[]" value="<?= $field->bf_name ?>">
                            <strong id="<?= $field->bf_id ?>_title"><?= $properties['title'] ?></strong>
                            <?php if ($field->bf_inputType !== 'hidden'): ?>
                                <span class="inline-actions"> | <a href="javascript:void(0)" onclick="fieldsCustomizer.inlineEdit('<?= $field->bf_list ?>', '<?= $field->bf_id ?>')" class="editinline"
                                                                   aria-label="Quick edit “<?= $field->bf_title ?>” inline">Quick Edit</a></span>
                            <?php endif; ?>
                        </td>
                        <td id="<?= $field->bf_id ?>_inputType"><?= $field->bf_inputType ?></td>
                        <?php if ($field->bf_inputType !== 'hidden'): ?>
                            <td id="<?= $field->bf_id ?>_defaultValue"><?= $properties['defaultValue'] ?></td>
                        <?php else: ?>
                            <td id="<?= $field->bf_id ?>_value"><?= $field->bf_value ?></td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr id="no-items" class="no-items">
                    <td class="colspanchange" colspan="8">There are no fields in the form yet.<br/>Drag and drop a field from the fields list to add it to the form.</td>
                </tr>
            <?php endif; ?>
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
