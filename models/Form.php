<?php

namespace mp_ssv_general;

use mp_ssv_general\custom_fields\Field;
use mp_ssv_general\custom_fields\HeaderField;
use mp_ssv_general\custom_fields\input_fields\ImageInputField;
use mp_ssv_general\custom_fields\input_fields\RoleCheckboxInputField;
use mp_ssv_general\custom_fields\input_fields\RoleSelectInputField;
use mp_ssv_general\custom_fields\InputField;
use mp_ssv_general\custom_fields\LabelField;
use mp_ssv_general\custom_fields\TabField;
use WP_Post;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Created by PhpStorm.
 * User: moridrin
 * Date: 29-1-17
 * Time: 15:42
 */
class Form
{
    #region variables
    /** @var Field[] $fields */
    public $fields;
    /** @var array|User $values */
    public $values;
    /** @var array $values */
    public $errors;
    /** @var User $values */
    public $user;
    /** @var int $containerID */
    public $containerID = 0;
    #endregion

    #region Construct($fields)
    /**
     * Form constructor.
     *
     * @param Field[] $fields
     */
    public function __construct($fields = array())
    {
        $this->fields = $fields;
        if (isset($_GET['member']) && current_user_can('edit_users')) {
            $this->user = User::getByID($_GET['member']);
        }
        if (!$this->user) {
            $this->user = User::getCurrent();
        }
    }
    #endregion

    #region fromDatabase()
    /**
     * This function gets all the Fields from the post metadata.
     *
     * @param bool         $setValues
     * @param WP_Post|null $post
     *
     * @return Form|Message
     */
    public static function fromDatabase($setValues = true, $post = null)
    {
        $form = new Form();

        /** @var \wpdb $wpdb */
        global $wpdb;

        if ($post == null) {
            global $post;
        }
        if (!$post) {
            return $form;
        }
        $customizationTable = SSV_General::CUSTOM_FORM_FIELDS_TABLE;
        $postID             = $post->ID;
        $customizedFields   = $wpdb->get_results("SELECT * FROM $customizationTable WHERE postID = $postID ORDER BY `order` ASC");
        foreach ($customizedFields as $customizedField) {
            $field = Field::fromDatabase($customizedField);
            if ($setValues) {
                if ($field instanceof TabField) {
                    foreach ($field->fields as $childField) {
                        if ($childField instanceof InputField) {
                            $childField->setValue($form->user->getMeta($childField->name));
                        }
                    }
                } elseif ($field instanceof InputField) {
                    $field->setValue($form->user->getMeta($field->name));
                }
            }
            $form->fields[] = $field;
        }
        return $form;
    }
    #endregion

    #region addFields($fields)
    /**
     * @param Field[]|Field $fields
     * @param bool          $atEnd if set to false, this will append the fields at the start of the array.
     */
    public function addFields($fields, $atEnd = true)
    {
        if ($fields instanceof Field) {
            $fields = array($fields);
        } elseif (!is_array($fields)) {
            return;
        }
        if ($atEnd) {
            $this->fields = array_merge($this->fields, $fields);
        } else {
            $this->fields = array_merge($fields, $this->fields);
        }
    }
    #endregion

    #region setValues($values)
    /**
     * @param null|array $values if set to null it uses form->user variable.
     */
    public function setValues($values = null)
    {
        if ($values == null) {
            $values = $this->user ?: array();
        }
        $this->values = $values;
        $this->loopRecursive(
            function ($field) {
                if ($field instanceof InputField) {
                    $field->setValue($this->values);
                }
            }
        );
    }
    #endregion

