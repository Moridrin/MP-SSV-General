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
     * @return string the class as JSON object.
     * @throws Exception if the method is not implemented by a sub class.
     */
    public function toJSON()
    {
        throw new Exception('This should be implemented in a sub class.');
    }

    /**
     * @return string the field as HTML object.
     * @throws Exception if the method is not implemented by a sub class.
     */
    public function getHTML()
    {
        throw new Exception('This should be implemented in sub class: ' . get_class($this) . '.');
    }
}
