<?php
if (!defined('ABSPATH')) {
    exit;
}

#region Functions that should be in PHP
function mp_ssv_replace_at_pos($haystack, $needle, $replacement, $position)
{
    return substr_replace($haystack, $replacement, $position, strlen($needle));
}

function mp_ssv_starts_with($haystack, $needle)
{
    return $needle === '' || strrpos($haystack, $needle, -strlen($haystack)) !== false;
}

function mp_ssv_ends_with($haystack, $needle)
{
    return $needle === '' || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
}
#endregion

#region fields [disabled]
/*
function ssv_get_tr($id, $content, $visible = true)
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

function ssv_get_td($content, $colspan = 1)
{
    ob_start();
    ?>
    <td style="vertical-align: middle; cursor: move;" colspan="<?php echo $colspan; ?>"><?php echo $content; ?></td>
    <?php
    return trim(preg_replace('/\s+/', ' ', ob_get_clean()));
}

function ssv_get_draggable_icon()
{
    ob_start();
    ?><img src="<?php echo plugins_url('images/icon-menu.svg', __FILE__); ?>" style="padding-right: 15px; margin: 10px 0;"/><?php
    return trim(preg_replace('/\s+/', ' ', ob_get_clean()));
}

function ssv_get_text_input($title, $id, $value, $type = "text", $args = array(), $esc_html = true)
{
    $title = $esc_html ? esc_html($title) : $title;
    $id    = $esc_html ? esc_html($id) : $id;
    $value = $esc_html ? esc_html($value) : $value;
    $type  = $esc_html ? esc_html($type) : $type;
    ob_start();
    if ($title != '') {
        $object_name = $id . "_" . strtolower(str_replace(" ", "_", $title));
        ?>
        <label for="<?php echo $object_name; ?>"><?php echo $title; ?></label>
        <br/>
        <?php
    } else {
        $object_name = $id;
    }
    ?>
    <input type="<?php echo $type; ?>" id="<?php echo $object_name; ?>" name="<?php echo $object_name; ?>" style="width: 100%;"
           value="<?php echo $value; ?>" <?php foreach ($args as $arg) {
        echo $arg . ' ';
    } ?>/>
    <?php
    return trim(preg_replace('/\s+/', ' ', ob_get_clean()));
}

function ssv_get_text_area($title, $id, $value, $type = "text", $args = array(), $esc_html = true)
{
    $title = $esc_html ? esc_html($title) : $title;
    $id    = $esc_html ? esc_html($id) : $id;
    $value = $esc_html ? esc_html($value) : $value;
    $type  = $esc_html ? esc_html($type) : $type;
    ob_start();
    if ($title != '') {
        $object_name = $id . "_" . strtolower(str_replace(" ", "_", $title));
        ?>
        <label for="<?php echo $object_name; ?>"><?php echo $title; ?></label>
        <br/>
        <?php
    } else {
        $object_name = $id;
    }
    ?>
    <textarea type="<?php echo $type; ?>" id="<?php echo $object_name; ?>" name="<?php echo $object_name; ?>" style="width: 100%;"
        <?php foreach ($args as $arg) {
            echo $arg . ' ';
        } ?>><?php echo $value; ?></textarea>
    <?php
    return trim(preg_replace('/\s+/', ' ', ob_get_clean()));
}

function ssv_get_select($title, $id, $selected, $options, $args = array(), $allow_custom = false, $input_type_custom = null, $title_on_newline = true, $esc_html = true)
{
    $title = $esc_html ? esc_html($title) : $title;
    $id    = $esc_html ? esc_html($id) : $id;
    ob_start();
    if ($allow_custom) {
        $options[] = "Custom";
    }
    $object_name        = strtolower(str_replace(" ", "_", $title));
    $object_custom_name = strtolower(str_replace(" ", "_", $title)) . "_custom";
    if ($id != null) {
        $object_name        = $id . '_' . $object_name;
        $object_custom_name = $id . "_" . $object_custom_name;
    }
    if ($id != null && !empty($title)): ?>
        <label for="<?php echo $object_name; ?>"><?php echo $title; ?></label>
    <?php endif;
    if ($title_on_newline) {
        echo '<br/>';
    }
    ?>
    <select id="<?php echo $object_name; ?>" name="<?php echo $object_name; ?>" style="width: 100%;" <?php foreach ($args as $arg) {
        echo $arg . ' ';
    } ?>>
        <?php foreach ($options as $option) { ?>
            <option value="<?php echo strtolower(str_replace(" ", "_", $option)); ?>" <?php if ($selected == strtolower(str_replace(" ", "_", $option))) {
                echo "selected";
            } ?>><?php echo $esc_html ? esc_html($option) : $option; ?></option>
        <?php } ?>
    </select>
    <?php if ($allow_custom && $selected == "custom"): ?>
    <div>
        <!--suppress HtmlFormInputWithoutLabel -->
        <input type="text" id="<?php echo $object_custom_name; ?>" name="<?php echo $object_custom_name; ?>" style="width: 100%;"
               value="<?php echo $input_type_custom; ?>" required/>
    </div>
<?php elseif ($allow_custom && $selected == "date"): ?>
    <div>
        <select id="<?= $object_custom_name; ?>" name="<?= $object_custom_name; ?>" style="width: 100%;" title="Date Time Type">
            <option value="datetime" <?= $input_type_custom == 'datetime' ? 'selected' : '' ?>>DateTime</option>
            <option value="date" <?= $input_type_custom == 'date' ? 'selected' : '' ?>>Date</option>
            <option value="time" <?= $input_type_custom == 'time' ? 'selected' : '' ?>>Time</option>
        </select>
    </div>
<?php endif;

    return trim(preg_replace('/\s+/', ' ', ob_get_clean()));
}

function ssv_get_checkbox($title, $id, $value, $args = array(), $on_new_line = false, $esc_html = true)
{
    $title = $esc_html ? esc_html($title) : $title;
    $id    = $esc_html ? esc_html($id) : $id;
    $value = $esc_html ? esc_html($value) : $value;
    ob_start();
    $object_name = $id . "_" . strtolower(str_replace(" ", "_", $title));
    if ($on_new_line) {
        ?><label for="<?php echo $object_name; ?>"><?php echo $title; ?></label><?php
    }
    ?>
    <br/><input type="checkbox" id="<?php echo $object_name; ?>" name="<?php echo $object_name; ?>"
                value="yes" <?php if ($value == "yes") : echo "checked"; endif; ?><?php foreach ($args as $arg):
    echo $esc_html ? esc_html($arg) : $arg; endforeach; ?>/><?php
    if (!$on_new_line) {
        ?><label for="<?php echo $object_name; ?>"><?php echo $title; ?></label><?php
    }
    return trim(preg_replace('/\s+/', ' ', ob_get_clean()));
}

function ssv_get_options($parent_id, $options, $type, $args = array(), $esc_html = true)
{
    $parent_id = $esc_html ? esc_html($parent_id) : $parent_id;
    $type      = $esc_html ? esc_html($type) : $type;
    ob_start();
    ?>
    <ul id="<?php echo $parent_id; ?>_options" style="margin: 0;">
        Options<br/>
        <?php foreach ($options as $option) :
            echo ssv_get_option($parent_id, $option, $args, $esc_html);
        endforeach; ?>
        <li>
            <!--suppress JSUnresolvedFunction -->
            <button type="button" id="<?php echo $parent_id; ?>_add_option"
                    onclick="add_<?php echo $type; ?>_option(<?php echo $parent_id; ?>)">Add Option
            </button>
        </li>
    </ul>
    <?php
    return trim(preg_replace('/\s+/', ' ', ob_get_clean()));
}

function ssv_get_option($parent_id, $option, $args = array(), $esc_html = true)
{
    $parent_id = $esc_html ? esc_html($parent_id) : $parent_id;
    ob_start();
    $object_name = $parent_id . "_option" . $option["id"];
    $object_name = $esc_html ? esc_html($object_name) : $object_name;
    if ($option["type"] == "role") {
        echo "<li>" . ssv_get_role_select($object_name, "option", $option["value"], false, array(), $esc_html) . "</li>";
    } else {
        ?>
        <li>
            <!--suppress HtmlFormInputWithoutLabel -->
            <input type="text" id="<?php echo $object_name; ?>_option" name="<?php echo $object_name; ?>_option" style="width: 100%;"
                   value="<?php echo $esc_html ? esc_html($option["value"]) : $option["value"]; ?>" <?php foreach ($args as $arg) : echo $esc_html ? esc_html($arg) : $arg; endforeach; ?>/>
        </li>
        <?php
    }

    return trim(preg_replace('/\s+/', ' ', ob_get_clean()));
}

function ssv_get_hidden($id, $name, $value, $esc_html = true)
{
    $name        = $esc_html ? esc_html($name) : $name;
    $value       = $esc_html ? esc_html($value) : $value;
    $object_name = $id == null ? $name : $id . "_" . strtolower(str_replace(" ", "_", $name));
    ob_start();
    ?><input type="hidden" id="<?php echo $id; ?>" name="<?php echo $object_name; ?>" value="<?php echo $value; ?>"><?php
    return trim(preg_replace('/\s+/', ' ', ob_get_clean()));
}

function ssv_get_role_select($id, $title, $value, $with_title = true, $args = array(), $esc_html = true)
{
    $id          = $esc_html ? esc_html($id) : $id;
    $title       = $esc_html ? esc_html($title) : $title;
    $value       = $esc_html ? esc_html($value) : $value;
    $object_name = $id . "_" . strtolower(str_replace(" ", "_", $title));
    $object_name = $esc_html ? esc_html($object_name) : $object_name;
    ob_start();
    wp_dropdown_roles($value);
    $roles_options = trim(preg_replace('/\s+/', ' ', ob_get_clean()));
    $roles_options = trim(preg_replace('/\s\s+/', ' ', $roles_options));
    $roles_options = str_replace("'", '"', $roles_options);
    ob_start();
    if ($with_title) {
        ?>
        <label for="<?php echo $object_name; ?>"><?php echo $title; ?></label><br/>
        <?php
    }
    ?>
    <select id="<?php echo $object_name; ?>" name="<?php echo $object_name; ?>" style="width: 100%;" <?php foreach ($args as $arg) :
        echo $esc_html ? esc_html($arg) : $arg; endforeach; ?>>
        <option value=''></option><?php echo $roles_options; ?>
    </select>
    <?php
    return trim(preg_replace('/\s+/', ' ', ob_get_clean()));
}
*/
#endregion