<?php

namespace mp_general\shortcodes;

abstract class ViewRight
{
    public static function viewRight($attributes, $innerHtml)
    {
        if (!is_array($attributes) || !isset($attributes['right'])) {
            return NotFoundShortcode::notFoundShortcode($innerHtml, 'Right not found');
        }
        if (!current_user_can($attributes['right']) && !current_user_can('administrator')) {
            if (isset($attributes['placeholder'])) {
                return $attributes['placeholder'];
            }
            return '';
        }
        return trim(do_shortcode($innerHtml));
    }

    public static function viewRightPlaceholder($attributes, $innerHtml)
    {
        if (!is_array($attributes) || !isset($attributes['right'])) {
            return NotFoundShortcode::notFoundShortcode($innerHtml, 'Right not found');
        }
        if (!current_user_can($attributes['right']) && !current_user_can('administrator')) {
            return trim(do_shortcode($innerHtml));
        }
        return '';
    }
}

add_shortcode('view-right', [ViewRight::class, 'viewRight']);
add_shortcode('view-right-sub', [ViewRight::class, 'viewRight']);
add_shortcode('view-right-placeholder', [ViewRight::class, 'viewRightPlaceholder']);
