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

    /** @var bool $disabled */
    public $disabled;
    /** @var array $options */
    public $options;

    /**
     * SelectInputField constructor.
     *
     * @param int    $id
     * @param string $title
     * @param string $name
     * @param bool   $disabled
     * @param string $options
     * @param string $class
     * @param string $style
     */
    protected function __construct($id, $title, $name, $disabled, $options, $class, $style)
    {
        parent::__construct($id, $title, self::INPUT_TYPE, $name, $class, $style);
        $this->disabled = filter_var($disabled, FILTER_VALIDATE_BOOLEAN);
        $this->options  = explode(',', $options);
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
            $values->disabled,
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
            'disabled'   => $this->disabled,
            'options'    => implode(',', $this->options),
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
        $disabled = $this->disabled ? 'disabled' : '';

        ob_start();
        if (current_theme_supports('materialize')) {
            ?>
            <div class="input-field">
                <select id="<?= $this->id ?>" <?= $name ?> <?= $class ?> <?= $style ?> <?= $disabled ?>>
                    <?php foreach ($this->options as $option): ?>
                        <option value="<?= $option ?>" <?= $this->value == $option ? 'selected' : '' ?>><?= $option ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="<?= $this->id ?>"><?= $this->title ?></label>
            </div>
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
        if (!empty($this->disabled) || !is_bool($this->disabled)) {
            $errors[] = new Message('Disabled [' . $this->disabled . '] is invalid.', Message::ERROR_MESSAGE);
        }
        if (!empty($this->required) || !is_array($this->options)) {
            $errors[] = new Message('Options [' . $this->options . '] are invalid', Message::ERROR_MESSAGE);
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
