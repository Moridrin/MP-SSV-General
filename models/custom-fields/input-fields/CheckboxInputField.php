<?php

namespace mp_ssv_general\custom_fields\input_fields;

use Exception;
use mp_ssv_general\custom_fields\InputField;
use mp_ssv_general\Message;
use mp_ssv_general\SSV_General;
use mp_ssv_general\User;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Created by PhpStorm.
 * User: moridrin
 * Date: 10-1-17
 * Time: 12:03
 */
class CheckboxInputField extends InputField
{
    const INPUT_TYPE = 'checkbox';

    public $disabled;
    public $required;
    public $defaultChecked;

    protected function __construct(int $id, string $name, string $title, int $order = null, array $classes = [], array $styles = [], array $overrideRights = [], bool $disabled = false, bool $required = false, bool $defaultChecked = false)
    {
        parent::__construct($id, $name, $title, self::INPUT_TYPE, $order, $classes, $styles, $overrideRights);
        $this->disabled     = $disabled;
        $this->required     = $required;
        $this->defaultChecked = $defaultChecked;
        $checkboxId = SSV_General::escape('checkbox_'.$this->id, 'attr');
        if (!isset($this->classes[$checkboxId])) {
            $this->classes[$checkboxId] = ['validate', 'filled-id'];
        }
        if (in_array('!validate', $this->classes[$checkboxId])) {
            $this->classes[$checkboxId] = array_diff($this->classes[$checkboxId], ['!validate', 'validate']);
        }
        if (in_array('!filled-in', $this->classes[$checkboxId])) {
            $this->classes[$checkboxId] = array_diff($this->classes[$checkboxId], ['!filled-in', 'filled-in']);
        }
    }

    public function getHTML(): string
    {
        $divId = SSV_General::escape('div_'.$this->id, 'attr');
        $hiddenId = SSV_General::escape('fallback_'.$this->id, 'attr');
        $checkboxId = SSV_General::escape('checkbox_'.$this->id, 'attr');
        $labelId = SSV_General::escape('label_'.$this->id, 'attr');
        ob_start();
        ?>
        <div <?= $this->getElementAttributesString($divId) ?>>
            <input type="hidden" value="false" <?= $this->getElementAttributesString($hiddenId, '_reset') ?>/>
            <input type="checkbox"  value="true" <?= $this->getElementAttributesString($checkboxId, '', true, true, true) ?>/>
            <label for="<?= $checkboxId ?>" <?= $this->getElementAttributesString($labelId) ?>><?= SSV_General::escape($this->title, 'html') ?><?= $this->required ? '*' : '' ?></label>
        </div>
        <?php
        return trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));
    }

    public function getFilterRow(): string
    {
        ob_start();
        ?>
        <select id="<?= esc_html($this->order) ?>" name="<?= esc_html($this->name) ?>" title="<?= esc_html($this->title) ?>">
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
        if (($this->required && !$this->disabled) && $this->getValue() !== true) {
            $errors[] = new Message($this->title . ' is required but checked.', $this->currentUserCanOcerride() ? Message::SOFT_ERROR_MESSAGE : Message::ERROR_MESSAGE);
        }
        return empty($errors) ?: $errors;
    }
}
