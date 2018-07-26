<?php

namespace mp_general\base;

if (!defined('ABSPATH')) {
    exit;
}

abstract class SSV_Themes
{
    const THEME_STANDARD = 'standard';
    const THEME_MATERIALIZE = 'materialize';

    public static function getCurrentTheme(array $supported, string $default = self::THEME_STANDARD): string
    {
        foreach ($supported as $theme) {
            if (current_theme_supports($theme)) {
                return $theme;
            }
        }
        return $default;
    }
}
