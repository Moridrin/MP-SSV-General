<?php

namespace mp_ssv_general\custom_fields;

use mp_ssv_general\SSV_Base;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Created by PhpStorm.
 * User: moridrin
 * Date: 6-1-17
 * Time: 6:38
 */
class HeaderField extends Field
{
    const FIELD_TYPE = 'header';

    protected function __construct(int $id, string $title, string $name, int $order = null, array $classes = [], array $styles = [])
    {
        parent::__construct($id, $title, self::FIELD_TYPE, $name, $order, $classes, $styles);
    }

    public function getHTML(): string
    {
        $headerId = SSV_Base::escape('header_' . $this->id, 'attr');
        return '<h2 ' . $this->getElementAttributesString($headerId) . '>' . SSV_Base::escape($this->title, 'html') . '</h2>';
    }
}
