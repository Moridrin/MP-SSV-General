<?php

namespace mp_general\shortcodes;

use mp_general\base\BaseFunctions;

abstract class PostLink extends Post
{
    public static function postLink($attributes, $innerHtml)
    {
        $post = self::getPost($attributes, $innerHtml);
        if ($post !== null) {
            return '<a href="' . get_permalink($post) . '">' . BaseFunctions::escape($innerHtml, 'html') . '</a>';
        }
        return $innerHtml;
    }
}

add_shortcode('post-link', [PostLink::class, 'postLink']);
add_shortcode('pl', [PostLink::class, 'postLink']);
