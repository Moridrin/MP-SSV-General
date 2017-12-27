<?php

namespace mp_ssv_general\custom_fields;

use mp_ssv_general\BaseFunctions;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Created by PhpStorm.
 * User: moridrin
 * Date: 6-1-17
 * Time: 6:38
 */
class LabelField extends Field
{
    const FIELD_TYPE = 'label';

    public $text;

    protected function __construct(int $id, string $title, string $name, int $order = null, string $text = '', array $classes = [], array $styles = [])
    {
        parent::__construct($id, $title, self::FIELD_TYPE, $name, $order, $classes, $styles);
        $this->text = $text;
    }

    public function getHTML(): string
    {
        $labelId = BaseFunctions::escape('label_' . $this->id, 'attr');
        return '<p ' . $this->getElementAttributesString($labelId) . '>' . BaseFunctions::escape($this->text, 'html') . '</p><br/>';
    }
}
