<?php

/**
 * Created by PhpStorm.
 * User: moridrin
 * Date: 10-1-17
 * Time: 12:03
 */
class SelectInputField extends InputField
{
    const INPUT_TYPE = 'select';

    /** @var array $options */
    public $options;
    /** @var string $class */
    public $class;
    /** @var string $style */
    public $style;

    /**
     * SelectInputField constructor.
     *
     * @param int    $id
     * @param string $title
     * @param string $name
     * @param string $options
     * @param string $class
     * @param string $style
     */
    protected function __construct($id, $title, $name, $options, $class, $style)
    {
        parent::__construct($id, $title, self::INPUT_TYPE);
        $this->name    = $name;
        $this->options = explode(',', $options);
        $this->class   = $class;
        $this->style   = $style;
    }

    /**
     * @param string $json
     *
     * @return SelectInputField
     * @throws Exception
     */
    public static function fromJSON($json)
    {
        $values = json_decode($json);
        return new SelectInputField(
            $values->id,
            $values->title,
            $values->name,
            $values->options,
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
            'id'         => $this->id,
            'title'      => $this->title,
            'field_type' => $this->fieldType,
            'input_type' => $this->inputType,
            'name'       => $this->name,
            'options'    => implode(',', $this->options),
            'class'      => $this->class,
            'style'      => $this->style,
        );
        return json_encode($values);
    }
}
