<?php

namespace mp_general\shortcodes;

use mp_general\base\BaseFunctions;

abstract class NotFoundShortcode
{
    public static function notFoundShortcode(string $innerHtml, string $message)
    {
        if (current_user_can('edit')) {
            return '<span style="color: red;" title="' . BaseFunctions::escape($message, 'attr') . '">' . BaseFunctions::escape($innerHtml, 'html') . '</span>';
        } else {
            return $innerHtml;
        }
    }
}

add_shortcode('post-content', [PostContent::class, 'postContent']);
