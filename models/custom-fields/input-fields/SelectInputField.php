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

    /** @var array $options */
    public $options;

    /**
     * SelectInputField constructor.
     *
     * @param int    $id
     * @param string $title
     * @param string $name
     * @param string $options
     * @param string $class
     * @param string $style
     */
    protected function __construct($id, $title, $name, $options, $class, $style)
    {
        parent::__construct($id, $title, self::INPUT_TYPE, $name, $class, $style);
        $this->options = explode(',', $options);
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
        $name  = !empty($this->name) ? 'name="' . $this->name . '"' : '';
        $class = !empty($this->class) ? 'class="validate ' . $this->class . '"' : 'class="validate"';
        $style = !empty($this->style) ? 'style="' . $this->style . '"' : '';

        ob_start();
        if (current_theme_supports('materialize')) {
            ?>
            <div class="input-field">
                <select id="<?= $this->id ?>" <?= $name ?> <?= $class ?> <?= $style ?>>
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
}
