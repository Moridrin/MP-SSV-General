<?php

namespace mp_ssv_general\custom_fields;

use Exception;
use mp_ssv_general\base\BaseFunctions;
use mp_ssv_general\base\SSV_Global;

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
        $this->title     = $title;
        $this->fieldType = $fieldType;
        if ($order === null) {
            $order = $id;
        }
        $this->order   = $order;
        $this->classes = $classes;
        $this->styles  = $styles;
    }

    public static function titleFromDatabase(string $name): string
    {
        $database = SSV_Global::getDatabase();
        $table = BaseFunctions::SHARED_BASE_FIELDS_TABLE;
        return $database->get_var("SELECT bf_title FROM $table WHERE bf_name = '$name'");
    }

    /**
     * @param string $json
     * @return Field
     * @throws Exception
     */
    public static function fromJSON(string $json): Field
    {
        $values = json_decode($json);
        switch ($values->type) {
            case InputField::FIELD_TYPE:
                return InputField::fromJSON($json);
            case TabField::FIELD_TYPE:
                return new TabField(...json_decode($json, true));
            case HeaderField::FIELD_TYPE:
                return new HeaderField(...json_decode($json, true));
            case LabelField::FIELD_TYPE:
                return new LabelField(...json_decode($json, true));
        }
        throw new Exception($values->type . ' is an unknown field type');
    }

    public function toJSON(): string
    {
        return json_encode(get_object_vars($this));
    }

//    abstract public function getHTML(): string;

    public static function getElementAttributesString($field, string $elementId, string $nameSuffix = null, array $options = []): string
    {
        $options          += [
            'type'     => false,
            'required' => false,
            'disabled' => false,
            'checked'  => false,
            'value'    => false,
        ];
        $attributesString = 'id="' . $elementId . '"';
        if (isset($options['type'])) {
            $attributesString .= ' type="' . $field->fieldType . '"';
        }
        if (isset($field->classes[$elementId])) {
            $attributesString .= ' class="' . BaseFunctions::escape($field->classes[$elementId], 'attr', ' ') . '"';
        }
        if (isset($field->styles[$elementId])) {
            $attributesString .= ' style="' . BaseFunctions::escape($field->styles[$elementId], 'attr', ' ') . '"';
        }
        if ($field instanceof InputField) {
            $currentUserCanOverride = $field->currentUserCanOcerride();
            if ($nameSuffix !== null) {
                $attributesString .= ' name="' . BaseFunctions::escape($field->name . $nameSuffix, 'attr') . '"';
            }
            if (!$currentUserCanOverride && $options['required'] && isset($field->required)) {
                $attributesString .= $field->required ? 'required="required"' : '';
            }
            if (!$currentUserCanOverride && $options['disabled'] && isset($field->disabled)) {
                $attributesString .= disabled($field->disabled, true, false);
            }
            if ($options['checked'] && isset($field->checked)) {
                $attributesString .= checked($field->checked, true, false);
            }
            if ($options['value']) {
                $attributesString .= checked($field->getValue(), true, false);
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
