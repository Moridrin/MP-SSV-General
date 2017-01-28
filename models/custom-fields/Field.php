<?php

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
    const ID_TAG = 'custom_field_ids';
    #endregion

    #region Variables
    /** @var int $id */
    public $id;
    /** @var string $title */
    public $title;
    /** @var string $fieldType */
    public $fieldType;
    /** @var string $class */
    public $class;
    /** @var string $style */
    public $style;
    #endregion

    #region __construct($id, $title, $fieldType, $class, $style)
    /**
     * Field constructor.
     *
     * @param int    $id
     * @param string $title
     * @param string $fieldType
     * @param string $class
     * @param string $style
     */
    protected function __construct($id, $title, $fieldType, $class, $style)
    {
        $this->id        = $id;
        $this->title     = $title;
        $this->fieldType = $fieldType;
        $this->class = $class;
        $this->style = $style;
    }
    #endregion

    #region fromMeta()
    /**
     * This function gets all the Fields from the post metadata.
     *
     * @param bool $setValues
     *
*@return Field[]
     */
    public static function fromMeta($setValues = true)
    {
        global $post;
        $fieldIDs = get_post_meta($post->ID, self::ID_TAG, true);
        $fieldIDs = is_array($fieldIDs) ? $fieldIDs : array();
        $fields   = array();
        foreach ($fieldIDs as $id) {
            $field = Field::fromJSON(get_post_meta($post->ID, self::PREFIX . $id, true));
            if ($setValues && is_user_logged_in()) {
                $user = User::getCurrent();
                if (isset($_GET['member']) && $user->isBoard()) {
                    $user = User::getByID($_GET['member']);
                }
                if ($field instanceof TabField) {
                    foreach ($field->fields as $childField) {
                        if ($childField instanceof InputField) {
                            $childField->setValue($user->getMeta($childField->name));
                        }
                    }
                } elseif ($field instanceof InputField) {
                    $field->setValue($user->getMeta($field->name));
                }
            }
            $fields[] = $field;
        }
        return $fields;
    }
    #endregion

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
        throw new Exception('Unknown field type');
    }
    #endregion

    #region toJSON($encode = true)
    /**
     * This function creates an array containing all variables of this Field.

     *
*@param bool $encode can be set to false if it is important not to json_encode the array.
     *
     * @return string the class as JSON object.
     */
    abstract public function toJSON($encode = true);
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
*@return int the max ID
     */
    public static function getMaxID($fields)
    {
        $maxID = 0;
        foreach ($fields as $field) {
            $maxID = $field->id > $maxID ? $field->id : $maxID;
            if ($field instanceof TabField) {
                foreach ($field->fields as $tabField) {
                    $maxID = $tabField->id > $maxID ? $tabField->id : $maxID;
                }
            }
        }
        return $maxID;
    }
    #endregion
}
