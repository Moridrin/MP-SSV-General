<?php

namespace mp_ssv_general\custom_fields\input_fields;

use DateTime;
use mp_ssv_general\base\BaseFunctions;
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

    /**
     * @param array $arguments {
     *
     * @type int    $id
     * @type string $name
     * @type string $title
     * @type int    $order
     * @type array  $classes
     * @type array  $styles
     * @type array  $overrideRights
     * @type bool   $disabled
     * @type bool   $required
     * @type mixed  $defaultValue
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
        $this->disabled     = $arguments['disabled'];
        $this->required     = $arguments['required'];
        $this->defaultValue = $arguments['defaultValue'];
        $this->placeholder  = $arguments['placeholder'];
    }

    /**
     * @return string the field as HTML object.
     */
    public function getHTML(): string
    {
        if (strtolower($this->defaultValue) == 'now') {
            $this->defaultValue = (new DateTime('NOW'))->format('Y-m-d');
        }
        $divId = BaseFunctions::escape('div_' . $this->name, 'attr');
        $input = BaseFunctions::escape('input_' . $this->name, 'attr');
        $label = BaseFunctions::escape('label_' . $this->name, 'attr');
        ob_start();
        if (current_theme_supports('materialize')) {
            ?>
            <div <?= $this->getElementAttributesString($divId) ?>>
                <input type="text" <?= $this->getElementAttributesString($input, '', ['disabled' => true, 'checked' => true, 'required' => true]) ?>/>
                <label <?= $this->getElementAttributesString($label) ?>for="<?= $label ?>"><?= BaseFunctions::escape($this->title, 'html') ?><?= $this->required ? '*' : '' ?></label>
            </div>
            <?php
        } else {
            ?>
            <label <?= $this->getElementAttributesString($label) ?>for="<?= $label ?>"><?= BaseFunctions::escape($this->title, 'html') ?><?= $this->required ? '*' : '' ?></label>
            <input type="checkbox" <?= $this->getElementAttributesString($input, '', ['disabled' => true, 'checked' => true, 'required' => true]) ?>/>
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
