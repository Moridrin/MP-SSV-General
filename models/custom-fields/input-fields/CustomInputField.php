<?php

/**
 * Created by PhpStorm.
 * User: moridrin
 * Date: 10-1-17
 * Time: 12:03
 */
class CustomInputField extends InputField
{
    /** @var bool $disabled */
    public $disabled;
    /** @var array $required */
    public $required;
    /** @var string $defaultValue */
    public $defaultValue;
    /** @var string $placeholder */
    public $placeholder;

    /**
     * CustomInputField constructor.
     *
     * @param int    $id
     * @param string $title
     * @param string $inputType
     * @param string $name
     * @param bool   $disabled
     * @param string $required
     * @param string $defaultValue
     * @param string $placeholder
     * @param string $class
     * @param string $style
     */
    protected function __construct($id, $title, $inputType, $name, $disabled, $required, $defaultValue, $placeholder, $class, $style)
    {
        parent::__construct($id, $title, $inputType, $name, $class, $style);
        $this->disabled     = filter_var($disabled, FILTER_VALIDATE_BOOLEAN);
        $this->required     = filter_var($required, FILTER_VALIDATE_BOOLEAN);
        $this->defaultValue = $defaultValue;
        $this->placeholder  = $placeholder;
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
            $values->disabled,
            $values->required,
            $values->default_value,
            $values->placeholder,
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
            'id'            => $this->id,
            'title'         => $this->title,
            'field_type'    => $this->fieldType,
            'input_type'    => $this->inputType,
            'name'          => $this->name,
            'disabled'      => $this->disabled,
            'required'      => $this->required,
            'default_value' => $this->defaultValue,
            'placeholder'   => $this->placeholder,
            'class'         => $this->class,
            'style'         => $this->style,
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
        if ($this->defaultValue == 'NOW') {
            $this->defaultValue = (new DateTime('NOW'))->format('Y-m-d');
        }
        $value       = isset($this->value) ? $this->value : $this->defaultValue;
        $inputType   = 'type="' . $this->inputType . '"';
        $name = 'name="' . $this->name . '"';
        $class       = !empty($this->class) ? 'class="' . $this->class . '"' : '';
        $style       = !empty($this->style) ? 'style="' . $this->style . '"' : '';
        $placeholder = !empty($this->placeholder) ? 'placeholder="' . $this->placeholder . '"' : '';
        $value       = !empty($value) ? 'value="' . $value . '"' : '';
        $disabled    = $this->disabled ? 'disabled' : '';
        $required    = $this->required ? 'required' : '';

        if (is_user_logged_in() && User::isBoard()) {
            $disabled = '';
            $required = '';
        }

        ob_start();
        if (current_theme_supports('materialize')) {
            ?>
            <div>
                <label for="<?= $this->id ?>"><?php echo $this->title; ?><?= $this->required ? '*' : '' ?></label>
                <input <?= $inputType ?> id="<?= $this->id ?>" <?= $name ?> <?= $class ?> <?= $style ?> <?= $value ?> <?= $disabled ?> <?= $placeholder ?> <?= $required ?>/>
            </div>
            <?php
            if ($this->inputType == 'date' && $this->required) {
                ?>
                <script>
                    jQuery(function ($) {
                        var dateField = $('#<?= $this->id ?>');
                        dateField.change(function () {
                            if (dateField.val() == '') {
                                dateField.addClass('invalid')
                            } else {
                                dateField.removeClass('invalid')
                            }
                        });
                    });
                </script>
                <?php
            }
        }

        return trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));
    }

    /**
     * @return Message[]|bool array of errors or true if no errors.
     */
    public function isValid()
    {
        $errors = array();
        if (($this->required && !$this->disabled) && empty($this->value)) {
            $errors[] = new Message($this->title . ' field is required but not set.', User::isBoard() ? Message::SOFT_ERROR_MESSAGE : Message::ERROR_MESSAGE);
        }
        switch (strtolower($this->inputType)) {
            case 'iban':
                $this->value = str_replace(' ', '', strtoupper($this->value));
                if (!SSV_General::isValidIBAN($this->value)) {
                    $errors[] = new Message($this->title . ' field is not a valid IBAN.', User::isBoard() ? Message::SOFT_ERROR_MESSAGE : Message::ERROR_MESSAGE);
                }
                break;
            case 'email':
                if (!filter_var($this->value, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = new Message($this->title . ' field is not a valid email.', User::isBoard() ? Message::SOFT_ERROR_MESSAGE : Message::ERROR_MESSAGE);
                }
                break;
            case 'url':
                if (!filter_var($this->value, FILTER_VALIDATE_URL)) {
                    $errors[] = new Message($this->title . ' field is not a valid url.', User::isBoard() ? Message::SOFT_ERROR_MESSAGE : Message::ERROR_MESSAGE);
                }
                break;
        }
        return empty($errors) ? true : $errors;
    }
}
