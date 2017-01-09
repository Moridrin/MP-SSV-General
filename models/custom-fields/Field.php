<?php

require_once 'InputField.php';

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

    /**
     * Field constructor.
     *
     * @param $id
     * @param $title
     * @param $fieldType
     */
    protected function __construct($id, $title, $fieldType)
    {
        $this->id        = $id;
        $this->title     = $title;
        $this->fieldType = $fieldType;
    }

    /**
     * @param $json
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
}