    #region getEditor($allowTabs)
    /**
     * @param bool $allowTabs if set true it will display the select option for tab in the Field Type
     *
     * @return string HTML
     */
    public function getEditor($allowTabs)
    {
        /** @var \wpdb $wpdb */
        global $wpdb;
        $table      = SSV_General::CUSTOM_FIELDS_TABLE;
        $baseFields = $wpdb->get_results("SELECT * FROM $table");
        $baseFields = array_combine(array_column($baseFields, 'name'), $baseFields);
        ob_start();
        echo SSV_General::getCapabilitiesDataList();
        $columns = json_decode(User::getCurrent()->getMeta(SSV_General::USER_OPTION_CUSTOM_FIELD_FIELDS, array()));
        ?>
        <div style="overflow-x: auto;">
            <table id="custom-fields-placeholder" class="sortable">
                <tr>
                    <th style="text-align: left;"></th>
                    <th style="text-align: left;"></th>
                    <th style="text-align: left;">Field</th>
                    <th style="text-align: left;">Title (override)</th>
                    <th style="text-align: left;">Default</th>
                    <?php if (in_array("disabled", $columns)): ?>
                        <th style="text-align: left;">Disabled</th>
                    <?php endif; ?>
                    <?php if (in_array("required", $columns)): ?>
                        <th style="text-align: left;">Required</th>
                    <?php endif; ?>
                    <?php if (in_array("placeholder", $columns)): ?>
                        <th style="text-align: left;">Placeholder</th>
                    <?php endif; ?>
                    <?php if (in_array("override_right", $columns)): ?>
                        <th style="text-align: left;">Override Right</th>
                    <?php endif; ?>
                    <?php if (in_array("class", $columns)): ?>
                        <th style="text-align: left;">Class</th>
                    <?php endif; ?>
                    <?php if (in_array("style", $columns)): ?>
                        <th style="text-align: left;">Style</th>
                    <?php endif; ?>
                </tr>
            </table>
            <br/>
            <?php if ($allowTabs): ?>
                <button type="button" onclick="mp_ssv_add_new_custom_tab_field_customizer('tab')">Add Tab</button>
                <br/>
                <br/>
            <?php endif; ?>
            <button type="button" onclick="mp_ssv_add_new_custom_header_field_customizer('header')">Add Header</button>
            <br/>
            <br/>
            <button type="button" onclick="mp_ssv_add_new_custom_label_field_customizer('label')">Add Label</button>
            <br/>
            <br/>
            <label>
                Field Name (or title)
                <input type="text" id="custom_field_selector" list="custom_fields"/>
            </label>
            <datalist id="custom_fields">
                <?php foreach ($baseFields as $field): ?>
                    <option value="<?= $field->name ?>"><?= $field->title ?></option>
                <?php endforeach; ?>
            </datalist>
            <button type="button" onclick="mp_ssv_add_new_custom_input_field_customizer()">Add Field</button>
        </div>
        <script>
            var baseFields = <?= json_encode($baseFields) ?>;
            var i = <?= esc_html(Field::getMaxID($this->fields) + 1) ?>;
            mp_ssv_sortable_table('custom-fields-placeholder');
            function mp_ssv_add_new_custom_input_field_customizer() {
                var baseFieldName = document.getElementById('custom_field_selector').value;
                if (!baseFieldName) {
                    document.getElementById("custom_field_selector").setAttribute("placeholder", "fill in a valid Field ID");
                } else {
                    document.getElementById("custom_field_selector").setAttribute("placeholder", "");
                    var field = JSON.parse(baseFields[baseFieldName]['json']);
                    mp_ssv_add_custom_input_field_customizer('custom-fields-placeholder', i, field['input_type'], field);
                }
                i++;
                document.getElementById('custom_field_selector').value = '';
            }
            function mp_ssv_add_new_custom_tab_field_customizer() {
                mp_ssv_add_custom_tab_field_customizer('custom-fields-placeholder', i);
                i++;
            }
            function mp_ssv_add_new_custom_header_field_customizer() {
                mp_ssv_add_custom_header_field_customizer('custom-fields-placeholder', i);
                i++;
            }
            function mp_ssv_add_new_custom_label_field_customizer() {
                mp_ssv_add_custom_label_field_customizer('custom-fields-placeholder', i);
                i++;
            }
            <?php
            foreach ($this->fields as $field) {
                if ($field instanceof TabField) {
                    echo 'mp_ssv_add_custom_tab_field_customizer(\'custom-fields-placeholder\', ' . $field->order . ', ' . $field->toJSON() . ');';
                } elseif ($field instanceof HeaderField) {
                    echo 'mp_ssv_add_custom_header_field_customizer(\'custom-fields-placeholder\', ' . $field->order . ', ' . $field->toJSON() . ');';
                } elseif ($field instanceof LabelField) {
                    echo 'mp_ssv_add_custom_label_field_customizer(\'custom-fields-placeholder\', ' . $field->order . ', ' . $field->toJSON() . ');';
                } else {
                    echo 'mp_ssv_add_custom_input_field_customizer(\'custom-fields-placeholder\', ' . $field->order . ', "' . $field->inputType . '", ' . $field->toJSON() . ');';
                }
            }
            ?>
        </script>
        <?php
        return ob_get_clean();
    }
    #endregion

