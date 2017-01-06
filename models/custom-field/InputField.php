<?php

/**
 * Created by PhpStorm.
 * User: moridrin
 * Date: 6-1-17
 * Time: 6:38
 */
class InputField extends Field
{
    const FIELD_TYPE = 'input-field';

    public $inputType;
    public $name;
    public $required;
    public $display;
    public $defaultValue;
    public $placeholder;
    public $class;
    public $style;

    /**
     * InputField constructor.
     *
     * @param $id
     * @param $title
     * @param $inputType
     * @param $name
     * @param $required
     * @param $display
     * @param $defaultValue
     * @param $placeholder
     * @param $class
     * @param $style
     */
    protected function __construct($id, $title, $inputType, $name, $required, $display, $defaultValue, $placeholder, $class, $style)
    {
        parent::__construct($id, $title, self::FIELD_TYPE);
        $this->inputType    = $inputType;
        $this->name         = $name;
        $this->required     = $required;
        $this->display      = $display;
        $this->defaultValue = $defaultValue;
        $this->placeholder  = $required;
        $this->class        = $class;
        $this->style        = $style;
    }

    /**
     * @param $json
     *
     * @return InputField
     * @throws Exception
     */
    public static function fromJSON($json)
    {
        $values = json_decode($json);
        if ($values->fieldType != self::FIELD_TYPE) {
            throw new Exception('Incorrect field type');
        }
        return new InputField(
            $values->id,
            $values->title,
            $values->inputType,
            $values->name,
            $values->required,
            $values->display,
            $values->defaultValue,
            $values->placeholder,
            $values->class,
            $values->style
        );
    }

    /**
     * @return string the class as JSON object.
     */
    public function toJSON()
    {
        $values = array(
            $this->id,
            $this->title,
            $this->fieldType,
            $this->inputType,
            $this->name,
            $this->required,
            $this->display,
            $this->defaultValue,
            $this->placeholder,
            $this->class,
            $this->style,
        );
        return json_encode($values);
    }
}
