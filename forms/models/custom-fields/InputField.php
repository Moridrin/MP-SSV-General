<?php

namespace mp_ssv_general\custom_fields;

use mp_ssv_general\base\BaseFunctions;
use mp_ssv_general\custom_fields\input_fields\CheckboxInputField;
use mp_ssv_general\custom_fields\input_fields\HiddenInputField;
use mp_ssv_general\custom_fields\input_fields\TextInputField;

if (!defined('ABSPATH')) {
    exit;
}

require_once 'input-fields/TextInputField.php';
require_once 'input-fields/CheckboxInputField.php';
//require_once 'input-fields/SelectInputField.php';
//require_once 'input-fields/ImageInputField.php';
//require_once 'input-fields/HiddenInputField.php';
//require_once 'input-fields/CustomInputField.php';
//require_once 'input-fields/DateInputField.php';
//require_once 'input-fields/RoleCheckboxInputField.php';
//require_once 'input-fields/RoleSelectInputField.php';

/**
 * Created by PhpStorm.
 * User: moridrin
 * Date: 6-1-17
 * Time: 6:38
 */
abstract class InputField extends Field
{
    const FIELD_TYPE = 'input';

    protected $inputType;
    protected $overrideRights;
    protected $value;

    protected function __construct(int $id, string $name, string $title, string $inputType, int $order = null, array $classes = [], array $styles = [], array $overrideRights = [])
    {
        parent::__construct($id, $title, self::FIELD_TYPE, $name, $order, $classes, $styles);
        $this->inputType      = $inputType;
        $this->overrideRights = $overrideRights;
    }

    public static function fromJSON(string $json): Field
    {
        $values = json_decode($json, true);
        switch ($values['inputType']) {
            case TextInputField::INPUT_TYPE:
                return new TextInputField($values);
//            case SelectInputField::INPUT_TYPE:
//                return new SelectInputField(...json_decode($json, true));
            case CheckboxInputField::INPUT_TYPE:
                return new CheckboxInputField($values);
//            case DateInputField::INPUT_TYPE:
//                return new DateInputField(...json_decode($json, true));
//            case RoleCheckboxInputField::INPUT_TYPE:
//                return new RoleCheckboxInputField(...json_decode($json, true));
//            case RoleSelectInputField::INPUT_TYPE:
//                return new RoleSelectInputField(...json_decode($json, true));
//            case ImageInputField::INPUT_TYPE:
//                return new ImageInputField(...json_decode($json, true));
//            case HiddenInputField::INPUT_TYPE:
//                return new HiddenInputField(...json_decode($json, true));
//            default:
//                return new CustomInputField(...json_decode($json, true));
        }
    }

    public abstract function getHTML(): string;

    /*
    public abstract function getFilterRow(): string;

    public function getFilterRowBase(string $filter): string
    {
        ob_start();
        ?>
        <td>
            <label for="<?= esc_html($this->order) ?>"><?= esc_html($this->title) ?></label>
        </td>
        <td>
            <label>
                Filter
                <input id="filter_<?= esc_html($this->order) ?>" type="checkbox" name="filter_<?= esc_html($this->name) ?>">
            </label>
        </td>
        <td>
            <?= $filter ?>
        </td>
        <?php
        return ob_get_clean();
    }

    public abstract function isValid();
    */

    public function getValue(): mixed
    {
        return $this->value;
    }

    /*
    public function setValue($value): void
    {
        if ($this instanceof HiddenInputField) {
            return; //Can't change the value of hidden fields.
        }
        if ($value instanceof User) { //User values can always be set (even if isDisabled())
            $this->value = $value->getMeta($this->name);
        } elseif (is_array($value)) {
            if (isset($value[$this->name])) {
                $this->value = BaseFunctions::sanitize($value[$this->name], $this->inputType);
            }
        } else {
            $this->value = BaseFunctions::sanitize($value, $this->inputType);
        }
    }

    public function isDisabled(): bool
    {
        return isset($this->disabled) ? $this->disabled : false;
    }

    public function updateName($id, $postID)
    {
        global $wpdb;
        $table = BaseFunctions::CUSTOMIZED_FIELDS_TABLE;
        $sql   = "SELECT customField FROM $table WHERE ID = $id AND postID = $postID";
        $json  = $wpdb->get_var($sql);
        if (empty($json)) {
            return;
        }
        $field = Field::fromJSON($json);
        if (!$field instanceof InputField) {
            return;
        }
        $wpdb->update(
            $wpdb->usermeta,
            array(
                'meta_key' => $this->name,
            ),
            array(
                'meta_key' => $field->name,
            ),
            array(
                '%s',
            ),
            array(
                '%s',
            )
        );
    }
    */

    function __toString(): string
    {
        return $this->name;
    }

}
