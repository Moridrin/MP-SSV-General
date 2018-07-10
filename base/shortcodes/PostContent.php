<?php

namespace mp_general\shortcodes;

abstract class PostContent extends Post
{
    public static function postContent($attributes, $innerHtml)
    {
        $postContentPost = self::getPost($attributes, $innerHtml);
        if ($postContentPost !== null) {
            $html = '';
            if (!isset($attributes['header'])) {
                $attributes['header'] = $postContentPost->post_title;
            }
            ob_start();
            ?><h1><?= \mp_general\base\BaseFunctions::escape($attributes['header'], 'html') ?></h1><?php
            $html .= ob_get_clean();
            global $post;
            $currentPost = $post;
            $post = $postContentPost;
            setup_postdata($postContentPost);
            $html .= apply_filters('the_content', do_shortcode($postContentPost->post_content));
            $post = $currentPost;
            setup_postdata($currentPost);
            return $html;
        }
        return $innerHtml;
    }
}

add_shortcode('post-content', [PostContent::class, 'postContent']);
add_shortcode('pc', [PostContent::class, 'postContent']);
