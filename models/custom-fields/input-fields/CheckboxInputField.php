<?php

/**
 * Created by PhpStorm.
 * User: moridrin
 * Date: 10-1-17
 * Time: 12:03
 */
class CheckboxInputField extends InputField
{
    const INPUT_TYPE = 'checkbox';

    /** @var bool $disabled */
    public $disabled;
    /** @var bool $required */
    public $required;
    /** @var bool $defaultChecked */
    public $defaultChecked;

    /**
     * CheckboxInputField constructor.
     *
     * @param int    $id
     * @param string $title
     * @param string $name
     * @param bool   $disabled
     * @param string $required
     * @param string $defaultChecked
     * @param string $class
     * @param string $style
     */
    protected function __construct($id, $title, $name, $disabled, $required, $defaultChecked, $class, $style)
    {
        parent::__construct($id, $title, self::INPUT_TYPE, $name, $class, $style);
        $this->disabled       = filter_var($disabled, FILTER_VALIDATE_BOOLEAN);
        $this->required       = filter_var($required, FILTER_VALIDATE_BOOLEAN);
        $this->defaultChecked = filter_var($defaultChecked, FILTER_VALIDATE_BOOLEAN);
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
            $values->disabled,
            $values->required,
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
            'disabled'        => $this->disabled,
            'required'        => $this->required,
            'default_checked' => $this->defaultChecked,
            'class'           => $this->class,
            'style'           => $this->style,
        );
        return json_encode($values);
    }

    /**
     * @return string the field as HTML object.
     */
    public function getHTML()
    {
        $isChecked = isset($this->value) ? $this->value : $this->defaultChecked;
        $name      = !empty($this->name) ? 'name="' . $this->name . '"' : '';
        $class     = !empty($this->class) ? 'class="validate ' . $this->class . '"' : 'class="validate filled-in"';
        $style     = !empty($this->style) ? 'style="' . $this->style . '"' : '';
        $disabled  = $this->disabled ? 'disabled' : '';
        $required  = $this->required ? 'required' : '';
        $checked   = filter_var($isChecked, FILTER_VALIDATE_BOOLEAN) ? 'checked' : '';

        ob_start();
        if (current_theme_supports('materialize')) {
            ?>
            <div>
                <input type="hidden" id="<?= $this->id ?>_reset" <?= $name ?> value="false"/>
                <p>
                    <input type="checkbox" id="<?= $this->id ?>" <?= $name ?> value="true" <?= $class ?> <?= $style; ?> <?= $checked ?> <?= $disabled ?>/>
                    <label for="<?= $this->id ?>"><?= $this->title ?><?= $required ? '*' : '' ?></label>
                </p>
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
        if ($this->required && empty($this->value)) {
            $errors[] = new Message('This field is required but not set.', Message::ERROR_MESSAGE);
        }
        return empty($errors) ? true : $errors;
    }
}
