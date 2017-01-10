<?php

/**
 * Created by PhpStorm.
 * User: moridrin
 * Date: 10-1-17
 * Time: 12:03
 */
class ImageInputField extends InputField
{
    const INPUT_TYPE = 'text';

    /** @var string $name */
    public $name;
    /** @var array $required */
    public $required;
    /** @var string $preview */
    public $preview;
    /** @var string $class */
    public $class;
    /** @var string $style */
    public $style;

    /**
     * ImageInputField constructor.
     *
     * @param int    $id
     * @param string $title
     * @param string $name
     * @param string $required
     * @param string $preview
     * @param string $class
     * @param string $style
     */
    protected function __construct($id, $title, $name, $required, $preview, $class, $style)
    {
        parent::__construct($id, $title, self::INPUT_TYPE);
        $this->name     = $name;
        $this->required = $required;
        $this->preview  = $preview;
        $this->class    = $class;
        $this->style    = $style;
    }

    /**
     * @param string $json
     *
     * @return ImageInputField
     * @throws Exception
     */
    public static function fromJSON($json)
    {
        $values = json_decode($json);
        if ($values->input_type != self::INPUT_TYPE) {
            throw new Exception('Incorrect input type');
        }
        return new ImageInputField(
            $values->id,
            $values->title,
            $values->name,
            $values->required,
            $values->preview,
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
            'required'   => $this->required,
            'preview'    => $this->preview,
            'class'      => $this->class,
            'style'      => $this->style,
        );
        return json_encode($values);
    }
}
