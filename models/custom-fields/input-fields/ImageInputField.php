<?php

/**
 * Created by PhpStorm.
 * User: moridrin
 * Date: 10-1-17
 * Time: 12:03
 */
class ImageInputField extends InputField
{
    const INPUT_TYPE = 'image';

    /** @var string $preview */
    public $preview;
    /** @var array $required */
    public $required;

    /**
     * ImageInputField constructor.
     *
     * @param int    $id
     * @param string $title
     * @param string $name
     * @param bool   $preview
     * @param string $required
     * @param string $class
     * @param string $style
     */
    protected function __construct($id, $title, $name, $preview, $required, $class, $style)
    {
        parent::__construct($id, $title, self::INPUT_TYPE, $name, $class, $style);
        $this->required = filter_var($required, FILTER_VALIDATE_BOOLEAN);
        $this->preview  = $preview;
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
            $values->preview,
            $values->required,
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
            'preview'    => $this->preview,
            'required'   => $this->required,
            'class'      => $this->class,
            'style'      => $this->style,
        );
        return json_encode($values);
    }

    /**
     * @return string the field as HTML object.
     */
    public function getHTML()
    {
        $name     = !empty($this->name) ? 'name="' . $this->name . '"' : '';
        $class    = !empty($this->class) ? 'class="validate ' . $this->class . '"' : 'class="validate"';
        $style    = !empty($this->style) ? 'style="' . $this->style . '"' : '';
        $required = $this->required && !empty($this->value) ? 'required' : '';

        ob_start();
        if (current_theme_supports('materialize')) {
            ?>
            <div style="padding-top: 10px;">
                <label for="<?= $this->id ?>"><?= $this->title ?><?= $this->required ? '*' : '' ?></label>
                <div class="file-field input-field">
                    <div class="btn">
                        <span>Image</span>
                        <input type="file" id="<?= $this->id ?>" <?= $name ?> <?= $class ?> <?= $style ?> <?= $required ?>>
                    </div>
                    <div class="file-path-wrapper">
                        <input class="file-path validate" type="text">
                    </div>
                </div>
            </div>
            <?php if ($this->preview): ?>
                <img src=""/>
            <?php endif; ?>
            <?php
        }

        return trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));
    }

    /**
     * @return array|bool array of errors or true if no errors.
     */
    public function isValid()
    {
        $errors = array();
        if (!empty($this->id) || !is_int($this->id)) {
            $errors[] = new Message('ID [' . $this->id . '] is invalid', Message::ERROR_MESSAGE);
        }
        if (!empty($this->title) || !is_string($this->title)) {
            $errors[] = new Message('Title [' . $this->title . '] is invalid', Message::ERROR_MESSAGE);
        }
        if (!empty($this->fieldType) || !is_string($this->fieldType) || $this->fieldType || $this->fieldType != self::FIELD_TYPE) {
            $errors[] = new Message('Field Type [' . $this->fieldType . '] is invalid.', Message::ERROR_MESSAGE);
        }
        if (!empty($this->inputType) || !is_string($this->inputType) || $this->inputType != self::INPUT_TYPE) {
            $errors[] = new Message('Input Type [' . $this->inputType . '] is invalid.', Message::ERROR_MESSAGE);
        }
        if (!empty($this->name) || !is_string($this->name)) {
            $errors[] = new Message('Name [' . $this->name . '] is invalid.', Message::ERROR_MESSAGE);
        }
        if (!empty($this->disabled) || !is_bool($this->preview)) {
            $errors[] = new Message('Preview [' . $this->preview . '] is invalid.', Message::ERROR_MESSAGE);
        }
        if (!empty($this->required) || !is_bool($this->required)) {
            $errors[] = new Message('Required [' . $this->required . '] is invalid', Message::ERROR_MESSAGE);
        }
        if (!empty($this->class) || !is_string($this->class)) {
            $errors[] = new Message('Class [' . $this->class . '] is invalid.', Message::ERROR_MESSAGE);
        }
        if (!empty($this->style) || !is_string($this->style)) {
            $errors[] = new Message('Style [' . $this->style . '] is invalid.', Message::ERROR_MESSAGE);
        }
        return empty($errors) ? true : $errors;
    }
}
