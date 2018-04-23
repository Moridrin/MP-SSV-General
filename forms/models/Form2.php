<?php

namespace mp_ssv_general\forms\models;

use mp_ssv_general\base\BaseFunctions;
use mp_ssv_general\base\SSV_Global;
use mp_ssv_general\exceptions\NotFoundException;
use mp_ssv_general\forms\SSV_Forms;

if (!defined('ABSPATH')) {
    exit;
}

class Form2
{
    private $id;
    private $fields;

    protected function __construct($id, $fields)
    {
        $this->id     = $id;
        $this->fields = $fields;
    }

    /**
     * @param int   $formId
     * @param array $options
     *
     * @return Form|null
     * @throws NotFoundException
     */
    public static function find(int $formId, $options = []): ?Form
    {
        $options   += [
            'ignore' => false,
        ];
        $database  = SSV_Global::getDatabase();
        $tableName = SSV_Forms::SITE_SPECIFIC_FORMS_TABLE;
        $form      = $database->get_row("SELECT * FROM $tableName WHERE f_id = '$formId'");
        if (empty($form)) {
            if ($options['ignore']) {
                return null;
            } else {
                throw new NotFoundException('Form wih ID ' . $formId . ' was not found.');
            }
        }
        return new Form($formId, $form->f_fields);

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

    public function getFormFields(array $fieldNames): array
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

    public function getWordPressBaseFields(): array
    {
        return json_decode(
            json_encode(
                [
                    [
                        'bf_name'       => 'username',
                        'bf_properties' => json_encode(
                            [
                                'name'  => 'username',
                                'title' => 'Username',
                                'list'  => 'wordpress',
                                'type'  => 'text',
                                'value' => '',
                            ]
                        ),
                    ],
                    [
                        'bf_name'       => 'first_name',
                        'bf_properties' => json_encode(
                            [
                                'name'  => 'first_name',
                                'title' => 'First Name',
                                'list'  => 'wordpress',
                                'type'  => 'text',
                                'value' => '',
                            ]
                        ),
                    ],
                    [
                        'bf_name'       => 'last_name',
                        'bf_properties' => json_encode(
                            [
                                'name'  => 'last_name',
                                'title' => 'Last Name',
                                'list'  => 'wordpress',
                                'type'  => 'text',
                                'value' => '',
                            ]
                        ),
                    ],
                    [
                        'bf_name'       => 'email',
                        'bf_properties' => json_encode(
                            [
                                'name'  => 'email',
                                'title' => 'Email',
                                'list'  => 'wordpress',
                                'type'  => 'text',
                                'value' => '',
                            ]
                        ),
                    ],
                    [
                        'bf_name'       => 'password',
                        'bf_properties' => json_encode(
                            [
                                'name'  => 'password',
                                'title' => 'Password',
                                'list'  => 'wordpress',
                                'type'  => 'text',
                                'value' => '',
                            ]
                        ),
                    ],
                    [
                        'bf_name'       => 'password_confirm',
                        'bf_properties' => json_encode(
                            [
                                'name'  => 'password_confirm',
                                'title' => 'Confirm Password',
                                'list'  => 'wordpress',
                                'type'  => 'text',
                                'value' => '',
                            ]
                        ),
                    ],
                ]
            )
        );
    }

    public function getDecorationBaseFields(): array
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

    public function __toString(): string
    {
        $formFields = self::getFormFields(json_decode($this->fields));
        ob_start();
        foreach ($formFields as $field) {
            $field           = json_decode(json_encode($field), true);
            $field['formId'] = $this->id;
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
}
