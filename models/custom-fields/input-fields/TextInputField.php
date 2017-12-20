<?php

namespace mp_ssv_general\custom_fields\input_fields;

use DateTime;
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
class TextInputField extends InputField
{
    const INPUT_TYPE = 'text';

    public $disabled;
    public $required;
    public $defaultValue;
    public $placeholder;

    protected function __construct(int $id, string $name, string $title, int $order = null, array $classes = [], array $styles = [], array $overrideRights = [], bool $disabled = false, bool $required = false, mixed $defaultValue = null, string $placeholder = '')
    {
        parent::__construct($id, $name, $title, self::INPUT_TYPE, $order, $classes, $styles, $overrideRights);
        $this->disabled     = $disabled;
        $this->required     = $required;
        $this->defaultValue = $defaultValue;
        $this->placeholder  = $placeholder;
    }

    /**
     * @return string the field as HTML object.
     */
    public function getHTML()
    {
        if (strtolower($this->defaultValue) == 'now') {
            $this->defaultValue = (new DateTime('NOW'))->format('Y-m-d');
        }
        $value       = !empty($this->value) ? $this->value : $this->defaultValue;
        $id          = !empty($this->order) ? 'id="' . esc_html($this->order) . '"' : '';
        $name        = 'name="' . $this->name . '"';
        $class       = !empty($this->classes) ? 'class="' . esc_html($this->classes) . '"' : 'class="validate"';
        $style       = !empty($this->styles) ? 'style="' . esc_html($this->styles) . '"' : '';
        $placeholder = !empty($this->placeholder) ? 'placeholder="' . esc_html($this->placeholder) . '"' : '';
        $value       = !empty($value) ? 'value="' . esc_html($value) . '"' : '';
        $disabled    = disabled($this->disabled, true, false);
        $required    = $this->required ? 'required="required"' : '';

        if (!empty($this->overrideRights) && current_user_can($this->overrideRights)) {
            $disabled = '';
            $required = '';
        }

        ob_start();
        if (current_theme_supports('materialize')) {
            ?>
            <div class="input-field">
                <input type="text" <?= $id ?> <?= $name ?> <?= $class ?> <?= $style ?> <?= $value ?> <?= $disabled ?> <?= $placeholder ?> <?= $required ?> title="<?= esc_html($this->title) ?>"/>
                <label><?= esc_html($this->title) ?><?= $this->required ? '*' : '' ?></label>
            </div>
            <?php
        } else {
            ?>
            <label><?= esc_html($this->title) ?><?= $this->required ? '*' : '' ?></label><br/>
            <input type="text" <?= $id ?> <?= $name ?> <?= $class ?> <?= $style ?> <?= $value ?> <?= $disabled ?> <?= $placeholder ?> <?= $required ?> title="<?= esc_html($this->title) ?>"/><br/>
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
        ?><input id="<?= esc_html($this->order) ?>" type="text" name="<?= esc_html($this->name) ?>" class="field-filter" title="<?= esc_html($this->title) ?>"/><?php
        return $this->getFilterRowBase(ob_get_clean());
    }

    /**
     * @return Message[]|bool array of errors or true if no errors.
     */
    public function isValid()
    {
        $errors = array();
        if (($this->required && !$this->disabled) && (empty($this->value))) {
            $errors[] = new Message($this->title . ' is required but not set.', current_user_can($this->overrideRights) ? Message::SOFT_ERROR_MESSAGE : Message::ERROR_MESSAGE);
        }
        return empty($errors) ? true : $errors;
    }
}
