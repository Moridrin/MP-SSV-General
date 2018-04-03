<?php

namespace mp_ssv_general\forms\models;

use mp_ssv_general\base\BaseFunctions;
use mp_ssv_general\base\SSV_Global;
use mp_ssv_general\forms\SSV_Forms;

if (!defined('ABSPATH')) {
    exit;
}

/** @noinspection PhpIncludeInspection */
require_once SSV_Forms::PATH . 'templates/base-form-fields-table.php';
/** @noinspection PhpIncludeInspection */
require_once SSV_Forms::PATH . 'templates/forms-table.php';
/** @noinspection PhpIncludeInspection */
require_once SSV_Forms::PATH . 'templates/form-editor.php';

abstract class Forms
{
    public static function filterContent($content)
    {
        $database = SSV_Global::getDatabase();
        $table    = SSV_Forms::SITE_SPECIFIC_FORMS_TABLE;
        $forms    = $database->get_results("SELECT * FROM $table");
        foreach ($forms as $form) {
            if (strpos($content, $form->f_tag) !== false) {
                $content = str_replace($form->f_tag, self::getFormFieldsHTML($form->f_id), $content);
            }
        }
        return $content;
    }

    public static function getFormFieldsHTML(int $formId): string
    {
        $database   = SSV_Global::getDatabase();
        $tableName  = SSV_Forms::SITE_SPECIFIC_FORMS_TABLE;
        $form       = $database->get_row("SELECT * FROM $tableName WHERE f_id = '$formId'");
        $formFields = self::getFormFields(json_decode($form->f_fields));
        ob_start();
        foreach ($formFields as $field) {
            $field           = json_decode(json_encode($field), true);
            $field['formId'] = $formId;
            $newField        = [];
            BaseFunctions::var_export($field);
            foreach ($field as $key => $value) {
                $newField[str_replace('bf_', '', $key)] = $value;
            }
            switch ($newField['inputType']) {
                case 'hidden':
                    /** @noinspection PhpIncludeInspection */
                    require_once SSV_Forms::PATH . 'templates/fields/hidden.php';
                    show_hidden_input_field($newField);
                    break;
                case 'select':
                    /** @noinspection PhpIncludeInspection */
                    require_once SSV_Forms::PATH . 'templates/fields/select.php';
                    show_select_input_field($form->f_id, $newField);
                    break;
                case 'checkbox':
                    /** @noinspection PhpIncludeInspection */
                    require_once SSV_Forms::PATH . 'templates/fields/checkbox.php';
                    show_checkbox_input_field($newField);
                    break;
                case 'datetime':
                    /** @noinspection PhpIncludeInspection */
                    require_once SSV_Forms::PATH . 'templates/fields/datetime.php';
                    show_datetime_input_field($form->f_id, $newField);
                    break;
                default:
                    /** @noinspection PhpIncludeInspection */
                    require_once SSV_Forms::PATH . 'templates/fields/input.php';
                    show_default_input_field($form->f_id, $newField);
                    break;
            }
        }
        return ob_get_clean();
    }

    public static function getFormFields(array $fieldNames): array
    {
        $wordPressBaseFields          = self::getWordPressBaseFields();
        $wordPressFormFields          = array_filter(
            $wordPressBaseFields,
            function ($field) use ($fieldNames) {
                return in_array($field->bf_name, $fieldNames);
            }
        );
        $decorationBaseFields         = self::getDecorationBaseFields();
        $decorationFormFields         = array_filter(
            $decorationBaseFields,
            function ($field) use ($fieldNames) {
                return in_array($field->bf_name, $fieldNames);
            }
        );
        $database                     = SSV_Global::getDatabase();
        $fieldNames                   = '"' . implode('", "', $fieldNames) . '"';
        $sharedBaseFieldsTable        = SSV_Forms::SHARED_BASE_FIELDS_TABLE;
        $siteSpecificBaseFieldsTable  = SSV_Forms::SITE_SPECIFIC_BASE_FIELDS_TABLE;
        $sharedFieldieldsSelect       = "SELECT *, 'shared' AS bf_list, 'input' AS bf_type FROM $sharedBaseFieldsTable";
        $siteSpecificBaseFieldsSelect = "SELECT *, 'siteSpecific' AS bf_list, 'input' AS bf_type FROM $siteSpecificBaseFieldsTable)";
        $select                       = "SELECT * FROM ($sharedFieldieldsSelect UNION $siteSpecificBaseFieldsSelect combined WHERE bf_name IN ($fieldNames) ORDER BY FIELD(`bf_name`,$fieldNames)";
        $databaseFields               = $database->get_results($select);
        return array_merge($wordPressFormFields, $decorationFormFields, $databaseFields);
    }

