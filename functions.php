<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * @param string $string
 * @param bool   $capitalizeFirstCharacter
 *
 * @return string
 */
function mp_ssv_to_camel_case($string, $capitalizeFirstCharacter = false)
{
    $string = str_replace(' ', '', mp_ssv_to_title($string));

    if (!$capitalizeFirstCharacter) {
        $string[0] = strtolower($string[0]);
    }

    return $string;
}

/**
 * @param string $string
 *
 * @return string
 */
function mp_ssv_to_title($string)
{
    $string = preg_replace('/(?<!\ )[A-Z]/', ' $0', $string);
    $string = str_replace('-', ' ', $string);
    $string = str_replace('_', ' ', $string);
    $string = ucwords($string);
    return $string;
}

/**
 * @param string $string
 *
 * @return string
 */
function mp_ssv_to_snake_case($string)
{
    preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $string, $matches);
    $ret = $matches[0];
    foreach ($ret as &$match) {
        $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
    }
    return implode('_', $ret);
}

/**
 * @param string $string
 *
 * @return string
 */
function mp_ssv_to_value($string)
{
    $string = str_replace(' ', '_', $string);
    $string = strtolower($string);
    return $string;
}

/**
 * @param $haystack
 * @param $needle
 * @param $replacement
 * @param $position
 *
 * @return mixed
 */
function mp_ssv_replace_at_pos($haystack, $needle, $replacement, $position)
{
    return substr_replace($haystack, $replacement, $position, strlen($needle));
}

/**
 * @param $haystack
 * @param $needle
 *
 * @return bool
 */
function mp_ssv_starts_with($haystack, $needle)
{
    return $needle === '' || strrpos($haystack, $needle, -strlen($haystack)) !== false;
}

/**
 * @param $haystack
 * @param $needle
 *
 * @return bool
 */
function mp_ssv_ends_with($haystack, $needle)
{
    return $needle === '' || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
}
