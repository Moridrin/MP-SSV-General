<?php

namespace mp_ssv_general\custom_fields\input_fields;

use mp_ssv_general\base\BaseFunctions;
use mp_ssv_general\custom_fields\InputField;

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

    /**
     * @param array   $arguments {
     *
     * @type int      $id
     * @type string   $name
     * @type string   $title
     * @type int|null $order
     * @type array    $classes
     * @type array    $styles
     * @type array    $overrideRights
     * @type bool     $disabled
     * @type bool     $required
     * @type bool     $defaultChecked
     * }
     */
    protected function __construct(array $arguments = [])
    {
        $arguments += [
            'order'          => null,
            'classes'        => [],
            'styles'         => [],
            'overrideRights' => [],
            'disabled'       => false,
            'required'       => false,
            'defaultChecked' => false,
        ];
        parent::__construct($arguments['id'], $arguments['name'], $arguments['title'], self::INPUT_TYPE, $arguments['order'], $arguments['classes'], $arguments['styles'], $arguments['overrideRights']);
        $this->disabled       = $arguments['disabled'];
        $this->required       = $arguments['required'];
        $this->defaultChecked = $arguments['defaultChecked'];
        $checkboxId           = BaseFunctions::escape('checkbox_' . $this->id, 'attr');
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
        $divId      = BaseFunctions::escape('div_' . $this->name, 'attr');
        $fallbackId = BaseFunctions::escape('fallback_' . $this->name, 'attr');
        $checkboxId = BaseFunctions::escape('checkbox_' . $this->name, 'attr');
        ob_start();
        ?>
        <div <?= $this->getElementAttributesString($divId) ?>>
            <input type="hidden" <?= $this->getElementAttributesString($fallbackId) ?> value="false"/>
            <input type="checkbox" <?= $this->getElementAttributesString($checkboxId, '', ['disabled' => true, 'checked' => true, 'required' => true]) ?> />
            <label for="<?= $checkboxId ?>"><?= BaseFunctions::escape($this->title, 'html') ?><?= $this->required ? '*' : '' ?></label>
        </div>
        <?php
        return trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));
    }

    /*
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

    public function isValid()
    {
        $errors = array();
        if (($this->required && !$this->disabled) && $this->getValue() !== true) {
            $errors[] = new Message($this->title . ' is required but checked.', $this->currentUserCanOcerride() ? Message::SOFT_ERROR_MESSAGE : Message::ERROR_MESSAGE);
        }
        return empty($errors) ?: $errors;
    }*/
}
