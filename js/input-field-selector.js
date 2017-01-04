/**
 * Created by moridrin on 4-1-17.
 */

var scripts = document.getElementsByTagName("script");
var pluginBaseURL = scripts[scripts.length-1].src.split('/').slice(0,-3).join('/');

var $ = jQuery.noConflict();
var id;
//TODO Set ID to max Database ID.
var tdBase = document.createElement("td");
tdBase.setAttribute("style", "vertical-align: middle; cursor: move;");

var draggableIcon = document.createElement("img");
draggableIcon.setAttribute("src", pluginBaseURL + '/general/images/icon-menu.svg');
draggableIcon.setAttribute("style", "padding-right: 15px; margin: 10px 0;");
var tdDraggableIcon = tdBase.cloneNode();
tdDraggableIcon.innerHTML = draggableIcon;
alert(tdDraggableIcon);

/*
$new_field_content = ssv_get_td(ssv_get_draggable_icon());
$new_field_content .= ssv_get_hidden('\' + id + \'', "Registration Page", $_GET['tab'] == 'register_page' ? 'yes' : 'no');
$new_field_content .= ssv_get_hidden('\' + id + \'', "Profile Type", $profileType);
$new_field_content .= ssv_get_td(ssv_get_text_input("Field Title", '\' + id + \'', "", "text", array("required"), false));
if ($_GET['tab'] == 'register_page') {
    $new_field_content .= ssv_get_td(ssv_get_select("Field Type", '\' + id + \'', "input", array("Header", "Input", "Label"), array("onchange=\"ssv_type_changed(' + id + ')\""), false, null, true, false));
} else {
    $new_field_content .= ssv_get_td(ssv_get_select("Field Type", '\' + id + \'', "input", array("Tab", "Header", "Input", "Label"), array("onchange=\"ssv_type_changed(' + id + ')\""), false, null, true, false));
}
$new_field_content .= ssv_get_td(ssv_get_select("Input Type", '\' + id + \'', "text", array("Text", "Text Select", "Role Select", "Text Checkbox", "Role Checkbox", "Image"), array("onchange=\"ssv_input_type_changed(' + id + ')\""), true, null, true, false));
$new_field_content .= ssv_get_td(ssv_get_text_input("Name", '\' + id + \'', "", "text", array("required"), false));
$new_field_content .= ssv_get_td(ssv_get_checkbox("Required", '\' + id + \'', "no", array(), false, false));
if (get_option('ssv_frontend_members_view_display_column', true)) {
    $new_field_content .= ssv_get_td(ssv_get_select("Display", '\' + id + \'', "normal", array("Normal", "ReadOnly", "Disabled"), array(), false, null, true, false));
} else {
    $new_field_content .= ssv_get_hidden('\' + id + \'', "Display", '');
}
if (get_option('ssv_frontend_members_view_default_column', true)) {
    $new_field_content .= ssv_get_td(ssv_get_text_input("Default Value", '\' + id + \'', '', 'text', array(), false));
} else {
    $new_field_content .= ssv_get_hidden('\' + id + \'', "Default Value", '');
}
if (get_option('ssv_frontend_members_view_placeholder_column', true)) {
    $new_field_content .= ssv_get_td(ssv_get_text_input("Placeholder", '\' + id + \'', '', 'text', array(), false));
} else {
    $new_field_content .= ssv_get_hidden('\' + id + \'', "Placeholder", '');
}
if (get_option('ssv_frontend_members_view_class_column', true)) {
    $new_field_content .= ssv_get_td(ssv_get_text_input('Field Class', '\' + id + \'', '', 'text', array(), false));
} else {
    $new_field_content .= ssv_get_hidden('\' + id + \'', "Field Class", '');
}
if (get_option('ssv_frontend_members_view_style_column', true)) {
    $new_field_content .= ssv_get_td(ssv_get_text_input('Field Style', '\' + id + \'', '', 'text', array(), false));
} else {
    $new_field_content .= ssv_get_hidden('\' + id + \'', "Field Style", '');
}
$new_field = ssv_get_tr('\' + id + \'', $new_field_content);
    ?>
function ssv_add_new_field() {
    id++;
    $("#fields_container").find("> tbody:last-child").append('<?php echo $new_field ?>');
}
*/

function clone(obj) {
    if (null == obj || "object" != typeof obj) return obj;
    var copy = obj.constructor();
    for (var attr in obj) {
        if (obj.hasOwnProperty(attr)) copy[attr] = obj[attr];
    }
    return copy;
}