<?php

/**
 * Created by PhpStorm.
 * User: moridrin
 * Date: 10-1-17
 * Time: 12:03
 */
class CustomInputField extends InputField
{
    /** @var array $required */
    public $required;
    /** @var string $display */
    public $display;
    /** @var string $defaultValue */
    public $defaultValue;
    /** @var string $placeholder */
    public $placeholder;
    /** @var string $class */
    public $class;
    /** @var string $style */
    public $style;

    /**
     * CustomInputField constructor.
     *
     * @param int    $id
     * @param string $title
     * @param string $inputType
     * @param string $name
     * @param string $required
     * @param string $display
     * @param string $defaultValue
     * @param string $placeholder
     * @param string $class
     * @param string $style
     */
    protected function __construct($id, $title, $inputType, $name, $required, $display, $defaultValue, $placeholder, $class, $style)
    {
        parent::__construct($id, $title, $inputType);
        $this->name         = $name;
        $this->required     = $required;
        $this->display      = $display;
        $this->defaultValue = $defaultValue;
        $this->placeholder  = $placeholder;
        $this->class        = $class;
        $this->style        = $style;
    }

    /**
     * @param string $json
     *
     * @return CustomInputField
     * @throws Exception
     */
    public static function fromJSON($json)
    {
        $values = json_decode($json);
        return new CustomInputField(
            $values->id,
            $values->title,
            $values->input_type,
            $values->name,
            $values->required,
            $values->display,
            $values->default_value,
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
            'id'            => $this->id,
            'title'         => $this->title,
            'field_type'    => $this->fieldType,
            'input_type'    => $this->inputType,
            'name'          => $this->name,
            'required'      => $this->required,
            'display'       => $this->display,
            'default_value' => $this->defaultValue,
            'placeholder'   => $this->placeholder,
            'class'         => $this->class,
            'style'         => $this->style,
        );
        return json_encode($values);
    }
}
