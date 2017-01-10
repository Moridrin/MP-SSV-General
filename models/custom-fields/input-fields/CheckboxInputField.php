<?php

/**
 * Created by PhpStorm.
 * User: moridrin
 * Date: 10-1-17
 * Time: 12:03
 */
class CheckboxInputField extends InputField
{
    const INPUT_TYPE = 'text';

    /** @var string $name */
    public $name;
    /** @var array $required */
    public $required;
    /** @var string $display */
    public $display;
    /** @var bool $defaultChecked */
    public $defaultChecked;
    /** @var string $class */
    public $class;
    /** @var string $style */
    public $style;

    /**
     * CheckboxInputField constructor.

     *
*@param int          $id
     * @param string $title
     * @param string $name
     * @param string $required
     * @param string $preview
     * @param string $defaultChecked
     * @param string $class
     * @param string $style
     */
    protected function __construct($id, $title, $name, $required, $preview, $defaultChecked, $class, $style)
    {
        parent::__construct($id, $title, self::INPUT_TYPE);
        $this->name           = $name;
        $this->required       = $required;
        $this->display        = $preview;
        $this->defaultChecked = filter_var($defaultChecked, FILTER_VALIDATE_BOOLEAN);
        $this->class          = $class;
        $this->style          = $style;
    }

    /**
     * @param string $json
     *
     * @return CheckboxInputField
     * @throws Exception
     */
    public static function fromJSON($json)
    {
        $values = json_decode($json);
        if ($values->input_type != self::INPUT_TYPE) {
            throw new Exception('Incorrect input type');
        }
        return new CheckboxInputField(
            $values->id,
            $values->title,
            $values->name,
            $values->required,
            $values->display,
            $values->default_checked,
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
            'id'              => $this->id,
            'title'           => $this->title,
            'field_type'      => $this->fieldType,
            'input_type'      => $this->inputType,
            'name'            => $this->name,
            'required'        => $this->required,
            'display'         => $this->display,
            'default_checked' => $this->defaultChecked ? 'true' : 'false',
            'class'           => $this->class,
            'style'           => $this->style,
        );
        return json_encode($values);
    }
}
