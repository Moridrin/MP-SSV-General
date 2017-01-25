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
    const PREFIX = 'custom_field_';
    const ID_TAG = 'custom_field_ids';

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

    /**
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

    /**
     * @param bool $encode
     *
     * @return string the class as JSON object.
     */
    abstract public function toJSON($encode = true);

    /**
     * @return string the field as HTML object.
     */
    abstract public function getHTML();

    /**
     * @param $fields
     *
     * @return string the field as HTML object.
     */
    public static function getFormFromFields($fields)
    {
        /** @var Field $field */
        $tabs    = array();
        $content = '';
        foreach ($fields as $field) {
            if ($field instanceof TabField) {
                $tabs[] = $field;
            } else {
                $content .= $field->getHTML();
            }
        }
        if (!empty($tabs)) {
            $tabsHTML        = '<ul class="tabs">';
            $tabsContentHTML = '';
            /** @var TabField $tab */
            foreach ($tabs as $tab) {
                $tabsHTML .= $tab->getHTML();
                $tabsContentHTML .= $tab->getFieldsHTML();
            }
            $tabsHTML .= '</ul>';
            $content .= $tabsHTML . $tabsContentHTML;
        }

        return $content;
    }

    /**
     * @param Field[] $fields
     *
     * @return int
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

    #region getFromMeta()
    /**
     * @return Field[]
     */
    public static function getFromMeta()
    {
        global $post;
        $fieldIDs = get_post_meta($post->ID, self::ID_TAG, true);
        $fieldIDs = is_array($fieldIDs) ? $fieldIDs : array();
        $fields   = array();
        foreach ($fieldIDs as $id) {
            $field    = get_post_meta($post->ID, self::PREFIX . $id, true);
            $fields[] = Field::fromJSON($field);
        }
        return $fields;
    }
    #endregion
}
