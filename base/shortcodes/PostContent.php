<?php

namespace mp_general\shortcodes;

abstract class PostContent extends Post
{
    public static function postContent($attributes, $innerHtml)
    {
        $post = self::getPost($attributes, $innerHtml);
        if ($post !== null) {
            $html = '';
            if (isset($attributes['header'])) {
                ob_start();
                ?><h1><?= \mp_general\base\BaseFunctions::escape($attributes['header'], 'html') ?></h1><?php
                $html .= ob_get_clean();
            }
            $html .= apply_filters('the_content', do_shortcode($post->post_content));
            return $html;
        }
        return $innerHtml;
    }
}

add_shortcode('post-content', [PostContent::class, 'postContent']);
add_shortcode('pc', [PostContent::class, 'postContent']);
