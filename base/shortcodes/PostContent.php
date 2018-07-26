<?php

namespace mp_general\shortcodes;

use mp_general\base\BaseFunctions;

abstract class PostContent extends Post
{
    public static function postContent($attributes, $innerHtml)
    {
        // BaseFunctions::var_export($attributes);
        $postContentPost = self::getPost($attributes, $innerHtml);
        if ($postContentPost !== null) {
            $html = '';
            $attributes += [
                'header' => $postContentPost->post_title,
            ];
            ob_start();
            if (isset($attributes['header-url'])) {
                ?><h1><a href="<?= BaseFunctions::escape($attributes['header-url'], 'url') ?>"><?= BaseFunctions::escape($attributes['header'], 'html') ?></a></h1><?php
            } else {
                ?><h1><?= BaseFunctions::escape($attributes['header'], 'html') ?></h1><?php
            }
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
