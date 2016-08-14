<?php
if (!defined('ABSPATH')) {
    exit;
}
/**
 * This function can be called from anywhere and will redirect the page to the given location.
 *
 * @param string $location is the url where the page should be redirected to.
 */
function mp_ssv_redirect($location)
{
    $redirect_script = '<script type="text/javascript">';
    $redirect_script .= 'window.location = "' . $location . '"';
    $redirect_script .= '</script>';
    echo $redirect_script;
}

/**
 * This function is for development purposes only and lets the developer print a variable in the PHP formatting to inspect what the variable is set to.
 *
 * @param mixed $variable any variable that you want to be printed.
 * @param bool  $die      set true if you want to call die() after the print. $die is ignored if $return is true.
 * @param bool  $return   set true if you want to return the print as string.
 * @param bool  $newline  set false if you don't want to print a newline at the end of the print.
 *
 * @return mixed|null|string returns the print in string if $return is true, returns null if $return is false, and doesn't return if $die is true.
 */
function mp_ssv_print($variable, $die = false, $return = false, $newline = true)
{
    $print = highlight_string("<?php " . var_export($variable, true), true);
    $print = trim($print);
    $print = preg_replace("|^\\<code\\>\\<span style\\=\"color\\: #[a-fA-F0-9]{0,6}\"\\>|", "", $print, 1);  // remove prefix
    $print = preg_replace("|\\</code\\>\$|", "", $print, 1);
    $print = trim($print);
    $print = preg_replace("|\\</span\\>\$|", "", $print, 1);
    $print = trim($print);
    $print = preg_replace("|^(\\<span style\\=\"color\\: #[a-fA-F0-9]{0,6}\"\\>)(&lt;\\?php&nbsp;)(.*?)(\\</span\\>)|", "\$1\$3\$4", $print);
    if ($return) {
        return $print;
    } else {
        echo $print;
        if ($newline) {
            echo '<br/>';
        }
    }

    if ($die) {
        die();
    }
    return null;
}

function mp_ssv_get_tr($id, $content, $visible = true)
{
    ob_start();
    if ($visible) {
        ?>
        <tr id="<?php echo $id; ?>"
            style="vertical-align: top; border-bottom: 1px solid gray; border-top: 1px solid gray;">
            <?php
            echo $content;
            ?>
        </tr>
        <?php
    } else {
        ?>
        <tr id="<?php echo $id; ?>" style="display: none;">
            <?php
            echo $content;
            ?>
        </tr>
        <?php
    }

    return trim(preg_replace('/\s+/', ' ', ob_get_clean()));
}

function mp_ssv_get_td($content)
{
    ob_start();
    ?>
    <td style="vertical-align: middle; cursor: move;"><?php echo $content; ?></td>
    <?php
    return trim(preg_replace('/\s+/', ' ', ob_get_clean()));
}

function mp_ssv_get_draggable_icon()
{
    ob_start();
    ?><img style="padding-right: 15px; margin: 10px 0;"
           src="<?php echo plugins_url('images/icon-menu.svg', __FILE__); ?>"/><?php
    return trim(preg_replace('/\s+/', ' ', ob_get_clean()));
}

function mp_ssv_get_text_input($title, $id, $value, $type = "text", $args = array())
{
    ob_start();
    if ($title != "") {
        $object_name = $id . "_" . strtolower(str_replace(" ", "_", $title));
        ?>
        <label for="<?php echo esc_html($object_name); ?>"><?php echo esc_html($title); ?></label>
        <br/>
        <?php
    } else {
        $object_name = $id;
    }
    ?>
    <input type="<?php echo esc_html($type); ?>" id="<?php echo esc_html($object_name); ?>" name="<?php echo esc_html($object_name); ?>" style="width: 100%;"
           value="<?php echo esc_html($value); ?>" <?php foreach ($args as $arg) {
        echo esc_html($arg);
    } ?>/>
    <?php
    return trim(preg_replace('/\s+/', ' ', ob_get_clean()));
}

function mp_ssv_get_select($title, $id, $selected, $options, $args = array(), $allow_custom = false, $input_type_custom = null, $title_on_newline = true)
{
    ob_start();
    if ($allow_custom) {
        $options[] = "Custom";
    }
    $object_name = $id . "_" . strtolower(str_replace(" ", "_", $title));
    $object_custom_name = $id . "_" . strtolower(str_replace(" ", "_", $title)) . "_custom";
    ?>
    <label for="<?php echo esc_html($object_name); ?>"><?php echo esc_html($title); ?></label>
    <?php
    if ($title_on_newline) {
        echo '<br/>';
    }
    ?>
    <select id="<?php echo esc_html($object_name); ?>" name="<?php echo esc_html($object_name); ?>" style="width: 100%;" <?php foreach ($args as $arg) {
        echo esc_html($arg);
    } ?>>
        <?php foreach ($options as $option) { ?>
            <option value="<?php echo esc_html(strtolower(str_replace(" ", "_", $option))); ?>" <?php if ($selected == esc_html(strtolower(str_replace(" ", "_", $option)))) {
                echo "selected";
            } ?>><?php echo $option; ?></option>
        <?php } ?>
    </select>
    <?php if ($allow_custom && $selected == "custom") { ?>
    <div>
        <!--suppress HtmlFormInputWithoutLabel -->
        <input type="text" id="<?php echo esc_html($object_custom_name); ?>" name="<?php echo esc_html($object_custom_name); ?>" style="width: 100%;"
               value="<?php echo esc_html($input_type_custom); ?>" required/>
    </div>
<?php }

    return trim(preg_replace('/\s+/', ' ', ob_get_clean()));
}

