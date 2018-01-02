<?php

namespace mp_ssv_general;

use mp_ssv_general\base\BaseFunctions;

if (!defined('ABSPATH')) {
    exit;
}

abstract class Field
{
    public static function getElementAttributesString(array $field, string $elementId, string $nameSuffix = null, array $options = []): string
    {
        $field            += [
            'inputType'      => 'text',
            'classes'        => [],
            'styles'         => [],
            'overrideRights' => [],
            'required'       => false,
            'disabled'       => false,
            'checked'        => false,
            'value'          => null,
        ];
        $options          += [
            'type'     => false,
            'required' => false,
            'disabled' => false,
            'checked'  => false,
            'value'    => false,
        ];
        $currentUserCanOverride = self::currentUserCanOverride($field['overrideRights']);
        $attributesString = 'id="' . $elementId . '"';
        if ($options['type']) {
            $attributesString .= ' type="' . $field['inputType'] . '"';
        }
        if (isset($field['classes'][$elementId])) {
            $attributesString .= ' class="' . BaseFunctions::escape($field['classes'][$elementId], 'attr', ' ') . '"';
        }
        if (isset($field['styles'][$elementId])) {
            $attributesString .= ' style="' . BaseFunctions::escape($field['styles'][$elementId], 'attr', ' ') . '"';
        }
        if ($nameSuffix !== null) {
            $attributesString .= ' name="' . BaseFunctions::escape($field['name']. $nameSuffix, 'attr') . '"';
        }
        if (!$currentUserCanOverride && $options['required'] && $field['required']) {
            $attributesString .= $field['required ']? 'required="required"' : '';
        }
        if (!$currentUserCanOverride && $options['disabled'] && $field['disabled']) {
            $attributesString .= disabled($field['disabled'], true, false);
        }
        if ($options['checked'] && $field['checked']) {
            $attributesString .= checked($field['checked'], true, false);
        }
        if ($options['value']) {
            $attributesString .= checked($field['getValue'](), true, false);
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
