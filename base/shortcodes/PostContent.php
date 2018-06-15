<?php

namespace mp_general\shortcodes;

abstract class PostContent
{
    public static function postContent($attributes, $innerHtml)
    {
        if (!is_array($attributes)) {
            return $innerHtml;
        }
        return apply_filters('the_content', get_post($attributes['id'])->post_content);
    }
}

add_shortcode('post-content', [PostContent::class, 'postContent']);
