<?php

namespace mp_ssv_general\custom_fields\input_fields;

use Exception;
use mp_ssv_general\custom_fields\InputField;
use mp_ssv_general\Message;

if (!defined('ABSPATH')) {
    exit;
}

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
     * @param int    $order
     * @param string $title
     * @param string $name
     * @param bool   $disabled
     * @param string $options
     * @param string $class
     * @param string $style
     * @param string $overrideRight
     */
    protected function __construct($containerID, $order, $title, $name, $disabled, $options, $class, $style, $overrideRight)
    {
        parent::__construct($containerID, $order, $title, self::INPUT_TYPE, $name, $class, $style, $overrideRight);
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
            $values->container_id,
            $values->order,
            $values->title,
            $values->name,
            $values->disabled,
            $values->options,
            $values->class,
            $values->style,
            $values->override_right
        );
    }

    /**
     * @return string the class as JSON object.
     */
    public function toJSON()
    {
        $values = array(
            'container_id'   => $this->containerID,
            'order'          => $this->order,
            'title'          => $this->title,
            'field_type'     => $this->fieldType,
            'input_type'     => $this->inputType,
            'name'           => $this->name,
            'disabled'       => $this->disabled,
            'options'        => implode(',', $this->options),
            'class'          => $this->classes,
            'style'          => $this->styles,
            'override_right' => $this->overrideRights,
        );
        $values = json_encode($values);
        return $values;
    }

    /**
     * @return string the field as HTML object.
     */
    public function getHTML()
    {
        $name     = 'name="' . esc_html($this->name) . '"';
        $class    = !empty($this->classes) ? 'class="' . esc_html($this->classes) . '"' : 'class="validate"';
        $style    = !empty($this->styles) ? 'style="' . esc_html($this->styles) . '"' : '';
        $disabled = disabled($this->disabled, true, false);

        if (!empty($this->overrideRights) && current_user_can($this->overrideRights)) {
            $disabled = '';
        }

        ob_start();
        if (current_theme_supports('materialize')) {
            ?>
            <div class="input-field">
                <select id="<?= esc_html($this->order) ?>" <?= $name ?> <?= $class ?> <?= $style ?> <?= $disabled ?>>
                    <?php foreach ($this->options as $option): ?>
                        <option value="<?= $option ?>" <?= selected($option, $this->value) ?>><?= $option ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="<?= esc_html($this->order) ?>"><?= esc_html($this->title) ?></label>
            </div>
            <?php
        } else {
            ?>
            <div class="input-field">
                <label for="<?= esc_html($this->order) ?>"><?= esc_html($this->title) ?></label><br/>
                <select id="<?= esc_html($this->order) ?>" <?= $name ?> <?= $class ?> <?= $style ?> <?= $disabled ?>>
                    <?php foreach ($this->options as $option): ?>
                        <option value="<?= $option ?>" <?= selected($option, $this->value) ?>><?= $option ?></option>
                    <?php endforeach; ?>
                </select>
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
        <select id="<?= esc_html($this->order) ?>" name="<?= esc_html($this->name) ?>" title="<?= esc_html($this->title) ?>">
            <?php foreach ($this->options as $option): ?>
                <option value="<?= esc_html($option) ?>"><?= esc_html($option) ?></option>
            <?php endforeach; ?>
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
        if (!$this->disabled && (empty($this->value) || !in_array($this->value, $this->options))) {
            $errors[] = new Message('The value ' . $this->value . ' is not one of the options.', current_user_can($this->overrideRights) ? Message::SOFT_ERROR_MESSAGE : Message::ERROR_MESSAGE);
        }
        return empty($errors) ? true : $errors;
    }
}
