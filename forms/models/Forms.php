<?php

namespace mp_ssv_general\forms\models;

use mp_ssv_general\base\BaseFunctions;
use mp_ssv_general\base\SSV_Global;
use mp_ssv_general\forms\SSV_Forms;
use stdClass;
use wpdb;

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
        /** @var wpdb $wpdb */
        global $wpdb;
        $table = SSV_Forms::SITE_SPECIFIC_FORMS_TABLE;
        $forms = $wpdb->get_results("SELECT * FROM $table");
        foreach ($forms as $form) {
            if (strpos($content, $form->f_tag) !== false) {
                $content = str_replace($form->f_tag, self::getFormFieldsHTML($form), $content);
            }
        }
        return $content;
    }

    public static function getFormFieldsHTML(stdClass $form): string
    {
        $formFields = self::getFormFields(json_decode($form->f_fields));
        ob_start();
        foreach ($formFields as $field) {
            $field    = json_decode(json_encode($field), true);
            $newField = [];
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
                    show_checkbox_input_field($form->f_id, $newField);
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
        $wordPressBaseFields = self::getWordPressBaseFields();
        $formFields          = array_filter(
            $wordPressBaseFields,
            function ($field) use ($fieldNames) {
                return in_array($field->bf_name, $fieldNames);
            }
        );
        /** @var wpdb $wpdb */
        global $wpdb;
        $fieldNames                  = '"' . implode('", "', $fieldNames) . '"';
        $sharedBaseFieldsTable       = SSV_Forms::SHARED_BASE_FIELDS_TABLE;
        $siteSpecificBaseFieldsTable = SSV_Forms::SITE_SPECIFIC_BASE_FIELDS_TABLE;
        $databaseFields = $wpdb->get_results("SELECT * FROM (SELECT * FROM $sharedBaseFieldsTable UNION SELECT * FROM $siteSpecificBaseFieldsTable) combined WHERE bf_name IN ($fieldNames) ORDER BY FIELD(`bf_name`,$fieldNames)");
        return array_merge($formFields, $databaseFields);
    }

    public static function getWordPressBaseFields(): array
    {
        return json_decode(
            json_encode(
                [
                    [
                        'bf_id'        => 'b_0',
                        'bf_name'      => 'username',
                        'bf_title'     => 'Username',
                        'bf_inputType' => 'text',
                        'bf_value'     => null,
                        'bf_options'   => null,
                    ],
                    [
                        'bf_id'        => 'b_1',
                        'bf_name'      => 'first_name',
                        'bf_title'     => 'First Name',
                        'bf_inputType' => 'text',
                        'bf_value'     => null,
                        'bf_options'   => null,
                    ],
                    [
                        'bf_id'        => 'b_2',
                        'bf_name'      => 'last_name',
                        'bf_title'     => 'Last Name',
                        'bf_inputType' => 'text',
                        'bf_value'     => null,
                        'bf_options'   => null,
                    ],
                    [
                        'bf_id'        => 'b_3',
                        'bf_name'      => 'email',
                        'bf_title'     => 'Email',
                        'bf_inputType' => 'email',
                        'bf_value'     => null,
                        'bf_options'   => null,
                    ],
                    [
                        'bf_id'        => 'b_4',
                        'bf_name'      => 'password',
                        'bf_title'     => 'Password',
                        'bf_inputType' => 'password',
                        'bf_value'     => null,
                        'bf_options'   => null,
                    ],
                    [
                        'bf_id'        => 'b_5',
                        'bf_name'      => 'password_confirm',
                        'bf_title'     => 'Confirm Password',
                        'bf_inputType' => 'password',
                        'bf_value'     => null,
                        'bf_options'   => null,
                    ],
                ]
            )
        );
    }
}

add_filter('the_content', [Forms::class, 'filterContent']);
