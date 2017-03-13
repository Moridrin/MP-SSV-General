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
     * @param bool $encode
     *
     * @return string the class as JSON object.
     */
    public function toJSON($encode = true)
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
        if ($encode) {
            $values = json_encode($values);
        }
        return $values;
    }

    /**
     * @param string|null $overrideRight string with the right needed to override required and disabled.
     *
     * @return string the field as HTML object.
     */
    public function getHTML($overrideRight = null)
    {
        $isChecked = is_bool($this->value) ? $this->value : $this->defaultChecked;
        $name      = 'name="' . $this->name . '"';
        $class     = !empty($this->class) ? 'class="' . $this->class . '"' : 'class="validate filled-in"';
        $style     = !empty($this->style) ? 'style="' . $this->style . '"' : '';
        $disabled  = $this->disabled ? 'disabled' : '';
        $required  = $this->required ? 'required' : '';
        $checked   = filter_var($isChecked, FILTER_VALIDATE_BOOLEAN) ? 'checked' : '';

        if (isset($overrideRight) && current_user_can($overrideRight)) {
            $disabled = '';
            $required = '';
        }

        ob_start();
        if (current_theme_supports('materialize')) {
            ?>
            <div <?= $style; ?>>
                <input type="hidden" id="<?= $this->id ?>_reset" <?= $name ?> value="false"/>
                <input type="checkbox" id="<?= $this->id ?>" <?= $name ?> value="true" <?= $class ?> <?= $checked ?> <?= $disabled ?>/>
                <label for="<?= $this->id ?>"><?= $this->title ?><?= $required ? '*' : '' ?></label>
            </div>
            <?php
        }

        return trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));
    }

    /**
     * @return string the filter for this field as HTML object.
     */
    public function getFilterRow()
    {
        ob_start();
        ?>
        <select id="<?= $this->id ?>" name="<?= $this->name ?>">
            <option value="false">Not Checked</option>
            <option value="true">Checked</option>
        </select>
        <?php
        return $this->getFilterRowBase(ob_get_clean());
    }

    /**
     * @return Message[]|bool array of errors or true if no errors.
     */
    public function isValid()
    {
        $errors = array();
        if (($this->required && !$this->disabled) && (empty($this->value) || !is_bool($this->value) || !$this->value)) {
            $errors[] = new Message($this->title . ' is required but not set.', User::isBoard() ? Message::SOFT_ERROR_MESSAGE : Message::ERROR_MESSAGE);
        }
        return empty($errors) ? true : $errors;
    }

    /**
     * @param string|array|User|mixed $value
     */
    public function setValue($value)
    {
        parent::setValue($value);
        $this->value = filter_var($this->value, FILTER_VALIDATE_BOOLEAN);
    }
}
