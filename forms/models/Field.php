<?php

namespace mp_ssv_general\forms\models;

use mp_ssv_general\base\BaseFunctions;
use mp_ssv_general\base\User;

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
            'profileField'   => true,
            'size'           => 1,
        ];
        $currentUserCanOverride = self::currentUserCanOverride($field['overrideRights']);
        $attributesString       = 'id="' . BaseFunctions::escape($field['formId'] . '_' . $element . '_' . $field['name'], 'attr') . '"';
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
            $profileValue = User::getCurrent()->getMeta($field['name']);
            if (!empty($field['value'])) {
                $attributesString .= ' value="' . BaseFunctions::escape($field['value'], 'attr') . '"';
            } elseif ($field['profileField'] && !empty($profileValue)) {
                $attributesString .= ' value="' . BaseFunctions::escape($profileValue, 'attr') . '"';
            } elseif (!empty($field['defaultValue'])) {
                $attributesString .= ' value="' . BaseFunctions::escape($field['defaultValue'], 'attr') . '"';
            }
        }
        if (in_array('multiple', $options) && $field['multiple']) {
            $attributesString .= ' multiple="multiple"';
        }
        if (in_array('size', $options) && $field['size'] > 1) {
            $attributesString .= ' size="' . BaseFunctions::escape($field['size'], 'attr') . '"';
        }
        if (in_array('for', $options)) {
            $attributesString .= ' for="' . BaseFunctions::escape($field['formId'] . '_' . 'input_' . $field['name'], 'attr') . '"';
        }
        if (in_array('autocomplete', $options) && !empty($field['autocomplete'])) {
            $attributesString .= ' autocomplete="' . $field['autocomplete'] . '"';
        }
        if (in_array('placeholder', $options) && !empty($field['placeholder'])) {
            $attributesString .= ' placeholder="' . $field['placeholder'] . '"';
        }
        if (in_array('list', $options) && !empty($field['list'])) {
            $attributesString .= ' list="' . $field['list'] . '"';
        }
        if (in_array('pattern', $options) && !empty($field['pattern'])) {
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
