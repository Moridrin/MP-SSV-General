<?php

namespace mp_ssv_general;

use mp_ssv_general\base\BaseFunctions;

if (!defined('ABSPATH')) {
    exit;
}

abstract class Field
{
    public static function getElementAttributesString(array $field, string $element, array $options = [], string $nameSuffix = null): string
    {
        $field                  += [
            'inputType'      => 'text',
            'classes'        => [],
            'styles'         => [],
            'overrideRights' => [],
            'required'       => false,
            'disabled'       => false,
            'checked'        => false,
            'value'          => null,
            'autocomplete'   => null,
            'placeholder'    => null,
            'list'           => null,
            'pattern'        => null,
            'multiple'       => false,
            'selected'       => false,
            'size'           => 1,
        ];
        $options                += [
            'type',
            'required',
            'disabled',
            'checked',
            'value',
            'selected',
            'multiple',
            'size',
            'for',
            'autocomplete',
            'placeholder',
            'list',
            'pattern',
        ];
        $currentUserCanOverride = self::currentUserCanOverride($field['overrideRights']);
        $attributesString       = 'id="' . BaseFunctions::escape($element . '_' . $field['name'], 'attr') . '"';
        if (in_array('type', $options)) {
            $attributesString .= ' type="' . $field['inputType'] . '"';
        }
        if (isset($field['classes'][$element])) {
            $attributesString .= ' class="' . BaseFunctions::escape($field['classes'][$element], 'attr', ' ') . '"';
        }
        if (isset($field['styles'][$element])) {
            $attributesString .= ' style="' . BaseFunctions::escape($field['styles'][$element], 'attr', ' ') . '"';
        }
        if ($nameSuffix !== null) {
            $attributesString .= ' name="' . BaseFunctions::escape($field['name'] . $nameSuffix, 'attr') . '"';
        }
        if (!$currentUserCanOverride && in_array('required', $options) && $field['required']) {
            $attributesString .= $field['required'] ? 'required="required"' : '';
        }
        if (!$currentUserCanOverride && in_array('disabled', $options) && $field['disabled']) {
            $attributesString .= disabled($field['disabled'], true, false);
        }
        if (in_array('checked', $options) && $field['checked']) {
            $attributesString .= checked($field['checked'], true, false);
        }
        if (in_array('value', $options)) {
            if (empty($field['value']) && !empty($field['defaultValue'])) {
                $attributesString .= ' value="' . BaseFunctions::escape($field['defaultValue'], 'attr') . '"';
            } elseif (!empty($field['value'])) {
                $attributesString .= ' value="' . BaseFunctions::escape($field['value'], 'attr') . '"';
            }
        }
        if (in_array('multiple', $options) && $field['multiple']) {
            $attributesString .= ' multiple="multiple"';
        }
        if (in_array('size', $options) && $field['size'] > 1) {
            $attributesString .= ' size="' . BaseFunctions::escape($field['size'], 'attr') . '"';
        }
        if (in_array('for', $options)) {
            $attributesString .= ' for="' . BaseFunctions::escape('input_' . $field['name'], 'attr') . '"';
        }
        if (in_array('autocomplete', $options)) {
            $attributesString .= ' autocomplete="' . $field['autocomplete'] . '"';
        }
        if (in_array('placeholder', $options)) {
            $attributesString .= ' placeholder="' . $field['placeholder'] . '"';
        }
        if (in_array('list', $options)) {
            $attributesString .= ' list="' . $field['list'] . '"';
        }
        if (in_array('pattern', $options)) {
            $attributesString .= ' pattern="' . $field['pattern'] . '"';
        }
        return $attributesString;
    }

    private static function currentUserCanOverride($overrideRights): bool
    {
        foreach ($overrideRights as $overrideRight) {
            if (current_user_can($overrideRight)) {
                return true;
            }
        }
        return false;
    }
}
