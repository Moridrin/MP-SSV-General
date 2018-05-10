<?php

namespace mp_general\base;

if (!defined('ABSPATH')) {
    exit;
}

abstract class Database
{
    public static function getPrefixForBlog(int $blogId = null): string
    {
        global $wpdb;
        if ($blogId === null) {
            return $wpdb->prefix;
        }
        return $wpdb->get_blog_prefix($blogId);
    }
    public static function getBasePrefix(): string
    {
        global $wpdb;
        return $wpdb->base_prefix;
    }
}