    #region saveEditorFromPost()
    /**
     * This function removes the old fields from the database and inserts the new fields.
     */
    public static function saveEditorFromPost()
    {
        /** @var \wpdb $wpdb */
        global $wpdb;
        global $post;
        if (!$post) {
            return;
        }
        $form       = new Form();
        $table      = SSV_General::CUSTOM_FIELDS_TABLE;
        $baseFields = $wpdb->get_results("SELECT * FROM $table");
        $baseFields = array_combine(array_column($baseFields, 'name'), $baseFields);

        $order = 0;
        /** @var TabField $currentTab */
        $currentTab = null;
        foreach ($_POST['field_ids'] as $fieldID) {
            $properties = array_filter(
                $_POST,
                function ($key) use ($fieldID) {
                    return mp_ssv_starts_with($key, 'custom_field_' . $fieldID . '_');
                },
                ARRAY_FILTER_USE_KEY
            );
            foreach ($properties as $key => $property) {
                if (mp_ssv_starts_with($key, 'custom_field_' . $fieldID . '_')) {
                    $properties[str_replace('custom_field_' . $fieldID . '_', '', $key)] = $property;
                    unset($properties[$key]);
                }
            }
            $properties['order']       = $order;
            $properties['containerID'] = $form->containerID;
            $order++;
            switch ($properties['name']) {
                case '[tab]':
                    $properties['field_type'] = TabField::FIELD_TYPE;
                    break;
                case '[header]':
                    $properties['field_type'] = HeaderField::FIELD_TYPE;
                    break;
                case '[label]':
                    $properties['field_type'] = LabelField::FIELD_TYPE;
                    break;
                default:
                    $baseField  = $baseFields[strval($properties['name'])];
                    $properties = array_merge(json_decode($baseField->json, true), $properties);
                    break;
            }
            $field = Field::fromJSON(json_encode($properties));
            if ($field instanceof TabField) {
                $currentTab             = $field;
                $form->fields[$fieldID] = $field;
            } elseif ($currentTab != null) {
                $currentTab->addField($fieldID, $field);
            } else {
                $form->fields[$fieldID] = $field;
            }
        }
        //Remove All old fields for post
        $wpdb->delete(
            SSV_General::CUSTOM_FORM_FIELDS_TABLE,
            array(
                'postID' => $post->ID,
            )
        );
        $fields = $form->fields; //All Fields
        foreach ($form->fields as $field) {
            if ($field instanceof TabField) {
                $fields = $fields + $field->fields;
            }
        }
        foreach ($fields as $field) {
            $wpdb->insert(
                SSV_General::CUSTOM_FORM_FIELDS_TABLE,
                array(
                    'postID'      => $post->ID,
                    'containerID' => $form->containerID,
                    'order'       => $field->order,
                    'name'        => $field instanceof InputField ? $field->name : null,
                    'json'        => $field->toJSON(true),
                )
            );
        }
    }
    #endregion

