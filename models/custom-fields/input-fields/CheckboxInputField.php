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

    /** @var bool $required */
    public $required;
    /** @var string $display */
    public $display;
    /** @var bool $defaultChecked */
    public $defaultChecked;

    /**
     * CheckboxInputField constructor.
     *
     * @param int    $id
     * @param string $title
     * @param string $name
     * @param string $required
     * @param string $preview
     * @param string $defaultChecked
     * @param string $class
     * @param string $style
     */
    protected function __construct($id, $title, $name, $required, $preview, $defaultChecked, $class, $style)
    {
        parent::__construct($id, $title, self::INPUT_TYPE, $name, $class, $style);
        $this->required       = filter_var($required, FILTER_VALIDATE_BOOLEAN);
        $this->display        = $preview;
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
            $values->required,
            $values->display,
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
            'required'        => $this->required,
            'display'         => $this->display,
            'default_checked' => $this->defaultChecked ? 'true' : 'false',
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
        $display   = $this->display;
        $required  = $this->required ? 'required' : '';
        $checked   = filter_var($isChecked, FILTER_VALIDATE_BOOLEAN) ? 'checked' : '';

        ob_start();
        if (current_theme_supports('materialize')) {
            ?>
            <div>
                <input type="hidden" id="<?= $this->id ?>_reset" <?= $name ?> value="false"/>
                <p>
                    <input type="checkbox" id="<?= $this->id ?>" <?= $name ?> value="true" <?= $class ?> <?= $style; ?> <?= $checked ?> <?= $display ?>/>
                    <label for="<?= $this->id ?>"><?= $this->title ?><?= $required ? '*' : '' ?></label>
                </p>
            </div>
            <?php
        }

        return trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));
    }
}
