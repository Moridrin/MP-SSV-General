/**
 * Created by moridrin on 4-1-17.
 */

var scripts = document.getElementsByTagName("script");
var pluginBaseURL = scripts[scripts.length - 1].src.split('/').slice(0, -3).join('/');

function mp_ssv_add_field(containerID, fieldID, namePrefix, $customFields, $trStyle, $tdStyle) {
    // var $ = jQuery.noConflict();
    var container = document.getElementById(containerID);

    //<tr>
    var tr = document.createElement("tr");
    tr.setAttribute("style", "border-bottom: 1px solid gray; border-top: 1px solid gray;");
    //<td>
    var td = document.createElement("td");
    // td.setAttribute("valign", "middle");
    //<br/>
    var br = '<br/>';
    var div = document.createElement("div");
    div.innerHTML = br;
    br = div.childNodes[0];

    // Draggable Icon
    var draggableIcon = document.createElement("img");
    draggableIcon.setAttribute("src", pluginBaseURL + '/general/images/icon-menu.svg');
    draggableIcon.setAttribute("style", "padding-right: 15px; margin: 10px 0;");
    var draggableIconTD = td.cloneNode(false);
    draggableIconTD.setAttribute("style", "vertical-align: middle; cursor: move;");
    draggableIconTD.appendChild(draggableIcon);
    tr.appendChild(draggableIconTD);

    // Field Title
    var fieldTitle = document.createElement("input");
    fieldTitle.setAttribute("id", fieldID + "_field_title");
    fieldTitle.setAttribute("name", namePrefix + "_" + fieldID + "_field_title");
    fieldTitle.setAttribute("style", "width: 100%;");
    fieldTitle.setAttribute("required", "required");
    var fieldTitleLabel = document.createElement("label");
    fieldTitleLabel.setAttribute("style", "white-space: nowrap;");
    fieldTitleLabel.setAttribute("for", fieldID + "_field_title");
    fieldTitleLabel.innerHTML = "Field Title";
    var fieldTitleTD = td.cloneNode(false);
    fieldTitleTD.appendChild(fieldTitleLabel);
    fieldTitleTD.appendChild(br.cloneNode(false));
    fieldTitleTD.appendChild(fieldTitle);
    tr.appendChild(fieldTitleTD);

    // Field Type
    var fieldType = mp_ssv_create_select(namePrefix + "_" + fieldID + "_field_type", ["Tab", "Header", "Input", "Label"]);
    fieldType.setAttribute("style", "width: 100%;");
    var fieldTypeLabel = document.createElement("label");
    fieldTypeLabel.setAttribute("style", "white-space: nowrap;");
    fieldTypeLabel.setAttribute("for", fieldID + "_field_type");
    fieldTypeLabel.innerHTML = "Field Type";
    var fieldTypeTD = td.cloneNode(false);
    fieldTypeTD.appendChild(fieldTypeLabel);
    fieldTypeTD.appendChild(br.cloneNode(false));
    fieldTypeTD.appendChild(fieldType);
    tr.appendChild(fieldTypeTD);

    // Input Type
    var inputType = mp_ssv_create_select(namePrefix + "_" + fieldID + "_input_type", ["Text", "Text Select", "Role Select", "Text Checkbox", "Role Checkbox", "Image", "Custom"]);
    inputType.setAttribute("style", "width: 100%;");
    var inputTypeLabel = document.createElement("label");
    inputTypeLabel.setAttribute("style", "white-space: nowrap;");
    inputTypeLabel.setAttribute("for", fieldID + "_input_type");
    inputTypeLabel.innerHTML = "Input Type";
    var inputTypeTD = td.cloneNode(false);
    inputTypeTD.appendChild(inputTypeLabel);
    inputTypeTD.appendChild(br.cloneNode(false));
    inputTypeTD.appendChild(inputType);
    tr.appendChild(inputTypeTD);

    // Name
    var name = document.createElement("input");
    name.setAttribute("id", fieldID + "_name");
    name.setAttribute("name", namePrefix + "_" + fieldID + "_name");
    name.setAttribute("style", "width: 100%;");
    name.setAttribute("required", "required");
    var nameLabel = document.createElement("label");
    nameLabel.setAttribute("style", "white-space: nowrap;");
    nameLabel.setAttribute("for", fieldID + "_name");
    nameLabel.innerHTML = "Name";
    var nameTD = td.cloneNode(false);
    nameTD.appendChild(nameLabel);
    nameTD.appendChild(br.cloneNode(false));
    nameTD.appendChild(name);
    tr.appendChild(nameTD);

    // Required
    var required = document.createElement("input");
    required.setAttribute("type", "checkbox");
    required.setAttribute("id", fieldID + "_required");
    required.setAttribute("name", namePrefix + "_" + fieldID + "_required");
    required.setAttribute("value", "true");
    var requiredReset = document.createElement("input");
    requiredReset.setAttribute("type", "hidden");
    requiredReset.setAttribute("id", fieldID + "_required");
    requiredReset.setAttribute("name", namePrefix + "_" + fieldID + "_required");
    requiredReset.setAttribute("value", "false");
    var requiredLabel = document.createElement("label");
    requiredLabel.setAttribute("style", "white-space: nowrap;");
    requiredLabel.setAttribute("for", fieldID + "_required");
    requiredLabel.innerHTML = "Required";
    var requiredTD = td.cloneNode(false);
    requiredTD.appendChild(requiredLabel);
    requiredTD.appendChild(br.cloneNode(false));
    requiredTD.appendChild(requiredReset);
    requiredTD.appendChild(required);
    tr.appendChild(requiredTD);
    container.appendChild(tr);

    // Display
    var display = mp_ssv_create_select(namePrefix + "_" + fieldID + "_input_type", ["Normal", "ReadOnly", "Disabled"]);
    display.setAttribute("style", "width: 100%;");
    var displayLabel = document.createElement("label");
    displayLabel.setAttribute("style", "white-space: nowrap;");
    displayLabel.setAttribute("for", fieldID + "_input_type");
    displayLabel.innerHTML = "Input Type";
    var displayTD = td.cloneNode(false);
    displayTD.appendChild(displayLabel);
    displayTD.appendChild(br.cloneNode(false));
    displayTD.appendChild(display);
    tr.appendChild(displayTD);

    // Default Value
    var defaultValue = document.createElement("input");
    defaultValue.setAttribute("id", fieldID + "_default_value");
    defaultValue.setAttribute("name", namePrefix + "_" + fieldID + "_default_value");
    defaultValue.setAttribute("style", "width: 100%;");
    var defaultValueLabel = document.createElement("label");
    defaultValueLabel.setAttribute("style", "white-space: nowrap;");
    defaultValueLabel.setAttribute("for", fieldID + "_default_value");
    defaultValueLabel.innerHTML = "Default Value";
    var defaultValueTD = td.cloneNode(false);
    defaultValueTD.appendChild(defaultValueLabel);
    defaultValueTD.appendChild(br.cloneNode(false));
    defaultValueTD.appendChild(defaultValue);
    tr.appendChild(defaultValueTD);

    // Placeholder
    var placeholder = document.createElement("input");
    placeholder.setAttribute("id", fieldID + "_placeholder");
    placeholder.setAttribute("name", namePrefix + "_" + fieldID + "_placeholder");
    placeholder.setAttribute("style", "width: 100%;");
    var placeholderLabel = document.createElement("label");
    placeholderLabel.setAttribute("style", "white-space: nowrap;");
    placeholderLabel.setAttribute("for", fieldID + "_placeholder");
    placeholderLabel.innerHTML = "Placeholder";
    var placeholderTD = td.cloneNode(false);
    placeholderTD.appendChild(placeholderLabel);
    placeholderTD.appendChild(br.cloneNode(false));
    placeholderTD.appendChild(placeholder);
    tr.appendChild(placeholderTD);

    // Class
    var classField = document.createElement("input");
    classField.setAttribute("id", fieldID + "_class");
    classField.setAttribute("name", namePrefix + "_" + fieldID + "_class");
    classField.setAttribute("style", "width: 100%;");
    var classLabel = document.createElement("label");
    classLabel.setAttribute("style", "white-space: nowrap;");
    classLabel.setAttribute("for", fieldID + "_class");
    classLabel.innerHTML = "Class";
    var classTD = td.cloneNode(false);
    classTD.appendChild(classLabel);
    classTD.appendChild(br.cloneNode(false));
    classTD.appendChild(classField);
    tr.appendChild(classTD);

    // Style
    var style = document.createElement("input");
    style.setAttribute("id", fieldID + "_style");
    style.setAttribute("name", namePrefix + "_" + fieldID + "_style");
    style.setAttribute("style", "width: 100%;");
    var styleLabel = document.createElement("label");
    styleLabel.setAttribute("style", "white-space: nowrap;");
    styleLabel.setAttribute("for", fieldID + "_style");
    styleLabel.innerHTML = "Style";
    var styleTD = td.cloneNode(false);
    styleTD.appendChild(styleLabel);
    styleTD.appendChild(br.cloneNode(false));
    styleTD.appendChild(style);
    tr.appendChild(styleTD);
}
/*
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

function mp_ssv_create_select(fieldName, options) {
    var select = document.createElement("select");
    select.setAttribute("id", fieldName);
    select.setAttribute("name", fieldName);

    for (i = 0; i < options.length; i++) {
        var option = document.createElement("option");
        option.setAttribute("value", options[i].toLowerCase());
        option.innerHTML = options[i];
        select.appendChild(option);
    }

    return select;
}
