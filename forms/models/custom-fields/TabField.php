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
class TabField extends Field
{
    const FIELD_TYPE = 'tab';

    public $fields;

    protected function __construct(int $id, string $title, string $name, int $order = null, array $fields = [], array $classes = [], array $styles = [])
    {
        parent::__construct($id, $title, self::FIELD_TYPE, $name, $order, $classes, $styles);
        $this->fields = $fields;
        $tabId        = BaseFunctions::escape('tab_' . $this->id, 'attr');
        if (!isset($this->classes[$tabId])) {
            $this->classes[$tabId] = ['tab'];
        }
        if (in_array('!tab', $this->classes[$tabId])) {
            $this->classes[$tabId] = array_diff($this->classes[$tabId], ['!tab', 'tab']);
        }
    }

    public function addField(int $id, Field $field)
    {
        $this->fields[$id] = $field;
    }

    public function getHTML(): string
    {
        $tabId = BaseFunctions::escape('tab_' . $this->id, 'attr');
        $aId   = BaseFunctions::escape('a_' . $this->id, 'attr');
        if (isset($_POST['tab']) && $_POST['tab'] == $this->order) {
            $this->classes[$tabId][] = 'active';
        }
        return '<li ' . $this->getElementAttributesString($tabId) . '><a ' . $this->getElementAttributesString($aId) . '>' . BaseFunctions::escape($this->title, 'html') . '</a></li>';
    }
}
