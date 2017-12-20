<?php

namespace mp_ssv_general\custom_fields;

use Exception;
use mp_ssv_general\SSV_General;

if (!defined('ABSPATH')) {
    exit;
}

require_once 'TabField.php';
require_once 'HeaderField.php';
require_once 'InputField.php';
require_once 'LabelField.php';

abstract class Field
{
    protected $id;
    public $name;
    public $title;
    public $fieldType;
    public $order;
    public $classes;
    public $styles;

    protected function __construct(int $id, string $title, string $fieldType, string $name = null, int $order = null, array $classes = [], array $styles = [])
    {
        $this->id = $id;
        if ($name !== null) {
            $this->name = preg_replace('/[^A-Za-z0-9_\-]/', '', str_replace(' ', '_', strtolower($name)));
        }
        $this->title = $title;
        $this->fieldType = $fieldType;
        if ($order === null) {
            $order = $id;
        }
        $this->order = $order;
        $this->classes = $classes;
        $this->styles = $styles;
    }

    public static function titleFromDatabase(string $name): string
    {
        /** @var \wpdb $wpdb */
        global $wpdb;
        $table  = SSV_General::BASE_FIELDS_TABLE;
        return $wpdb->get_var("SELECT bf_title FROM $table WHERE bf_name = '$name'");
    }

    public static function fromJSON(string $json): Field
    {
        $values = json_decode($json);
        switch ($values->field_type) {
            case InputField::FIELD_TYPE:
                return InputField::fromJSON($json);
            case TabField::FIELD_TYPE:
                return new TabField(...json_decode($json, true));
            case HeaderField::FIELD_TYPE:
                return new HeaderField(...json_decode($json, true));
            case LabelField::FIELD_TYPE:
                return new LabelField(...json_decode($json, true));
        }
        throw new Exception($values->field_type . ' is an unknown field type');
    }

    public function toJSON(): string
    {
        return get_object_vars($this);
    }

    abstract public function getHTML(): string;

    protected function getElementAttributesString(string $elementId, string $nameSuffix = null, bool $withRequired = false, bool $withDisabled = false, bool $withChecked = false): string
    {
        $attributesString = 'id="'.$elementId.'"';
        if (isset($this->classes[$elementId])) {
            $attributesString .= ' class="' . SSV_General::escape($this->classes[$elementId], 'attr', ' ') . '"';
        }
        if (isset($this->styles[$elementId])) {
            $attributesString .= ' style="' . SSV_General::escape($this->styles[$elementId], 'attr', ' ') . '"';
        }
        if ($this instanceof InputField) {
            $currentUserCanOverride = $this->currentUserCanOcerride();
            if ($nameSuffix !== null) {
                $attributesString .= ' name="'.SSV_General::escape($this->name.$nameSuffix, 'attr').'"';
            }
            if (!$currentUserCanOverride && $withRequired && isset($this->required)) {
                $attributesString .= $this->required ? 'required="required"' : '';
            }
            if (!$currentUserCanOverride && $withDisabled && isset($this->disabled)) {
                $attributesString .= disabled($this->disabled, true, false);
            }
            if ($withChecked && isset($this->checked)) {
                $attributesString .= checked($this->checked, true, false);
            }
        }
        return $attributesString;
    }

    public function __compare(Field $field): int
    {
        return $this->order <=> $field->order;
    }

    public function __toString(): string
    {
        return $this->getHTML();
    }
}
