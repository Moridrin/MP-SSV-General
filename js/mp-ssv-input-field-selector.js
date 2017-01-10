/**
 * Created by moridrin on 4-1-17.
 */

var custom_field_fields = settings.custom_field_fields;
var scripts = document.getElementsByTagName("script");
var pluginBaseURL = scripts[scripts.length - 1].src.split('/').slice(0, -3).join('/');

function mp_ssv_add_new_tab_field(containerID, fieldID, namePrefix, values, allowTabs) {
    // alert(JSON.stringify(values));
    var container = document.getElementById(containerID);

    var fieldTitle = '';
    var fieldType = 'tab';
    if (typeof values['title'] !== 'undefined') {
        fieldTitle = values['title'];
    }

    var tr = document.createElement("tr");
    tr.setAttribute("style", "border-bottom: 1px solid gray; border-top: 1px solid gray;");

    tr.appendChild(getStart(fieldID, namePrefix));
    tr.appendChild(getFieldID(fieldID, namePrefix));
    tr.appendChild(getDraggable());
    tr.appendChild(getFieldTitle(fieldID, namePrefix, fieldTitle));
    tr.appendChild(getFieldType(fieldID, namePrefix, fieldType, allowTabs));
    tr.appendChild(getEnd(fieldID, namePrefix));

    container.appendChild(tr);
}
function mp_ssv_add_new_text_input_field(containerID, fieldID, namePrefix, values, allowTabs) {
    // alert(JSON.stringify(values));
    var container = document.getElementById(containerID);

    var fieldTitle = '';
    var fieldType = 'input';
    var inputType = 'text';
    var name = '';
    var required = '';
    var display = 'normal';
    var defaultValue = '';
    var placeholder = '';
    var classValue = '';
    var style = '';
    if (typeof values != 'undefined') {
        fieldTitle = values['title'];
        name = values['name'];
        required = values['required'];
        display = values['display'];
        defaultValue = values['default_value'];
        placeholder = values['placeholder'];
        classValue = values['class'];
        style = values['style'];
    }

    var tr = document.createElement("tr");
    tr.setAttribute("style", "border-bottom: 1px solid gray; border-top: 1px solid gray;");

    tr.appendChild(getStart(fieldID, namePrefix));
    tr.appendChild(getFieldID(fieldID, namePrefix));
    tr.appendChild(getDraggable());
    tr.appendChild(getFieldTitle(fieldID, namePrefix, fieldTitle));
    tr.appendChild(getFieldType(fieldID, namePrefix, fieldType, allowTabs));
    tr.appendChild(getInputType(fieldID, namePrefix, inputType));
    tr.appendChild(getName(fieldID, namePrefix, name));
    tr.appendChild(getRequired(fieldID, namePrefix, required));
    var showDisplay = custom_field_fields.indexOf('display') !== -1;
    tr.appendChild(getDisplay(fieldID, namePrefix, display, showDisplay));
    var showDefault = custom_field_fields.indexOf('default') !== -1;
    tr.appendChild(getDefaultValue(fieldID, namePrefix, defaultValue, showDefault));
    var showPlaceholder = custom_field_fields.indexOf('placeholder') !== -1;
    tr.appendChild(getPlaceholder(fieldID, namePrefix, placeholder, showPlaceholder));
    var showClass = custom_field_fields.indexOf('class') !== -1;
    tr.appendChild(getClass(fieldID, namePrefix, classValue, showClass));
    var showStyle = custom_field_fields.indexOf('style') !== -1;
    tr.appendChild(getStyle(fieldID, namePrefix, style, showStyle));
    tr.appendChild(getEnd(fieldID, namePrefix));

    container.appendChild(tr);
}

function getBR() {
    var br = document.createElement("div");
    br.innerHTML = '<br/>';
    return br.childNodes[0];
}

