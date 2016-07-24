<?php
function mp_ssv_get_local_time_string($time_string)
{
    global $wpdb;
    $time = DateTime::createFromFormat('H:i', $time_string);
    $table_name = $wpdb->prefix . "mp_ssv_event_timezone";
    $mp_ssv_event_time_zone = get_option('mp_ssv_event_time_zone');
    $result = $wpdb->get_row("SELECT * FROM $table_name WHERE `id` = $mp_ssv_event_time_zone");
    $gmt_adjustment = $result->gmt_adjustment;
    if ($gmt_adjustment[0] == '+') {
        $time->sub(
            new DateInterval(
                'PT' . $gmt_adjustment[1] . $gmt_adjustment[2] . 'H' . $gmt_adjustment[4] . $gmt_adjustment[5] . 'M'
            )
        );
    } else {
        $time->add(
            new DateInterval(
                'PT' . $gmt_adjustment[1] . $gmt_adjustment[2] . 'H' . $gmt_adjustment[4] . $gmt_adjustment[5] . 'M'
            )
        );
    }

    return $time->format('H:i');
}

function mp_ssv_redirect($location)
{
    $redirect_script = '<script type="text/javascript">';
    $redirect_script .= 'window.location = "' . $location . '"';
    $redirect_script .= '</script>';
    echo $redirect_script;
}

function mp_ssv_print($variable, $die = false)
{
    highlight_string('<?php ' . var_export($variable, true) . ' ?>');
    if ($die) {
        die();
    }
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
        <label for="<?php echo $object_name; ?>"><?php echo $title; ?></label>
        <br/>
        <?php
    } else {
        $object_name = $id;
    }
    ?>
    <input type="<?php echo $type; ?>" id="<?php echo $object_name; ?>" name="<?php echo $object_name; ?>"
           value="<?php echo $value; ?>" <?php foreach ($args as $arg) {
        echo $arg;
    } ?>/>
    <?php
    return trim(preg_replace('/\s+/', ' ', ob_get_clean()));
}

function mp_ssv_get_select($title, $id, $selected, $options, $args = array(), $allow_custom = false,
    $input_type_custom = null
) {
    ob_start();
    if ($allow_custom) {
        $options[] = "Custom";
    }
    $object_name = $id . "_" . strtolower(str_replace(" ", "_", $title));
    $object_custom_name = $id . "_" . strtolower(str_replace(" ", "_", $title)) . "_custom";
    ?>
    <label for="<?php echo $object_name; ?>"><?php echo $title; ?></label>
    <select id="<?php echo $object_name; ?>" name="<?php echo $object_name; ?>" <?php foreach ($args as $arg) {
        echo $arg;
    } ?>>
        <?php foreach ($options as $option) { ?>
            <option value="<?php echo strtolower(str_replace(" ", "_", $option)); ?>" <?php if ($selected == strtolower(
                    str_replace(" ", "_", $option)
                )
            ) {
                echo "selected";
            } ?>><?php echo $option; ?></option>
        <?php } ?>
    </select>
    <?php if ($allow_custom && $selected == "custom") { ?>
        <div>
            <!--suppress HtmlFormInputWithoutLabel -->
            <input type="text" id="<?php echo $object_custom_name; ?>" name="<?php echo $object_custom_name; ?>"
                   value="<?php echo $input_type_custom; ?>"/>
        </div>
    <?php }

    return trim(preg_replace('/\s+/', ' ', ob_get_clean()));
}

function mp_ssv_get_checkbox($title, $id, $value, $args = array())
{
    ob_start();
    $object_name = $id . "_" . strtolower(str_replace(" ", "_", $title));
    ?>
    <br/><input type="checkbox" id="<?php echo $object_name; ?>" name="<?php echo $object_name; ?>"
                value="yes" <?php if ($value == "yes") : echo "checked"; endif; ?><?php foreach ($args as $arg):
    echo $arg; endforeach; ?>/>
    <label for="<?php echo $object_name; ?>"><?php echo $title; ?></label>
    <?php
    return trim(preg_replace('/\s+/', ' ', ob_get_clean()));
}

function mp_ssv_get_options($parent_id, $options, $type, $args = array())
{
    ob_start();
    ?>
    <ul id="<?php echo $parent_id; ?>_options" style="margin: 0;">Options<br/><?php foreach ($options as $option) :
            echo mp_ssv_get_option($parent_id, $option, $args); endforeach; ?>
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

function mp_ssv_get_option($parent_id, $option, $args = array())
{
    ob_start();
    $object_name = $parent_id . "_option" . $option["id"];
    if ($option["type"] == "role") {
        echo "<li>" . mp_ssv_get_role_select($object_name, "option", $option["value"], false) . "</li>";
    } else {
        ?>
        <li>
            <!--suppress HtmlFormInputWithoutLabel -->
            <input type="text" id="<?php echo $object_name; ?>_option" name="<?php echo $object_name; ?>_option"
                   value="<?php echo $option["value"]; ?>" <?php foreach ($args as $arg) : echo $arg; endforeach; ?>/>
        </li>
        <?php
    }

    return trim(preg_replace('/\s+/', ' ', ob_get_clean()));
}

function mp_ssv_get_hidden($id, $name, $value)
{
    ob_start();
    $object_name = $id . "_" . $name;
    ?><input type="hidden" id="<?php echo $object_name; ?>"
             name="<?php echo $object_name; ?>" value="<?php echo $value; ?>"<?php
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
        <label for="<?php echo $object_name; ?>"><?php echo $title; ?></label><br/>
        <?php
    }
    ?>
    <select id="<?php echo $object_name; ?>" name="<?php echo $object_name; ?>" <?php foreach ($args as $arg) :
        echo $arg; endforeach; ?>>
        <option value=""></option><?php echo $roles_options; ?>
    </select>
    <?php
    return trim(preg_replace('/\s+/', ' ', ob_get_clean()));
}

?>