function mp_ssv_get_checkbox($title, $id, $value, $args = array(), $on_new_line = false)
{
    ob_start();
    $object_name = $id . "_" . strtolower(str_replace(" ", "_", $title));
    if ($on_new_line) {
        ?><label for="<?php echo esc_html($object_name); ?>"><?php echo esc_html($title); ?></label><?php
    }
    ?>
    <br/><input type="checkbox" id="<?php echo esc_html($object_name); ?>" name="<?php echo esc_html($object_name); ?>"
                value="yes" <?php if ($value == "yes") : echo "checked"; endif; ?><?php foreach ($args as $arg):
    echo esc_html($arg); endforeach; ?>/><?php
    if (!$on_new_line) {
        ?><label for="<?php echo esc_html($object_name); ?>"><?php echo esc_html($title); ?></label><?php
    }
    return trim(preg_replace('/\s+/', ' ', ob_get_clean()));
}

function mp_ssv_get_options($parent_id, $options, $type, $args = array())
{
    ob_start();
    ?>
    <ul id="<?php echo esc_html($parent_id); ?>_options" style="margin: 0;">Options<br/><?php foreach ($options as $option) :
            echo mp_ssv_get_option($parent_id, $option, $args); endforeach; ?>
        <li>
            <!--suppress JSUnresolvedFunction -->
            <button type="button" id="<?php echo esc_html($parent_id); ?>_add_option"
                    onclick="add_<?php echo esc_html($type); ?>_option(<?php echo esc_html($parent_id); ?>)">Add Option
            </button>
        </li>
    </ul>
    <?php
    return trim(preg_replace('/\s+/', ' ', ob_get_clean()));
}

function mp_ssv_get_option($parent_id, $option, $args = array())
{
    ob_start();
    $object_name = $parent_id . "_option" . $option["id"];
    if ($option["type"] == "role") {
        echo "<li>" . esc_html(mp_ssv_get_role_select($object_name, "option", $option["value"], false)) . "</li>";
    } else {
        ?>
        <li>
            <!--suppress HtmlFormInputWithoutLabel -->
            <input type="text" id="<?php echo esc_html($object_name); ?>_option" name="<?php echo esc_html($object_name); ?>_option" style="width: 100%;"
                   value="<?php echo esc_html($option["value"]); ?>" <?php foreach ($args as $arg) : echo esc_html($arg); endforeach; ?>/>
        </li>
        <?php
    }

    return trim(preg_replace('/\s+/', ' ', ob_get_clean()));
}

function mp_ssv_get_hidden($id, $name, $value)
{
    ob_start();
    $object_name = $id . "_" . $name;
    ?><input type="hidden" id="<?php echo esc_html($object_name); ?>"
             name="<?php echo esc_html($object_name); ?>" value="<?php echo esc_html($value); ?>"<?php
    return trim(preg_replace('/\s+/', ' ', ob_get_clean()));
}

function mp_ssv_get_role_select($id, $title, $value, $with_title = true, $args = array())
{
    $object_name = $id . "_" . strtolower(str_replace(" ", "_", $title));
    ob_start();
    wp_dropdown_roles($value);
    $roles_options = trim(preg_replace('/\s+/', ' ', ob_get_clean()));
    $roles_options = trim(preg_replace('/\s\s+/', ' ', $roles_options));
    $roles_options = str_replace("'", '"', $roles_options);
    ob_start();
    if ($with_title) {
        ?>
        <label for="<?php echo esc_html($object_name); ?>"><?php echo esc_html($title); ?></label><br/>
        <?php
    }
    ?>
    <select id="<?php echo esc_html($object_name); ?>" name="<?php echo esc_html($object_name); ?>" style="width: 100%;" <?php foreach ($args as $arg) :
        echo esc_html($arg); endforeach; ?>>
        <option value=""></option><?php echo esc_html($roles_options); ?>
    </select>
    <?php
    return trim(preg_replace('/\s+/', ' ', ob_get_clean()));
}

function mp_ssv_starts_with($haystack, $needle)
{
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
}

function mp_ssv_ends_with($haystack, $needle)
{
    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
}

?>