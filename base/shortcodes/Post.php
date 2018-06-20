<?php

namespace mp_general\shortcodes;

abstract class Post
{
    protected static function getPost($attributes, &$innerHtml)
    {
        if (is_array($attributes) && isset($attributes['id'])) {
            $post = get_post($attributes['id']);
        } else {
            $post = get_page_by_title($innerHtml, OBJECT, get_post_types());
        }
        if ($post === null) {
            $innerHtml = NotFoundShortcode::notFoundShortcode($innerHtml, 'Post Not Found');
        }
        return $post;
    }
}

require_once 'PostContent.php';
require_once 'PostLink.php';
