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
        parent::__construct($id, $title, $inputType, $name, $class, $style);
        $this->required     = filter_var($required, FILTER_VALIDATE_BOOLEAN);
        $this->display      = $display;
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

    /**
     * @return string the field as HTML object.
     */
    public function getHTML()
    {
        $value       = isset($this->value) ? $this->value : $this->defaultValue;
        $inputType   = 'type="' . $this->inputType . '"';
        $name        = !empty($this->name) ? 'name="' . $this->name . '"' : '';
        $class       = !empty($this->class) ? 'class="' . $this->class . '"' : '';
        $style       = !empty($this->style) ? 'style="' . $this->style . '"' : '';
        $placeholder = !empty($this->placeholder) ? 'placeholder="' . $this->placeholder . '"' : '';
        $value       = !empty($value) ? 'value="' . $value . '"' : '';
        $display     = $this->display;
        $required    = $this->required ? 'required' : '';

        ob_start();
        if (current_theme_supports('materialize')) {
            ?>
            <div class="input-field">
                <input <?= $inputType ?> id="<?= $this->id ?>" <?= $name ?> <?= $class ?> <?= $style ?> <?= $value ?> <?= $display ?> <?= $placeholder ?> <?= $required ?>/>
                <label for="<?= $this->id ?>"><?php echo $this->title; ?><?= $this->required ? '*' : '' ?></label>
            </div>
            <?php
        }

        return trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));
    }
}
