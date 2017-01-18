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

    /** @var array $required */
    public $required;
    /** @var string $preview */
    public $preview;

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

    /**
     * @return string the field as HTML object.
     */
    public function getHTML()
    {
        $name     = !empty($this->name) ? 'name="' . $this->name . '"' : '';
        $class    = !empty($this->class) ? 'class="validate ' . $this->class . '"' : 'class="validate"';
        $style    = !empty($this->style) ? 'style="' . $this->style . '"' : '';
        $preview  = $this->preview;
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
            <?php
        }

        return trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));
    }
}