    #region getHTML($adminReferer, $buttonText = 'save')
    /**
     * @param string $adminReferrer is the admin referer for the form.
     * @param string $buttonText    is the text on the submit button (default = 'save').
     *
     * @return string the field as HTML object.
     */
    public function getHTML($adminReferrer, $buttonText = 'save')
    {
        $tabs = array();
        $html = '';
        /** @var Field $field */
        foreach ($this->fields as $field) {
            if ($field instanceof TabField) {
                $tabs[] = $field;
            } elseif (empty($tabs)) {
                $html .= $field->getHTML();
            }
        }
        if (empty($tabs)) {
            ob_start();
            ?>
            <form action="#" method="POST" enctype="multipart/form-data">
                <?= $html ?>
                <button type="submit" name="submit" class="btn waves-effect waves-light btn waves-effect waves-light--primary"><?= esc_html($buttonText) ?></button>
                <?= SSV_General::getFormSecurityFields($adminReferrer, false, false) ?>
            </form>
            <?php
            $html = ob_get_clean();
        } else {
            $tabsHTML        = '<ul class="tabs">';
            $tabsContentHTML = '';
            /** @var TabField $tab */
            foreach ($tabs as $tab) {
                $tabsHTML .= $tab->getHTML();
                ob_start();
                ?>
                <div id="<?= esc_html($tab->name) ?>">
                    <form action="#" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="tab" value="<?= esc_html($tab->order) ?>">
                        <?php foreach ($tab->fields as $childField): ?>
                            <?= $childField->getHTML() ?>
                        <?php endforeach; ?>
                        <button type="submit" name="submit" class="btn waves-effect waves-light btn waves-effect waves-light--primary"><?= esc_html($buttonText) ?></button>
                        <?= SSV_General::getFormSecurityFields($adminReferrer, false, false); ?>
                    </form>
                </div>
                <?php
                $tabsContentHTML .= ob_get_clean();
            }
            $tabsHTML .= '</ul>';
            $html     .= $tabsHTML . $tabsContentHTML;
        }
        return $html;
    }

    #endregion

    #region getInputFields()
    /**
     * @return InputField[]
     */
    public function getInputFields()
    {
        return $this->loopRecursive(
            function ($field) {
                if ($field instanceof InputField) {
                    return $field;
                } else {
                    return null;
                }
            }
        );
    }
    #endregion

    #region isValid($tabId)
    /**
     * @param int|null $tabID if set it will only check the fields inside that tab.
     *
     * @return array|bool array of errors or true if no errors.
     */
    public function isValid($tabID = null)
    {
        $this->errors = array();
        $this->loopRecursive(
            function ($field) {
                if ($field instanceof InputField) {
                    $errors = $field->isValid();
                    if ($errors !== true) {
                        $this->errors = $this->errors + $errors;
                    }
                }
            },
            $tabID
        );
        return empty($this->errors) ? true : $this->errors;
    }
    #endregion

    #region save($tabId)
    /**
     * This function saves all the field values to the user meta.
     * This function does not validate fields.
     *
     * @param int|null $tabID if set it will only save the fields inside that tab.
     *
     * @return Message[]
     */
    public function save($tabID = null)
    {
        //Fields
        $messages = $this->loopRecursive(
            function ($field) {
                if ($field instanceof ImageInputField) {
                    //Do Nothing
                } elseif ($field instanceof InputField) {
                    if ($field->name == 'password' || $field->name == 'password_confirm') {
                        return true;
                    }
                    if (!$field->isDisabled() || current_user_can($field->overrideRight)) {
                        if ($field instanceof RoleCheckboxInputField || $field instanceof RoleSelectInputField) {
                            $field->saveValue($this->user);
                        }
                        if (is_bool($field->value)) {
                            $field->value = $field->value ? 'true' : 'false';
                        }
                        return $this->user->updateMeta($field->name, $field->value);
                    }
                }
                return true;
            },
            $tabID
        );

        //Files
        foreach ($_FILES as $name => $file) {
            if ($file['size'] == 0) {
                continue;
            }
            if (!function_exists('wp_handle_upload')) {
                require_once(ABSPATH . 'wp-admin/includes/file.php');
            }
            $overrides     = array('test_form' => false, 'mimes' => array('jpg' => 'image/jpg', 'jpeg' => 'image/jpeg', 'gif' => 'image/gif', 'png' => 'image/png'));
            $file_location = wp_handle_upload($file, $overrides);
            if ($file_location && !isset($file_location['error'])) {
                $currentURL      = $this->user->getMeta($name);
                $currentLocation = $this->user->getMeta($name . '_path');
                if ($currentURL != '' && mp_ssv_starts_with($currentURL, SSV_General::BASE_URL) && file_exists($currentLocation)) {
                    unlink($currentLocation);
                }
                $this->user->updateMeta($name, $file_location["url"]);
                $this->user->updateMeta($name . '_path', $file_location["file"]);
            } else {
                $messages[] = new Message($file_location['error'], current_user_can($file->overrideRight) ? Message::SOFT_ERROR_MESSAGE : Message::ERROR_MESSAGE);
            }
        }

        $messages = array_diff($messages, array(true));
        return $messages;
    }
    #endregion