    public static function getWordPressBaseFields(): array
    {
        return json_decode(
            json_encode(
                [
                    [
                        'bf_id'        => '0',
                        'bf_name'      => 'username',
                        'bf_title'     => 'Username',
                        'bf_list'      => 'wordpress',
                        'bf_type'      => 'input',
                        'bf_inputType' => 'text',
                        'bf_value'     => null,
                        'bf_options'   => null,
                    ],
                    [
                        'bf_id'        => '1',
                        'bf_name'      => 'first_name',
                        'bf_title'     => 'First Name',
                        'bf_list'      => 'wordpress',
                        'bf_type'      => 'input',
                        'bf_inputType' => 'text',
                        'bf_value'     => null,
                        'bf_options'   => null,
                    ],
                    [
                        'bf_id'        => '2',
                        'bf_name'      => 'last_name',
                        'bf_title'     => 'Last Name',
                        'bf_list'      => 'wordpress',
                        'bf_type'      => 'input',
                        'bf_inputType' => 'text',
                        'bf_value'     => null,
                        'bf_options'   => null,
                    ],
                    [
                        'bf_id'        => '3',
                        'bf_name'      => 'email',
                        'bf_title'     => 'Email',
                        'bf_list'      => 'wordpress',
                        'bf_type'      => 'input',
                        'bf_inputType' => 'email',
                        'bf_value'     => null,
                        'bf_options'   => null,
                    ],
                    [
                        'bf_id'        => '4',
                        'bf_name'      => 'password',
                        'bf_title'     => 'Password',
                        'bf_list'      => 'wordpress',
                        'bf_type'      => 'input',
                        'bf_inputType' => 'password',
                        'bf_value'     => null,
                        'bf_options'   => null,
                    ],
                    [
                        'bf_id'        => '5',
                        'bf_name'      => 'password_confirm',
                        'bf_title'     => 'Confirm Password',
                        'bf_list'      => 'wordpress',
                        'bf_type'      => 'input',
                        'bf_inputType' => 'password',
                        'bf_value'     => null,
                        'bf_options'   => null,
                    ],
                ]
            )
        );
    }

    public static function getDecorationBaseFields(): array
    {
        return json_decode(
            json_encode(
                [
                    [
                        'bf_id'        => '0',
                        'bf_name'      => null,
                        'bf_title'     => 'Label',
                        'bf_list'      => 'decoration',
                        'bf_type'      => 'label',
                        'bf_inputType' => null,
                        'bf_value'     => null,
                        'bf_options'   => null,
                    ],
                    [
                        'bf_id'        => '1',
                        'bf_name'      => null,
                        'bf_title'     => 'Header',
                        'bf_list'      => 'decoration',
                        'bf_type'      => 'header',
                        'bf_inputType' => null,
                        'bf_value'     => null,
                        'bf_options'   => null,
                    ],
                    [
                        'bf_id'        => '2',
                        'bf_name'      => null,
                        'bf_title'     => 'Break',
                        'bf_list'      => 'decoration',
                        'bf_type'      => 'break',
                        'bf_inputType' => null,
                        'bf_value'     => null,
                        'bf_options'   => null,
                    ],
                ]
            )
        );
    }
}

add_filter('the_content', [Forms::class, 'filterContent']);
