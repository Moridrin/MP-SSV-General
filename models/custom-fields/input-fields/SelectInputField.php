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
     * @param bool $encode
     *
     * @return string the class as JSON object.
     */
    public function toJSON($encode = true)
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
        if ($encode) {
            $values = json_encode($values);
        }
        return $values;
    }

    /**
     * @return string the field as HTML object.
     */
    public function getHTML()
    {
        $name     = 'name="' . $this->name . '"';
        $class    = !empty($this->class) ? 'class="' . $this->class . '"' : 'class="validate"';
        $style    = !empty($this->style) ? 'style="' . $this->style . '"' : '';
        $disabled = $this->disabled ? 'disabled' : '';

        if (is_user_logged_in() && User::isBoard()) {
            $disabled = '';
        }

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
     * @return Message[]|bool array of errors or true if no errors.
     */
    public function isValid()
    {
        $errors = array();
        if (!$this->disabled && (empty($this->value) || !in_array($this->value, $this->options))) {
            $errors[] = new Message('The value ' . $this->value . ' is not one of the options.', Message::ERROR_MESSAGE);
        }
        return empty($errors) ? true : $errors;
    }
}
