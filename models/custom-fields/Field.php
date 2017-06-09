<?php

namespace mp_ssv_general\custom_fields;

use Exception;
use mp_ssv_general\SSV_General;

if (!defined('ABSPATH')) {
    exit;
}

require_once 'TabField.php';
require_once 'HeaderField.php';
require_once 'InputField.php';
require_once 'LabelField.php';

/**
 * Created by PhpStorm.
 * User: moridrin
 * Date: 5-1-17
 * Time: 20:25
 */
abstract class Field
{
    #region Constants
    const PREFIX = 'custom_field_';
    const CUSTOM_FIELD_IDS_META = 'custom_field_ids';
    #endregion

    #region Variables
    /** @var int $containerID */
    public $containerID = 0;
    /** @var int $order */
    public $order;
    /** @var string $title */
    public $title;
    /** @var string $fieldType */
    public $fieldType;
    /** @var string $class */
    public $class;
    /** @var string $style */
    public $style;
    #endregion

    #region __construct($id, $title, $fieldType, $class, $style, $overrideRight)
    /**
     * Field constructor.
     *
     * @param int    $order
     * @param string $title
     * @param string $fieldType
     * @param string $class
     * @param string $style
     */
    protected function __construct($containerID, $order, $title, $fieldType, $class, $style)
    {
        $this->containerID = $containerID ? $containerID : 0;
        $this->order       = $order;
        $this->title       = $title;
        $this->fieldType   = $fieldType;
        $this->class       = $class;
        $this->style       = $style;
    }
    #endregion

    /**
     * @param string $name
     *
     * @return string with the title for that field or empty if no match is found.
     */
    public static function titleFromDatabase($name)
    {
        /** @var \wpdb $wpdb */
        global $wpdb;
        $table  = SSV_General::CUSTOM_FORM_FIELDS_TABLE;
        $sql    = "SELECT customField FROM $table WHERE customField LIKE '%\"name\":\"$name\"%'";
        $fields = $wpdb->get_results($sql);
        foreach ($fields as $field) {
            $field = self::fromJSON($field->customField);
            if ($field instanceof TabField) {
                foreach ($field->fields as $childField) {
                    if ($childField instanceof InputField) {
                        if ($childField->name == $name) {
                            return $childField->title;
                        }
                    }
                }
            } elseif ($field instanceof InputField && $field->name == $name) {
                return $field->title;
            }
        }
        return '';
    }

    #region fromJSON($json)

    /**
     * This function extracts a Field from the JSON string.
     *
     * @param string $json
     *
     * @return Field
     * @throws Exception if the field type is unknown.
     */
    public static function fromJSON($json)
    {
        $values = json_decode($json);
        switch ($values->field_type) {
            case InputField::FIELD_TYPE:
                return InputField::fromJSON($json);
            case TabField::FIELD_TYPE:
                return TabField::fromJSON($json);
            case HeaderField::FIELD_TYPE:
                return HeaderField::fromJSON($json);
            case LabelField::FIELD_TYPE:
                return LabelField::fromJSON($json);
        }
        throw new Exception($values->field_type . ' is an unknown field type');
    }
    #endregion

    #region fromDatabaseRow($row)
    /**
     * This function extracts a Field from the JSON string.
     *
     * @param  $row
     *
     * @return Field
     */
    public static function fromDatabaseRow($row)
    {
        $values        = json_decode($row->json);
        $values->order = $row->order;
        $values->name  = $row->name;
        $values->title = $row->title;

        return Field::fromJSON(json_encode($values));
    }
    #endregion

    #region getByID($fieldID)
    /**
     * @param int $order
     *
     * @return Field
     */
    public static function getByOrder($containerID = 0, $order)
    {
        global $post;
        if ($post != null) {
            $postID = $post->ID;
        }
        if (!isset($postID)) {
            return null;
        }

        /** @var \wpdb $wpdb */
        global $wpdb;
        $table = SSV_General::CUSTOM_FORM_FIELDS_TABLE;
        $field = $wpdb->get_var("SELECT json FROM $table WHERE postID = $postID AND `containerID` = $containerID AND `order` = $order");
        if (!empty($field)) {
            return self::fromJSON($field);
        } else {
            return null;
        }
    }
    #endregion

    #region toJSON($encode = true)
    /**
     * This function creates an array containing all variables of this Field.
     *
     * @return string the class as JSON object.
     */
    abstract public function toJSON();
    #endregion

    #region getHTML()
    /**
     * This function returns a string with the Field as HTML (to be used in the frontend).
     *
     * @return string the field as HTML object.
     */
    abstract public function getHTML();
    #endregion

    #region getMaxID($fields)
    /**
     * This function returns the highest ID in all the fields (including all sub-fields)
     *
     * @param Field[] $fields
     *
     * @return int the max ID
     */
    public static function getMaxID($fields)
    {
        $maxID = 0;
        foreach ($fields as $field) {
            $maxID = $field->order > $maxID ? $field->order : $maxID;
            if ($field instanceof TabField) {
                foreach ($field->fields as $tabField) {
                    $maxID = $tabField->order > $maxID ? $tabField->order : $maxID;
                }
            }
        }
        return $maxID;
    }
    #endregion

    #region __compare($field)
    /**
     * @param Field $a
     * @param Field $b
     *
     * @return int -1 / 0 / 1
     */
    public static function compare($a, $b)
    {
        return $a->__compare($b);
    }
    #endregion

    #region __compare($field)
    /**
     * @param Field $field
     *
     * @return int -1 / 0 / 1
     */
    public function __compare($field)
    {
        if ($this->order == $field->order) {
            return 0;
        }
        return ($this->order < $field->order) ? -1 : 1;
    }
    #endregion

    #region __toString()
    /**
     * @return string HTML code for the field
     */
    public function __toString()
    {
        return $this->getHTML();
    }
    #endregion
}
