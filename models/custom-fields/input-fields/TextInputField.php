<?php

/**
 * Created by PhpStorm.
 * User: moridrin
 * Date: 10-1-17
 * Time: 12:03
 */
class TextInputField extends InputField
{
    const INPUT_TYPE = 'text';

    /** @var array $required */
    public $required;
    /** @var string $display */
    public $display;
    /** @var string $defaultValue */
    public $defaultValue;
    /** @var string $placeholder */
    public $placeholder;
    /** @var string $class */
    public $class;
    /** @var string $style */
    public $style;

    /**
     * TextInputField constructor.
     *
     * @param int    $id
     * @param string $title
     * @param string $name
     * @param string $required
     * @param string $preview
     * @param string $defaultChecked
     * @param string $placeholder
     * @param string $class
     * @param string $style
     */
    protected function __construct($id, $title, $name, $required, $preview, $defaultChecked, $placeholder, $class, $style)
    {
        parent::__construct($id, $title, self::INPUT_TYPE);
        $this->name         = $name;
        $this->required     = $required;
        $this->display      = $preview;
        $this->defaultValue = $defaultChecked;
        $this->placeholder  = $placeholder;
        $this->class        = $class;
        $this->style        = $style;
    }

    /**
     * @param string $json
     *
     * @return TextInputField
     * @throws Exception
     */
    public static function fromJSON($json)
    {
        $values = json_decode($json);
        if ($values->input_type != self::INPUT_TYPE) {
            throw new Exception('Incorrect input type');
        }
        return new TextInputField(
            $values->id,
            $values->title,
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
        $value       = $this->defaultValue;
        $id          = !empty($this->id) ? 'id="' . $this->id . '"' : '';
        $name        = !empty($this->name) ? 'name="' . $this->name . '"' : '';
        $class       = !empty($this->class) ? 'class="validate ' . $this->class . '"' : 'class="validate"';
        $style       = !empty($this->style) ? 'style="' . $this->style . '"' : '';
        $placeholder = !empty($this->placeholder) ? 'placeholder="' . $this->placeholder . '"' : '';
        $value       = !empty($value) ? 'value="' . $value . '"' : '';
        $display     = $this->display;
        $required    = $this->required == "true" ? 'required' : '';

        ob_start();
        if (current_theme_supports('materialize')) {
            ?>
            <div class="input-field">
                <input type="text" <?= $id ?> <?= $name ?> <?= $class ?> <?= $style ?> <?= $value ?> <?= $display ?> <?= $placeholder ?> <?= $required ?> title="<?= $this->title ?>"/>
                <label><?php echo $this->title; ?><?= $this->required == "yes" ? '*' : "" ?></label>
            </div>
            <?php
        }

        return trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));
    }
}
