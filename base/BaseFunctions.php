<?php

namespace mp_general\base;

use DateTime;
use Exception;

if (!defined('ABSPATH')) {
    exit;
}

abstract class BaseFunctions
{
    private static $options = [];

    /**
     * This function can be called from anywhere and will redirect the page to the given location.
     *
     * @param string $location is the url where the page should be redirected to.
     */
    public static function redirect(string $location)
    {
        ?>
        <script type="text/javascript">
            window.location = "<?= $location ?>";
        </script>
        <?php
    }

    /**
     * This checks if the request is a POST request and if it has the correct admin referer.
     *
     * @param string $adminReferer
     *
     * @return bool
     */
    public static function isValidPOST(?string $adminReferer): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST' && (($adminReferer === null) ?: check_admin_referer($adminReferer));
    }

    /**
     * This checks if the provided string is a correct IBAN.
     *
     * @param string $iban
     *
     * @return bool
     */
    public static function isValidIBAN(string $iban): bool
    {
        $iban      = strtolower(str_replace(' ', '', $iban));
        $Countries = array('al' => 28,
                           'ad' => 24,
                           'at' => 20,
                           'az' => 28,
                           'bh' => 22,
                           'be' => 16,
                           'ba' => 20,
                           'br' => 29,
                           'bg' => 22,
                           'cr' => 21,
                           'hr' => 21,
                           'cy' => 28,
                           'cz' => 24,
                           'dk' => 18,
                           'do' => 28,
                           'ee' => 20,
                           'fo' => 18,
                           'fi' => 18,
                           'fr' => 27,
                           'ge' => 22,
                           'de' => 22,
                           'gi' => 23,
                           'gr' => 27,
                           'gl' => 18,
                           'gt' => 28,
                           'hu' => 28,
                           'is' => 26,
                           'ie' => 22,
                           'il' => 23,
                           'it' => 27,
                           'jo' => 30,
                           'kz' => 20,
                           'kw' => 30,
                           'lv' => 21,
                           'lb' => 28,
                           'li' => 21,
                           'lt' => 20,
                           'lu' => 20,
                           'mk' => 19,
                           'mt' => 31,
                           'mr' => 27,
                           'mu' => 30,
                           'mc' => 27,
                           'md' => 24,
                           'me' => 22,
                           'nl' => 18,
                           'no' => 15,
                           'pk' => 24,
                           'ps' => 29,
                           'pl' => 28,
                           'pt' => 25,
                           'qa' => 29,
                           'ro' => 24,
                           'sm' => 27,
                           'sa' => 24,
                           'rs' => 22,
                           'sk' => 24,
                           'si' => 19,
                           'es' => 24,
                           'se' => 24,
                           'ch' => 21,
                           'tn' => 24,
                           'tr' => 26,
                           'ae' => 23,
                           'gb' => 22,
                           'vg' => 24,
        );
        $Chars     = array('a' => 10,
                           'b' => 11,
                           'c' => 12,
                           'd' => 13,
                           'e' => 14,
                           'f' => 15,
                           'g' => 16,
                           'h' => 17,
                           'i' => 18,
                           'j' => 19,
                           'k' => 20,
                           'l' => 21,
                           'm' => 22,
                           'n' => 23,
                           'o' => 24,
                           'p' => 25,
                           'q' => 26,
                           'r' => 27,
                           's' => 28,
                           't' => 29,
                           'u' => 30,
                           'v' => 31,
                           'w' => 32,
                           'x' => 33,
                           'y' => 34,
                           'z' => 35,
        );

        if (empty($iban)) {
            return false;
        }

        try {
            if (strlen($iban) == $Countries[substr($iban, 0, 2)]) {

                $MovedChar      = substr($iban, 4) . substr($iban, 0, 4);
                $MovedCharArray = str_split($MovedChar);
                $NewString      = '';

                foreach ($MovedCharArray AS $key => $value) {
                    if (!is_numeric($MovedCharArray[$key])) {
                        $MovedCharArray[$key] = $Chars[$MovedCharArray[$key]];
                    }
                    $NewString .= $MovedCharArray[$key];
                }

                if (self::bcmod($NewString, '97') == 1) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (Exception $ex) {
            return false;
        }
    }

    /**
     * @param string      $adminReferer should be defined by a constant from the class you want to use this form in.
     * @param bool|string $saveButton   set to false if you don't want the save button to be displayed or give string to set custom button text.
     * @param bool|string $resetButton  set to false if you don't want the reset button to be displayed or give string to set custom button text.
     *
     * @return string HTML
     */
    public static function getAdminFormSecurityFields(string $adminReferer, $saveButton = true, $resetButton = false): string
    {
        ob_start();
        wp_nonce_field($adminReferer);
        if (is_string($saveButton)) {
            submit_button($saveButton);
        } elseif ($saveButton === true) {
            submit_button();
        }
        if ($resetButton) {
            ?><input type="submit" name="reset" id="reset" class="button button-primary" value="<?= is_string($resetButton) ? $resetButton : 'Reset to Default' ?>"><?php
        }
        return ob_get_clean();
    }

    /**
     * @param string      $adminReferer should be defined by a constant from the class you want to use this form in.
     * @param bool|string $saveButton   set to false if you don't want the save button to be displayed or give string to set custom button text.
     *
     * @return string HTML
     */
    public static function getFrontendFormSecurityFields(string $adminReferer, $saveButton = 'Submit'): string
    {
        ob_start();
        wp_nonce_field($adminReferer);
        if (is_string($saveButton)) {
            ?>
            <button type="submit"><?= $saveButton ?></button><?php
        }
        return ob_get_clean();
    }

    /**
     * @return string HTML
     */
    public static function getCapabilitiesDataList(): string
    {
        ob_start();
        if (function_exists('members_get_capabilities')) {
            $capabilities = members_get_capabilities();
        } else {
            $capabilities = array(
                'activate_plugins',
                'add_users',
                'create_users',
                'delete_others_pages',
                'delete_others_posts',
                'delete_pages',
                'delete_plugins',
                'delete_posts',
                'delete_private_pages',
                'delete_private_posts',
                'delete_published_pages',
                'delete_published_posts',
                'delete_themes',
                'delete_users',
                'edit_dashboard',
                'edit_files',
                'edit_others_pages',
                'edit_others_posts',
                'edit_pages',
                'edit_plugins',
                'edit_posts',
                'edit_private_pages',
                'edit_private_posts',
                'edit_published_pages',
                'edit_published_posts',
                'edit_theme_options',
                'edit_themes',
                'edit_users',
                'export',
                'import',
                'install_plugins',
                'install_themes',
                'list_users',
                'manage_categories',
                'manage_links',
                'manage_options',
                'moderate_comments',
                'promote_users',
                'publish_pages',
                'publish_posts',
                'read',
                'read_private_pages',
                'read_private_posts',
                'remove_users',
                'switch_themes',
                'unfiltered_html',
                'unfiltered_upload',
                'update_core',
                'update_plugins',
                'update_themes',
                'upload_files',
                'manage_events',
                'manage_event_registrations',
            );
        }
        ?>
        <datalist id="capabilities">
            <?php foreach ($capabilities as $capability): ?>
                <option value="<?= $capability ?>"><?= $capability ?></option>
            <?php endforeach; ?>
        </datalist>
        <?php
        return ob_get_clean();
    }

    public static function getInputTypes(array $exclude = []): array
    {
        $inputTypes = [
            'text',
            'select',
            'checkbox',
            'role_checkbox',
            'role_select',
            'datetime',
            'file',
            'hidden',
        ];
        return array_diff($inputTypes, $exclude);
    }

    public static function getInputTypeDataList(array $exclude = []): string
    {
        ob_start();
        $inputTypes = self::getInputTypes($exclude);
        ?>
        <datalist id="inputType">
            <?php foreach ($inputTypes as $inputType): ?>
                <option value="<?= self::toSnakeCase($inputType) ?>"><?= $inputType ?></option>
            <?php endforeach; ?>
        </datalist>
        <?php
        return ob_get_clean();
    }

    /**
     * @param             $value
     * @param             $sanitationType
     * @param string|null $implode
     *
     * @return mixed
     */
    public static function sanitize($value, $sanitationType, string $implode = null)
    {
        if (is_array($value)) {
            foreach ($value as $key => &$item) {
                if (is_array($sanitationType)) {
                    $item = self::sanitize($item, $sanitationType[$key]);
                } else {
                    $item = self::sanitize($item, $sanitationType);
                }
            }
            if ($implode !== null) {
                $value = implode($implode, $value);
            }
            return $value;
        }
        switch ($sanitationType) {
            case 'email':
                $value = sanitize_email($value);
                break;
            case 'file':
            case 'image':
                $value = sanitize_file_name($value);
                break;
            case 'color':
                $value = sanitize_hex_color($value);
                break;
            case 'class':
                $value = sanitize_html_class($value);
                break;
            case 'option':
                $value = sanitize_option($sanitationType, $value);
                break;
            case 'dateTime':
                $dateTime = DateTime::createFromFormat('Y-m-d H:i', sanitize_text_field($value));
                $value    = $dateTime ? $dateTime->format('Y-m-d H:i') : '';
                break;
            case 'date':
                $dateTime = DateTime::createFromFormat('Y-m-d', sanitize_text_field($value));
                $value    = $dateTime ? $dateTime->format('Y-m-d') : '';
                break;
            case 'time':
                $dateTime = DateTime::createFromFormat('H:i', sanitize_text_field($value));
                $value    = $dateTime ? $dateTime->format('H:i') : '';
                break;
            case 'bool':
            case 'boolean':
            case 'checkbox':
                $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                break;
            case 'int':
                if (strval($value) === '') {
                    $value = null;
                } else {
                    $value = intval($value);
                }
                break;
            case 'role':
            case 'role_select':
            case 'role_checkbox':
                if (function_exists('members_sanitize_role')) {
                    $value = members_sanitize_role($value);
                } else {
                    $value = sanitize_text_field($value);
                }
                break;
            default:
                $value = sanitize_text_field($value);
                break;
        }
        return $value;
    }

    public static function escape($value, $escapeType, string $implode = null)
    {
        if (is_array($value)) {
            foreach ($value as $key => &$item) {
                if (is_array($escapeType)) {
                    self::sanitize($item, $escapeType[$key]);
                } else {
                    self::sanitize($item, $escapeType);
                }
            }
            if ($implode !== null) {
                $value = implode($implode, $value);
            }
            return $value;
        }
        switch ($escapeType) {
            case 'html':
                $value = esc_html($value);
                break;
            case 'sql':
                $value = esc_sql($value);
                break;
            case 'attr':
            case 'attribute':
                $value = esc_attr($value);
                break;
            case 'js':
                $value = esc_js($value);
                break;
            case 'text':
            case 'textarea':
                $value = esc_textarea($value);
                break;
            case 'url':
                $value = esc_url($value);
                break;
            default:
                $function = 'esc_' . $escapeType;
                if (function_exists($function)) {
                    $value = $function($value);
                } else {
                    throw new \InvalidArgumentException($escapeType . ' is an unknown escape type.');
                }
                break;
        }
        return $value;
    }

    /**
     * This function is for development purposes only and lets the developer print a variable in the PHP formatting to inspect what the variable is set to.
     *
     * @param mixed $variable any variable that you want to be printed.
     * @param bool  $die      set true if you want to call die() after the print. $die is ignored if $return is true.
     *
     * @return mixed|null|string returns the print in string if $return is true, returns null if $return is false, and doesn't return if $die is true.
     */
    public static function var_export($variable, $die = false)
    {
        if (!$die) {
            echo '<div style="margin-left: 180px;">';
        }
        if (is_string($variable) && strpos($variable, 'FROM') !== false && strpos($variable, 'WHERE') !== false) {
            ob_start();
            echo $variable . ';';
            $query = ob_get_clean();
            require_once SSV_Global::PATH . 'lib/SqlFormatter.php';
            $print = SqlFormatter::highlight($query);
            $print = trim(preg_replace('/\s+/', ' ', $print));
        } else {
            if (self::_hasCircularReference($variable)) {
                $print = highlight_string("<?php " . var_dump($variable, true), true);
            } else {
                $print = highlight_string("<?php " . var_export($variable, true), true);
            }
            $print = trim($print);
            /** @noinspection HtmlUnknownAttribute */
            $print = preg_replace("|^\\<code\\>\\<span style\\=\"color\\: #[a-fA-F0-9]{0,6}\"\\>|", '', $print, 1);  // remove prefix
            $print = preg_replace("|\\</code\\>\$|", '', $print, 1);
            $print = trim($print);
            $print = preg_replace("|\\</span\\>\$|", '', $print, 1);
            $print = trim($print);
            /** @noinspection HtmlUnknownAttribute */
            $print = preg_replace("|^(\\<span style\\=\"color\\: #[a-fA-F0-9]{0,6}\"\\>)(&lt;\\?php&nbsp;)(.*?)(\\</span\\>)|", "\$1\$3\$4", $print);
            $print .= ';';
        }
        echo $print;
        echo '<br/>';

        if ($die) {
            die();
        } else {
            echo '</div>';
        }
        return null;
    }

    public static function getListSelect($name, $options, $selected)
    {
        $selected = self::escape($selected, 'html');
        $options  = self::escape($options, 'html');
        $name     = self::escape($name, 'attribute');
        ob_start();
        $optionCount = count($options);
        ?>
        <div style="float:left;margin-right:20px;">
            <label for="non_selected_fields">Available</label>
            <br/>
            <select id="non_selected_fields" size="<?= $optionCount > 25 ? 25 : $optionCount ?>" multiple title="Columns to Export" style="min-width: 200px;">
                <?php foreach ($options as $option): ?>
                    <option id="<?= $name ?>_non_selected_result_<?= $option ?>" onClick='<?= $name ?>_add("<?= $option ?>")' value="<?= $option ?>" <?= in_array($option, $selected) ? 'disabled' : '' ?>><?= $option ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div style="float:left;margin-right:20px;">
            <label for="selected_fields">Selected</label>
            <br/>
            <select id="selected_fields" size="<?= $optionCount > 25 ? 25 : $optionCount ?>" multiple title="Columns to Export" style="min-width: 200px;">
                <?php foreach ($selected as $option): ?>
                    <option id="<?= $name ?>_selected_result_<?= $option ?>" onClick='<?= $name ?>_remove("<?= $option ?>")' value="<?= $option ?>"><?= $option ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <input type="hidden" id="<?= $name ?>" name="<?= $name ?>" value=""/>
        <!--suppress JSUnusedAssignment -->
        <script>
            var options = <?= json_encode($selected) ?>;
            document.getElementById('<?= $name ?>').value = options;

            function <?= $name ?>_add(val) {
                options.push(val);
                document.getElementById('<?= $name ?>').value = options;
                var option = document.createElement("option");
                option.id = '<?= $name ?>_selected_result_' + val;
                option.text = val;
                option.addEventListener("click", function () {
                    <?= $name ?>_remove(val);
                }, false);
                document.getElementById('selected_fields').add(option);
                option = document.getElementById('<?= $name ?>_non_selected_result_' + val);
                option.setAttribute("disabled", "disabled");
            }

            function <?= $name ?>_remove(val) {
                var index = options.indexOf(val);
                if (index > -1) {
                    options.splice(index, 1);
                }
                document.getElementById('<?= $name ?>').value = options;
                var option = document.getElementById('<?= $name ?>_non_selected_result_' + val);
                option.removeAttribute("disabled");
                option = document.getElementById('<?= $name ?>_selected_result_' + val);
                option.parentNode.removeChild(option);
            }
        </script>
        <?php
        return ob_get_clean();
    }

    private static function bcmod($x, $y)
    {
        $take = 5;
        $mod  = '';

        do {
            $a   = (int)$mod . substr($x, 0, $take);
            $x   = substr($x, $take);
            $mod = $a % $y;
        } while (strlen($x));

        return (int)$mod;
    }

    private static function _hasCircularReference($variable)
    {
        $dump = print_r($variable, true);
        if (strpos($dump, '*RECURSION*') !== false) {
            return true;
        } else {
            return false;
        }
    }

    public static function toCamelCase(string $string, bool $capitalizeFirstCharacter = false): string
    {
        $string = str_replace(' ', '', self::toTitle($string));

        if (!$capitalizeFirstCharacter) {
            $string[0] = strtolower($string[0]);
        }

        return $string;
    }

    public static function toTitle(string $string): string
    {
        $string = preg_replace('/(?<!\ )[A-Z]/', ' $0', $string);
        $string = str_replace('-', ' ', $string);
        $string = str_replace('_', ' ', $string);
        $string = ucwords($string);
        return $string;
    }

    public static function toSnakeCase(string $string): string
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $string, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('_', $ret);
    }

    public static function toValue(string $string): string
    {
        $string = str_replace(' ', '_', $string);
        $string = strtolower($string);
        return $string;
    }

    public static function replaceAtPos(string $haystack, string $needle, string $replacement, int $position): string
    {
        return substr_replace($haystack, $replacement, $position, strlen($needle));
    }

    public static function startsWith(string $haystack, string $needle): bool
    {
        return $needle === '' || strrpos($haystack, $needle, -strlen($haystack)) !== false;
    }

    public static function endsWith(string $haystack, string $needle): bool
    {
        return $needle === '' || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
    }

    public static function registerOption(string $group, string $name, string $type)
    {
        if (!isset(self::$options[$group])) {
            self::$options[$group] = [];
        }
        self::$options[$group][] = [
            'name' => $name,
            'type' => $type,
        ];
        register_setting($group, $name, ['type' => $type]);
    }

    public static function getOption($string)
    {
    }
}
