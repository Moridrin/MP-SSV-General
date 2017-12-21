<?php

namespace mp_ssv_general\custom_fields\input_fields;

use mp_ssv_general\custom_fields\InputField;
use mp_ssv_general\Message;
use mp_ssv_general\SSV_General;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Created by PhpStorm.
 * User: moridrin
 * Date: 10-1-17
 * Time: 12:03
 */
class CustomInputField extends InputField
{
    public $disabled;
    public $required;
    public $defaultValue;
    public $placeholder;

    protected function __construct(
        int $id,
        string $name,
        string $title,
        string $type,
        int $order = null,
        array $classes = [],
        array $styles = [],
        array $overrideRights = [],
        bool $disabled = false,
        bool $required = false,
        string $defaultValue = null,
        string $placeholder = null
    ) {
        parent::__construct($id, $name, $title, $type, $order, $classes, $styles, $overrideRights);
        $this->disabled     = filter_var($disabled, FILTER_VALIDATE_BOOLEAN);
        $this->required     = filter_var($required, FILTER_VALIDATE_BOOLEAN);
        $this->defaultValue = $defaultValue;
        $this->placeholder  = $placeholder;
    }

    public function getHTML(): string
    {
        $inputId = SSV_General::escape('checkbox_' . $this->name, 'attr');
        $labelId = SSV_General::escape('label_' . $this->name, 'attr');
        $divId   = SSV_General::escape('div_' . $this->name, 'attr');

        $value       = !empty($this->value) ? $this->value : $this->defaultValue;
        $inputType   = 'type="' . SSV_General::escape($this->inputType, 'attr') . '"';
        $name        = 'name="' . esc_html($this->name) . '"';
        $class       = !empty($this->classes) ? 'class="' . esc_html($this->classes) . '"' : '';
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
        ?>
        <div <?= $this->getElementAttributesString($divId) ?>>
            <label for="<?= $inputId ?>" <?= $this->getElementAttributesString($labelId) ?>><?= esc_html($this->title) ?><?= $this->required ? '*' : '' ?></label>
            <input <?= $inputType ?> <?= $this->getElementAttributesString($inputId, '', ['required' => true, 'disabled' => true, 'value' => true]) ?>/>
        </div>
        <?php

        return trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));
    }

    /**
     * @return string the filter for this field as HTML object.
     */
    public function getFilterRow()
    {
        ob_start();
        ?><input id="<?= esc_html($this->order) ?>" type="text" name="<?= esc_html($this->name) ?>" title="<?= esc_html($this->title) ?>"/><?php
        return $this->getFilterRowBase(ob_get_clean());
    }

    /**
     * @return Message[]|bool array of errors or true if no errors.
     */
    public function isValid()
    {
        $errors = array();
        if (($this->required && !$this->disabled) && empty($this->value)) {
            $errors[] = new Message($this->title . ' field is required but not set.', current_user_can($this->overrideRights) ? Message::SOFT_ERROR_MESSAGE : Message::ERROR_MESSAGE);
        }
        switch (strtolower($this->inputType)) {
            case 'iban':
                $this->value = str_replace(' ', '', strtoupper($this->value));
                if (!empty($this->value) && !SSV_General::isValidIBAN($this->value)) {
                    $errors[] = new Message($this->title . ' field is not a valid IBAN.', current_user_can($this->overrideRights) ? Message::SOFT_ERROR_MESSAGE : Message::ERROR_MESSAGE);
                }
                break;
            case 'email':
                if (!filter_var($this->value, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = new Message($this->title . ' field is not a valid email.', current_user_can($this->overrideRights) ? Message::SOFT_ERROR_MESSAGE : Message::ERROR_MESSAGE);
                }
                break;
            case 'url':
                if (!filter_var($this->value, FILTER_VALIDATE_URL)) {
                    $errors[] = new Message($this->title . ' field is not a valid url.', current_user_can($this->overrideRights) ? Message::SOFT_ERROR_MESSAGE : Message::ERROR_MESSAGE);
                }
                break;
        }
        return empty($errors) ? true : $errors;
    }
}