function getStart(fieldID, namePrefix) {
    var start = document.createElement("input");
    start.setAttribute("type", "hidden");
    start.setAttribute("id", fieldID + "_field_start");
    start.setAttribute("name", namePrefix + "_" + fieldID + "_field_start");
    start.setAttribute("value", "start");
    var startTD = document.createElement("td");
    startTD.appendChild(start);
    return startTD;
}
function getFieldID(fieldID, namePrefix) {
    var fieldIDElement = document.createElement("input");
    fieldIDElement.setAttribute("type", "hidden");
    fieldIDElement.setAttribute("id", fieldID + "_id");
    fieldIDElement.setAttribute("name", namePrefix + "_" + fieldID + "_id");
    fieldIDElement.setAttribute("value", fieldID);
    var fieldIDTD = document.createElement("td");
    fieldIDTD.appendChild(fieldIDElement);
    return fieldIDTD;
}
function getDraggable() {
    var draggableIcon = document.createElement("img");
    draggableIcon.setAttribute("src", pluginBaseURL + '/general/images/icon-menu.svg');
    draggableIcon.setAttribute("style", "padding-right: 15px; margin: 10px 0;");
    var draggableIconTD = document.createElement("td");
    draggableIconTD.setAttribute("style", "vertical-align: middle; cursor: move;");
    draggableIconTD.appendChild(draggableIcon);
    return draggableIconTD;
}
function getFieldTitle(fieldID, namePrefix, value) {
    var fieldTitle = document.createElement("input");
    fieldTitle.setAttribute("id", fieldID + "_title");
    fieldTitle.setAttribute("name", namePrefix + "_" + fieldID + "_title");
    fieldTitle.setAttribute("style", "width: 100%;");
    fieldTitle.setAttribute("value", value);
    var fieldTitleLabel = document.createElement("label");
    fieldTitleLabel.setAttribute("style", "white-space: nowrap;");
    fieldTitleLabel.setAttribute("for", fieldID + "_field_title");
    fieldTitleLabel.innerHTML = "Field Title";
    var fieldTitleTD = document.createElement("td");
    fieldTitleTD.appendChild(fieldTitleLabel);
    fieldTitleTD.appendChild(getBR());
    fieldTitleTD.appendChild(fieldTitle);
    return fieldTitleTD;
}
function getFieldType(fieldID, namePrefix, value, allowTabs) {
    var options;
    if (allowTabs) {
        options = ["Tab", "Header", "Input", "Label"];
    } else {
        options = ["Header", "Input", "Label"];
    }
    var fieldType = mp_ssv_create_select(namePrefix + "_" + fieldID + "_field_type", options, 'input');
    fieldType.setAttribute("style", "width: 100%;");
    fieldType.setAttribute("value", value);
    var fieldTypeLabel = document.createElement("label");
    fieldTypeLabel.setAttribute("style", "white-space: nowrap;");
    fieldTypeLabel.setAttribute("for", fieldID + "_field_type");
    fieldTypeLabel.innerHTML = "Field Type";
    var fieldTypeTD = document.createElement("td");
    fieldTypeTD.appendChild(fieldTypeLabel);
    fieldTypeTD.appendChild(getBR());
    fieldTypeTD.appendChild(fieldType);
    return fieldTypeTD;
}
function getInputType(fieldID, namePrefix, value) {
    var inputType = mp_ssv_create_select(namePrefix + "_" + fieldID + "_input_type", ["Text", "Text Select", "Role Select", "Text Checkbox", "Role Checkbox", "Image", "Custom"], 'text');
    inputType.setAttribute("style", "width: 100%;");
    inputType.setAttribute("value", value);
    var inputTypeLabel = document.createElement("label");
    inputTypeLabel.setAttribute("style", "white-space: nowrap;");
    inputTypeLabel.setAttribute("for", fieldID + "_input_type");
    inputTypeLabel.innerHTML = "Input Type";
    var inputTypeTD = document.createElement("td");
    inputTypeTD.appendChild(inputTypeLabel);
    inputTypeTD.appendChild(getBR());
    inputTypeTD.appendChild(inputType);
    return inputTypeTD;
}
function getName(fieldID, namePrefix, value) {
    var name = document.createElement("input");
    name.setAttribute("id", fieldID + "_name");
    name.setAttribute("name", namePrefix + "_" + fieldID + "_name");
    name.setAttribute("style", "width: 100%;");
    name.setAttribute("value", value);
    name.setAttribute("required", "required");
    var nameLabel = document.createElement("label");
    nameLabel.setAttribute("style", "white-space: nowrap;");
    nameLabel.setAttribute("for", fieldID + "_name");
    nameLabel.innerHTML = "Name";
    var nameTD = document.createElement("td");
    nameTD.appendChild(nameLabel);
    nameTD.appendChild(getBR());
    nameTD.appendChild(name);
    return nameTD;
}
function getRequired(fieldID, namePrefix, value) {
    var required = document.createElement("input");
    required.setAttribute("type", "checkbox");
    required.setAttribute("id", fieldID + "_required");
    required.setAttribute("name", namePrefix + "_" + fieldID + "_required");
    required.setAttribute("value", "true");
    if (value == 'true') {
        required.setAttribute("checked", "checked");
    }
    var requiredReset = document.createElement("input");
    requiredReset.setAttribute("type", "hidden");
    requiredReset.setAttribute("id", fieldID + "_required");
    requiredReset.setAttribute("name", namePrefix + "_" + fieldID + "_required");
    requiredReset.setAttribute("value", "false");
    var requiredLabel = document.createElement("label");
    requiredLabel.setAttribute("style", "white-space: nowrap;");
    requiredLabel.setAttribute("for", fieldID + "_required");
    requiredLabel.innerHTML = "Required";
    var requiredTD = document.createElement("td");
    requiredTD.appendChild(requiredLabel);
    requiredTD.appendChild(getBR());
    requiredTD.appendChild(requiredReset);
    requiredTD.appendChild(required);
    return requiredTD;
}
function getDisplay(fieldID, namePrefix, value, show) {
    var display;
    if (show) {
        display = mp_ssv_create_select(namePrefix + "_" + fieldID + "_display", ["Normal", "ReadOnly", "Disabled"], value);
        display.setAttribute("style", "width: 100%;");
    } else {
        display = document.createElement("input");
        display.setAttribute("type", "hidden");
        display.setAttribute("id", fieldID + "_field_start");
        display.setAttribute("name", namePrefix + "_" + fieldID + "_field_start");
        display.setAttribute("value", value);
    }
    var displayTD = document.createElement("td");
    if (show) {
        var displayLabel = document.createElement("label");
        displayLabel.setAttribute("style", "white-space: nowrap;");
        displayLabel.setAttribute("for", fieldID + "_display");
        displayLabel.innerHTML = "Display";
        displayTD.appendChild(displayLabel);
        displayTD.appendChild(getBR());
    }
    displayTD.appendChild(display);
    return displayTD;
}
function getDefaultValue(fieldID, namePrefix, value, show) {
    var defaultValue = document.createElement("input");
    if (!show) {
        defaultValue.setAttribute("type", "hidden");
    }
    defaultValue.setAttribute("id", fieldID + "_default_value");
    defaultValue.setAttribute("name", namePrefix + "_" + fieldID + "_default_value");
    defaultValue.setAttribute("style", "width: 100%;");
    defaultValue.setAttribute("value", value);
    var defaultValueTD = document.createElement("td");
    if (show) {
        var defaultValueLabel = document.createElement("label");
        defaultValueLabel.setAttribute("style", "white-space: nowrap;");
        defaultValueLabel.setAttribute("for", fieldID + "_default_value");
        defaultValueLabel.innerHTML = "Default Value";
        defaultValueTD.appendChild(defaultValueLabel);
        defaultValueTD.appendChild(getBR());
    }
    defaultValueTD.appendChild(defaultValue);
    return defaultValueTD;
}
function getPlaceholder(fieldID, namePrefix, value, show) {
    var placeholder = document.createElement("input");
    if (!show) {
        placeholder.setAttribute("type", "hidden");
    }
    placeholder.setAttribute("id", fieldID + "_placeholder");
    placeholder.setAttribute("name", namePrefix + "_" + fieldID + "_placeholder");
    placeholder.setAttribute("style", "width: 100%;");
    placeholder.setAttribute("value", value);
    var placeholderTD = document.createElement("td");
    if (show) {
        var placeholderLabel = document.createElement("label");
        placeholderLabel.setAttribute("style", "white-space: nowrap;");
        placeholderLabel.setAttribute("for", fieldID + "_placeholder");
        placeholderLabel.innerHTML = "Placeholder";
        placeholderTD.appendChild(placeholderLabel);
        placeholderTD.appendChild(getBR());
    }
    placeholderTD.appendChild(placeholder);
    return placeholderTD;
}
function getClass(fieldID, namePrefix, value, show) {
    var classField = document.createElement("input");
    if (!show) {
        classField.setAttribute("type", "hidden");
    }
    classField.setAttribute("id", fieldID + "_class");
    classField.setAttribute("name", namePrefix + "_" + fieldID + "_class");
    classField.setAttribute("style", "width: 100%;");
    classField.setAttribute("value", value);
    var classTD = document.createElement("td");
    if (show) {
        var classLabel = document.createElement("label");
        classLabel.setAttribute("style", "white-space: nowrap;");
        classLabel.setAttribute("for", fieldID + "_class");
        classLabel.innerHTML = "Class";
        classTD.appendChild(classLabel);
        classTD.appendChild(getBR());
    }
    classTD.appendChild(classField);
    return classTD;
}
function getStyle(fieldID, namePrefix, value, show) {
    var style = document.createElement("input");
    if (!show) {
        style.setAttribute("type", "hidden");
    }
    style.setAttribute("id", fieldID + "_style");
    style.setAttribute("name", namePrefix + "_" + fieldID + "_style");
    style.setAttribute("style", "width: 100%;");
    style.setAttribute("value", value);
    var styleTD = document.createElement("td");
    if (show) {
        var styleLabel = document.createElement("label");
        styleLabel.setAttribute("style", "white-space: nowrap;");
        styleLabel.setAttribute("for", fieldID + "_style");
        styleLabel.innerHTML = "Style";
        styleTD.appendChild(styleLabel);
        styleTD.appendChild(getBR());
    }
    styleTD.appendChild(style);
    return styleTD;
}
function getEnd(fieldID, namePrefix) {
    var stop = document.createElement("input");
    stop.setAttribute("type", "hidden");
    stop.setAttribute("id", fieldID + "_field_end");
    stop.setAttribute("name", namePrefix + "_" + fieldID + "_field_end");
    stop.setAttribute("value", "end");
    var stopTD = document.createElement("td");
    stopTD.appendChild(stop);
    return stopTD;
}

function mp_ssv_create_select(fieldName, options, selected) {
    var select = document.createElement("select");
    select.setAttribute("id", fieldName);
    select.setAttribute("name", fieldName);

    for (var i = 0; i < options.length; i++) {
        var option = document.createElement("option");
        option.setAttribute("value", options[i].toLowerCase());
        if (options[i].toLowerCase() == selected) {
            option.setAttribute("selected", "selected");
        }
        option.innerHTML = options[i];
        select.appendChild(option);
    }

    return select;
}