    #region getValue($name)
    /**
     * @param string $name of the field to return the value
     *
     * @return string|null
     */
    public function getValue($name)
    {
        $values = $this->loopRecursive(
            function ($field, $args) {
                if ($field instanceof InputField) {
                    if ($field->name == $args['field_name']) {
                        return $field->value;
                    }
                }
                return null;
            },
            null,
            array('field_name' => $name)
        );
        return count($values) ? reset($values) : null;
    }
    #endregion

    #region getInputFieldProperty($_property)
    /**
     * @param string $_property
     *
     * @return string[] array of all properties for the fields that have that property
     */
    public function getFieldProperty($_property)
    {
        global $property;
        $property   = $_property;
        $properties = $this->loopRecursive(
            function ($field) {
                global $property;
                if (isset($field->$property)) {
                    return $field->$property;
                }
                return null;
            }
        );
        return $properties;
    }

    #endregion

    #region getEmail($_hidePasswordFields)
    /**
     * @param bool $_hidePasswordFields if true, the passwords will be replaced with ******.
     *
     * @return string email body in HTML.
     */
    public function getEmail($_hidePasswordFields = true)
    {
        global $hidePasswordFields;
        $hidePasswordFields = $_hidePasswordFields;
        $rows               = $this->loopRecursive(
            function ($field) {
                if ($field instanceof TabField) {
                    return '<tr><td colspan="2"><h1>' . esc_html($field->title) . '</h1></td></tr>';
                } elseif ($field instanceof HeaderField) {
                    return '<tr><td colspan="2"><h3>' . esc_html($field->title) . '</h3></td></tr>';
                } elseif ($field instanceof InputField) {
                    if ($field->name == 'password_confirm') {
                        return null;
                    }
                    global $hidePasswordFields;
                    if ($hidePasswordFields && $field->name == 'password') {
                        return '<tr><td>' . esc_html($field->title) . '</td><td>********</td></tr>';
                    } else {
                        return '<tr><td>' . esc_html($field->title) . '</td><td>' . esc_html($field->value) . '</td></tr>';
                    }
                } elseif ($field instanceof LabelField) {
                    return '<tr><td>' . esc_html($field->text) . '</td></tr>';
                }
                return null;
            }
        );
        return '<table>' . implode('', $rows) . '</table>';
    }
    #endregion

    #region loopRecursive($callback)
    /**
     * This function runs the callable for all fields (including all the sub-fields in tabs).
     *
     * @param callable $callback The function to be called with the field as parameter.
     * @param int|null $tabID    if set it will only run the callback on the fields inside that tab.
     * @param array    $args
     *
     * @return array
     */
    public function loopRecursive($callback, $tabID = null, $args = array())
    {
        $return = array();
        /** @var Field $field */
        foreach ($this->fields as $field) {
            if (isset($tabID) && $field->order != $tabID) {
                continue;
            }
            if ($field instanceof TabField) {
                foreach ($field->fields as $childField) {
                    $return[] = $callback($childField, $args);
                }
            } else {
                $return[] = $callback($field, $args);
            }
        }
        $return = array_diff($return, array(null));
        return $return;
    }
    #endregion
}
