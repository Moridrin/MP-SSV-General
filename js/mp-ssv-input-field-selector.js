/**
 * Created by moridrin on 4-1-17.
 */

var custom_field_fields = settings.custom_field_fields;
var scripts = document.getElementsByTagName("script");
var pluginBaseURL = scripts[scripts.length - 1].src.split('/').slice(0, -3).join('/');

function mp_ssv_add_new_field(fieldType, inputType, containerID, fieldID, namePrefix, values, allowTabs) {
    // alert(JSON.stringify(values));
    if (fieldType == 'tab') {
        getTabField(containerID, fieldID, namePrefix, values, allowTabs);
    } else if (fieldType == 'header') {
        getHeaderField(containerID, fieldID, namePrefix, values, allowTabs);
    } else if (fieldType == 'input') {
        if (inputType == 'text') {
            getTextInputField(containerID, fieldID, namePrefix, values, allowTabs);
        } else if (inputType == 'select') {
            getSelectInputField(containerID, fieldID, namePrefix, values, allowTabs);
        } else if (inputType == 'checkbox') {
            getCheckboxInputField(containerID, fieldID, namePrefix, values, allowTabs);
        } else if (inputType == 'image') {
            getImageInputField(containerID, fieldID, namePrefix, values, allowTabs);
        } else {
            getCustomInputField(inputType, containerID, fieldID, namePrefix, values, allowTabs);
        }
    } else if (fieldType == 'label') {
        getLabelField(containerID, fieldID, namePrefix, values, allowTabs);
    }
}

function getTabField(containerID, fieldID, namePrefix, values, allowTabs) {
    var container = document.getElementById(containerID);

    var fieldTitle = '';
    var fieldType = 'tab';
    if (typeof values['title'] !== 'undefined') {
        fieldTitle = values['title'];
    }

    var tr = getBaseFields(fieldID, namePrefix, fieldTitle, fieldType, allowTabs);
    tr.appendChild(getEmpty(fieldID));
    tr.appendChild(getEmpty(fieldID));
    tr.appendChild(getEmpty(fieldID));
    tr.appendChild(getEmpty(fieldID));
    tr.appendChild(getEmpty(fieldID));
    tr.appendChild(getEmpty(fieldID));
    tr.appendChild(getClass(fieldID, namePrefix, ""));
    tr.appendChild(getStyle(fieldID, namePrefix, ""));
    tr.appendChild(getEnd(fieldID, namePrefix));

    container.appendChild(tr);
}
function getHeaderField(containerID, fieldID, namePrefix, values, allowTabs) {
    var container = document.getElementById(containerID);

    var fieldTitle = '';
    var fieldType = 'header';
    if (typeof values['title'] !== 'undefined') {
        fieldTitle = values['title'];
    }

    var tr = getBaseFields(fieldID, namePrefix, fieldTitle, fieldType, allowTabs);
    tr.appendChild(getEmpty(fieldID));
    tr.appendChild(getEmpty(fieldID));
    tr.appendChild(getEmpty(fieldID));
    tr.appendChild(getEmpty(fieldID));
    tr.appendChild(getEmpty(fieldID));
    tr.appendChild(getEmpty(fieldID));
    tr.appendChild(getClass(fieldID, namePrefix, ""));
    tr.appendChild(getStyle(fieldID, namePrefix, ""));
    tr.appendChild(getEnd(fieldID, namePrefix));

    container.appendChild(tr);
}
function getTextInputField(containerID, fieldID, namePrefix, values, allowTabs) {
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

    var tr = getBaseFields(fieldID, namePrefix, fieldTitle, fieldType, allowTabs);
    tr = getTextInputFields(tr, fieldID, namePrefix, inputType, name, required, display, defaultValue, placeholder, classValue, style);
    container.appendChild(tr);
}
function getSelectInputField(containerID, fieldID, namePrefix, values, allowTabs) {
    var container = document.getElementById(containerID);

    var fieldTitle = '';
    var fieldType = 'input';
    var inputType = 'select';
    var name = '';
    var options = '';
    var display = 'normal';
    var classValue = '';
    var style = '';
    if (typeof values != 'undefined') {
        fieldTitle = values['title'];
        name = values['name'];
        options = values['options'];
        display = values['display'];
        classValue = values['class'];
        style = values['style'];
    }

    var tr = getBaseFields(fieldID, namePrefix, fieldTitle, fieldType, allowTabs);
    tr = getSelectInputFields(tr, fieldID, namePrefix, inputType, name, options, display, classValue, style);
    container.appendChild(tr);
}
function getCheckboxInputField(containerID, fieldID, namePrefix, values, allowTabs) {
    var container = document.getElementById(containerID);

    var fieldTitle = '';
    var fieldType = 'input';
    var inputType = 'checkbox';
    var name = '';
    var required = '';
    var display = 'normal';
    var defaultSelected = '';
    var classValue = '';
    var style = '';
    if (typeof values != 'undefined') {
        fieldTitle = values['title'];
        name = values['name'];
        required = values['required'];
        display = values['display'];
        defaultSelected = values['default_value'];
        classValue = values['class'];
        style = values['style'];
    }

    var tr = getBaseFields(fieldID, namePrefix, fieldTitle, fieldType, allowTabs);
    tr = getCheckboxInputFields(tr, fieldID, namePrefix, inputType, name, required, display, defaultSelected, classValue, style);
    container.appendChild(tr);
}
function getImageInputField(containerID, fieldID, namePrefix, values, allowTabs) {
    var container = document.getElementById(containerID);

    var fieldTitle = '';
    var fieldType = 'input';
    var inputType = 'image';
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

    var tr = getBaseFields(fieldID, namePrefix, fieldTitle, fieldType, allowTabs);
    tr = getImageInputFields(tr, fieldID, namePrefix, inputType, name, required, display, defaultValue, placeholder, classValue, style);
    container.appendChild(tr);
}
function getCustomInputField(inputType, containerID, fieldID, namePrefix, values, allowTabs) {
    var container = document.getElementById(containerID);

    var fieldTitle = '';
    var fieldType = 'input';
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

    var tr = getBaseFields(fieldID, namePrefix, fieldTitle, fieldType, allowTabs);
    tr = getCustomInputFields(tr, fieldID, namePrefix, inputType, name, required, display, defaultValue, placeholder, classValue, style);
    container.appendChild(tr);
}
function getLabelField(containerID, fieldID, namePrefix, values, allowTabs) {
    var container = document.getElementById(containerID);

    var fieldType = 'label';
    var fieldTitle = '';
    var text = '';
    var classValue = '';
    var style = '';
    if (typeof values !== 'undefined') {
        fieldTitle = values['title'];
        text = values['text'];
        classValue = values['class'];
        style = values['style'];
    }

    var tr = getBaseFields(fieldID, namePrefix, fieldTitle, fieldType, allowTabs);
    tr.appendChild(getText(fieldID, namePrefix, text));
    tr.appendChild(getClass(fieldID, namePrefix, classValue));
    tr.appendChild(getStyle(fieldID, namePrefix, style));
    tr.appendChild(getEnd(fieldID, namePrefix));

    container.appendChild(tr);
}

function getBaseFields(fieldID, namePrefix, fieldTitle, fieldType, allowTabs) {
    var tr = document.createElement("tr");
    tr.setAttribute("id", fieldID + "_tr");
    tr.appendChild(getStart(fieldID, namePrefix));
    tr.appendChild(getFieldID(fieldID, namePrefix));
    tr.appendChild(getDraggable(fieldID));
    tr.appendChild(getFieldTitle(fieldID, namePrefix, fieldTitle));
    tr.appendChild(getFieldType(fieldID, namePrefix, fieldType, allowTabs));
    return tr;
}
function getTextInputFields(tr, fieldID, namePrefix, inputType, name, required, display, defaultValue, placeholder, classValue, style) {
    tr.appendChild(getInputType(fieldID, namePrefix, inputType));
    tr.appendChild(getName(fieldID, namePrefix, name));
    tr.appendChild(getDisplay(fieldID, namePrefix, display));
    tr.appendChild(getRequired(fieldID, namePrefix, required));
    tr.appendChild(getDefaultValue(fieldID, namePrefix, defaultValue));
    tr.appendChild(getPlaceholder(fieldID, namePrefix, placeholder));
    tr.appendChild(getClass(fieldID, namePrefix, classValue));
    tr.appendChild(getStyle(fieldID, namePrefix, style));
    tr.appendChild(getEnd(fieldID, namePrefix));
    return tr;
}
function getSelectInputFields(tr, fieldID, namePrefix, inputType, name, options, display, classValue, style) {
    tr.appendChild(getInputType(fieldID, namePrefix, inputType));
    tr.appendChild(getName(fieldID, namePrefix, name));
    tr.appendChild(getDisplay(fieldID, namePrefix, display));
    tr.appendChild(getOptions(fieldID, namePrefix, options));
    tr.appendChild(getClass(fieldID, namePrefix, classValue));
    tr.appendChild(getStyle(fieldID, namePrefix, style));
    tr.appendChild(getEnd(fieldID, namePrefix));
    return tr;
}
function getCheckboxInputFields(tr, fieldID, namePrefix, inputType, name, required, display, defaultSelected, classValue, style) {
    tr.appendChild(getInputType(fieldID, namePrefix, inputType));
    tr.appendChild(getName(fieldID, namePrefix, name));
    tr.appendChild(getDisplay(fieldID, namePrefix, display));
    tr.appendChild(getRequired(fieldID, namePrefix, required));
    tr.appendChild(getDefaultSelected(fieldID, namePrefix, defaultSelected));
    tr.appendChild(getEmpty(fieldID));
    tr.appendChild(getClass(fieldID, namePrefix, classValue));
    tr.appendChild(getStyle(fieldID, namePrefix, style));
    tr.appendChild(getEnd(fieldID, namePrefix));
    return tr;
}
function getImageInputFields(tr, fieldID, namePrefix, inputType, name, required, display, defaultValue, placeholder, classValue, style) {
    tr.appendChild(getInputType(fieldID, namePrefix, inputType));
    tr.appendChild(getName(fieldID, namePrefix, name));
    tr.appendChild(getDisplay(fieldID, namePrefix, display));
    tr.appendChild(getRequired(fieldID, namePrefix, required));
    tr.appendChild(getDefaultValue(fieldID, namePrefix, defaultValue));
    tr.appendChild(getPlaceholder(fieldID, namePrefix, placeholder));
    tr.appendChild(getClass(fieldID, namePrefix, classValue));
    tr.appendChild(getStyle(fieldID, namePrefix, style));
    tr.appendChild(getEnd(fieldID, namePrefix));
    return tr;
}
function getCustomInputFields(tr, fieldID, namePrefix, inputType, name, required, display, defaultValue, placeholder, classValue, style) {
    tr.appendChild(getInputType(fieldID, namePrefix, inputType));
    tr.appendChild(getName(fieldID, namePrefix, name));
    tr.appendChild(getDisplay(fieldID, namePrefix, display));
    tr.appendChild(getRequired(fieldID, namePrefix, required));
    tr.appendChild(getDefaultValue(fieldID, namePrefix, defaultValue));
    tr.appendChild(getPlaceholder(fieldID, namePrefix, placeholder));
    tr.appendChild(getClass(fieldID, namePrefix, classValue));
    tr.appendChild(getStyle(fieldID, namePrefix, style));
    tr.appendChild(getEnd(fieldID, namePrefix));
    return tr;
}

function getBR() {
    var br = document.createElement("div");
    br.innerHTML = '<br/>';
    return br.childNodes[0];
}
function getEmpty(fieldID) {
    var td = document.createElement("td");
    td.setAttribute("class", fieldID + "empty_td");
    return td;
}
function getStart(fieldID, namePrefix) {
    var start = document.createElement("input");
    start.setAttribute("type", "hidden");
    start.setAttribute("id", fieldID + "_start");
    start.setAttribute("name", namePrefix + "_" + fieldID + "_start");
    start.setAttribute("value", "start");
    var startTD = document.createElement("td");
    startTD.setAttribute("id", fieldID + "_start_td");
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
    fieldIDTD.setAttribute("id", fieldID + "_id_td");
    fieldIDTD.appendChild(fieldIDElement);
    return fieldIDTD;
}
function getDraggable(fieldID) {
    var draggableIcon = document.createElement("img");
    draggableIcon.setAttribute("src", pluginBaseURL + '/general/images/icon-menu.svg');
    draggableIcon.setAttribute("style", "padding-right: 15px; margin: 10px 0;");
    var draggableIconTD = document.createElement("td");
    draggableIconTD.setAttribute("id", fieldID + "_draggable_td");
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
    fieldTitleTD.setAttribute("id", fieldID + "_field_title_td");
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
    var fieldType = createSelect(fieldID, namePrefix, "_field_type", options, value);
    fieldType.setAttribute("style", "width: 100%;");
    fieldType.onchange = function () {
        fieldTypeChanged(fieldID, namePrefix);
    };
    var fieldTypeLabel = document.createElement("label");
    fieldTypeLabel.setAttribute("style", "white-space: nowrap;");
    fieldTypeLabel.setAttribute("for", fieldID + "_field_type");
    fieldTypeLabel.innerHTML = "Field Type";
    var fieldTypeTD = document.createElement("td");
    fieldTypeTD.setAttribute("id", fieldID + "_field_type_td");
    fieldTypeTD.appendChild(fieldTypeLabel);
    fieldTypeTD.appendChild(getBR());
    fieldTypeTD.appendChild(fieldType);
    return fieldTypeTD;
}
function getText(fieldID, namePrefix, value) {
    var fieldTitle = document.createElement("textarea");
    fieldTitle.setAttribute("id", fieldID + "_text");
    fieldTitle.setAttribute("name", namePrefix + "_" + fieldID + "_text");
    fieldTitle.setAttribute("style", "width: 100%;");
    fieldTitle.innerHTML = value;
    var fieldTitleLabel = document.createElement("label");
    fieldTitleLabel.setAttribute("style", "white-space: nowrap;");
    fieldTitleLabel.setAttribute("for", fieldID + "_text");
    fieldTitleLabel.innerHTML = "Text";
    var fieldTitleTD = document.createElement("td");
    fieldTitleTD.setAttribute("id", fieldID + "_text_td");
    var colspan = 6;
    fieldTitleTD.setAttribute("colspan", colspan);
    fieldTitleTD.appendChild(fieldTitleLabel);
    fieldTitleTD.appendChild(getBR());
    fieldTitleTD.appendChild(fieldTitle);
    return fieldTitleTD;
}
function getInputType(fieldID, namePrefix, value) {
    var options = ["Text", "Select", "Checkbox", "Image", "Custom"];
    var customValue = '';
    if (["text", "select", "checkbox", "image", "custom"].indexOf(value) == -1) {
        customValue = value;
        value = 'custom';
    }
    var inputType = createSelect(fieldID, namePrefix, "_input_type", options, value);
    if (value == 'custom') {
        inputType.setAttribute("style", "width: 48%;");
    } else {
        inputType.setAttribute("style", "width: 100%;");
    }
    inputType.onchange = function () {
        inputTypeChanged(fieldID, namePrefix);
    };
    var inputTypeLabel = document.createElement("label");
    inputTypeLabel.setAttribute("style", "white-space: nowrap;");
    inputTypeLabel.setAttribute("for", fieldID + "_input_type");
    inputTypeLabel.innerHTML = "Input Type";
    var inputTypeTD = document.createElement("td");
    inputTypeTD.setAttribute("id", fieldID + "_input_type_td");
    inputTypeTD.appendChild(inputTypeLabel);
    inputTypeTD.appendChild(getBR());
    inputTypeTD.appendChild(inputType);
    if (value == 'custom') {
        var inputTypeCustom = document.createElement("input");
        inputTypeCustom.setAttribute("id", fieldID + "_input_type");
        inputTypeCustom.setAttribute("name", namePrefix + "_" + fieldID + "_input_type");
        inputTypeCustom.setAttribute("style", "width: 50%;");
        inputTypeCustom.setAttribute("value", customValue);
        inputTypeCustom.setAttribute("required", "required");
        inputTypeTD.appendChild(inputTypeCustom);
    }
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
    nameTD.setAttribute("id", fieldID + "_name_td");
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
    requiredTD.setAttribute("id", fieldID + "_required_td");
    requiredTD.appendChild(requiredLabel);
    requiredTD.appendChild(getBR());
    requiredTD.appendChild(requiredReset);
    requiredTD.appendChild(required);
    return requiredTD;
}
function getDisplay(fieldID, namePrefix, value) {
    var display;
    var show = custom_field_fields.indexOf('display') !== -1;
    if (show) {
        display = createSelect(fieldID, namePrefix, "_display", ["Normal", "ReadOnly", "Disabled"], value);
        display.setAttribute("style", "width: 100%;");
    } else {
        display = document.createElement("input");
        display.setAttribute("type", "hidden");
        display.setAttribute("id", fieldID + "_display");
        display.setAttribute("name", namePrefix + "_" + fieldID + "_display");
        display.setAttribute("value", value);
    }
    var displayTD = document.createElement("td");
    displayTD.setAttribute("id", fieldID + "_display_td");
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
function getOptions(fieldID, namePrefix, value) {
    var options = document.createElement("input");
    options.setAttribute("id", fieldID + "_options");
    options.setAttribute("name", namePrefix + "_" + fieldID + "_options");
    options.setAttribute("style", "width: 100%;");
    options.setAttribute("value", value);
    options.setAttribute("required", "required");
    options.setAttribute("placeholder", "Separate with ','");
    var optionsLabel = document.createElement("label");
    optionsLabel.setAttribute("style", "white-space: nowrap;");
    optionsLabel.setAttribute("for", fieldID + "_options");
    optionsLabel.innerHTML = "Options";
    var nameTD = document.createElement("td");
    nameTD.setAttribute("id", fieldID + "_options_td");
    nameTD.setAttribute("colspan", 3);
    nameTD.appendChild(optionsLabel);
    nameTD.appendChild(getBR());
    nameTD.appendChild(options);
    return nameTD;
}
function getDefaultValue(fieldID, namePrefix, value) {
    var defaultValue = document.createElement("input");
    var show = custom_field_fields.indexOf('default') !== -1;
    if (!show) {
        defaultValue.setAttribute("type", "hidden");
    }
    defaultValue.setAttribute("id", fieldID + "_default_value");
    defaultValue.setAttribute("name", namePrefix + "_" + fieldID + "_default_value");
    defaultValue.setAttribute("style", "width: 100%;");
    defaultValue.setAttribute("value", value);
    var defaultValueTD = document.createElement("td");
    defaultValueTD.setAttribute("id", fieldID + "_default_value_td");
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
function getDefaultSelected(fieldID, namePrefix, value) {
    var defaultSelected = document.createElement("input");
    var show = custom_field_fields.indexOf('default') !== -1;
    if (!show) {
        defaultSelected.setAttribute("type", "hidden");
    } else {
        defaultSelected.setAttribute("type", "checkbox");
    }
    defaultSelected.setAttribute("id", fieldID + "_default_checked");
    defaultSelected.setAttribute("name", namePrefix + "_" + fieldID + "_default_checked");
    defaultSelected.setAttribute("value", "true");
    if (value == 'true') {
        defaultSelected.setAttribute("checked", "checked");
    }
    var defaultSelectedReset = document.createElement("input");
    defaultSelectedReset.setAttribute("type", "hidden");
    defaultSelectedReset.setAttribute("id", fieldID + "_default_checked");
    defaultSelectedReset.setAttribute("name", namePrefix + "_" + fieldID + "_default_checked");
    defaultSelectedReset.setAttribute("value", "false");
    var requiredTD = document.createElement("td");
    requiredTD.setAttribute("id", fieldID + "_default_checked_td");
    if (show) {
        var requiredLabel = document.createElement("label");
        requiredLabel.setAttribute("style", "white-space: nowrap;");
        requiredLabel.setAttribute("for", fieldID + "_default_checked");
        requiredLabel.innerHTML = "Default Selected";
        requiredTD.appendChild(requiredLabel);
        requiredTD.appendChild(getBR());
    }
    requiredTD.appendChild(defaultSelectedReset);
    requiredTD.appendChild(defaultSelected);
    return requiredTD;
}
function getPlaceholder(fieldID, namePrefix, value) {
    var placeholder = document.createElement("input");
    var show = custom_field_fields.indexOf('placeholder') !== -1;
    if (!show) {
        placeholder.setAttribute("type", "hidden");
    }
    placeholder.setAttribute("id", fieldID + "_placeholder");
    placeholder.setAttribute("name", namePrefix + "_" + fieldID + "_placeholder");
    placeholder.setAttribute("style", "width: 100%;");
    placeholder.setAttribute("value", value);
    var placeholderTD = document.createElement("td");
    placeholderTD.setAttribute("id", fieldID + "_placeholder_td");
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
function getClass(fieldID, namePrefix, value) {
    var classField = document.createElement("input");
    var show = custom_field_fields.indexOf('class') !== -1;
    if (!show) {
        classField.setAttribute("type", "hidden");
    }
    classField.setAttribute("id", fieldID + "_class");
    classField.setAttribute("name", namePrefix + "_" + fieldID + "_class");
    classField.setAttribute("style", "width: 100%;");
    classField.setAttribute("value", value);
    var classTD = document.createElement("td");
    classTD.setAttribute("id", fieldID + "_class_td");
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
function getStyle(fieldID, namePrefix, value) {
    var style = document.createElement("input");
    var show = custom_field_fields.indexOf('style') !== -1;
    if (!show) {
        style.setAttribute("type", "hidden");
    }
    style.setAttribute("id", fieldID + "_style");
    style.setAttribute("name", namePrefix + "_" + fieldID + "_style");
    style.setAttribute("style", "width: 100%;");
    style.setAttribute("value", value);
    var styleTD = document.createElement("td");
    styleTD.setAttribute("id", fieldID + "_style_td");
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
    stop.setAttribute("id", fieldID + "_end");
    stop.setAttribute("name", namePrefix + "_" + fieldID + "_end");
    stop.setAttribute("value", "end");
    var stopTD = document.createElement("td");
    stopTD.setAttribute("id", fieldID + "_end_td");
    stopTD.appendChild(stop);
    return stopTD;
}

function fieldTypeChanged(fieldID, namePrefix) {
    var tr = document.getElementById(fieldID + "_tr");
    var fieldType = document.getElementById(fieldID + "_field_type").value;
    removeField(document.getElementById(fieldID + "_text_td"));
    removeField(document.getElementById(fieldID + "_input_type_td"));
    removeField(document.getElementById(fieldID + "_name_td"));
    removeField(document.getElementById(fieldID + "_required_td"));
    removeField(document.getElementById(fieldID + "_options_td"));
    removeField(document.getElementById(fieldID + "_display_td"));
    removeField(document.getElementById(fieldID + "_default_value_td"));
    removeField(document.getElementById(fieldID + "_default_checked_td"));
    removeField(document.getElementById(fieldID + "_placeholder_td"));
    removeField(document.getElementById(fieldID + "_class_td"));
    removeField(document.getElementById(fieldID + "_style_td"));
    removeField(document.getElementById(fieldID + "_end_td"));
    removeFields(document.getElementsByClassName("empty_td"));
    if (fieldType == 'input') {
        tr.appendChild(getInputType(fieldID, namePrefix, ""));
        tr.appendChild(getName(fieldID, namePrefix, ""));
        tr.appendChild(getDisplay(fieldID, namePrefix, ""));
        tr.appendChild(getRequired(fieldID, namePrefix, ""));
        tr.appendChild(getDefaultValue(fieldID, namePrefix, ""));
        tr.appendChild(getPlaceholder(fieldID, namePrefix, ""));
        tr.appendChild(getClass(fieldID, namePrefix, ""));
        tr.appendChild(getStyle(fieldID, namePrefix, ""));
        tr.appendChild(getEnd(fieldID, namePrefix));
    } else if (fieldType == 'label') {
        tr.appendChild(getText(fieldID, namePrefix, ""));
        tr.appendChild(getClass(fieldID, namePrefix, ""));
        tr.appendChild(getStyle(fieldID, namePrefix, ""));
        tr.appendChild(getEnd(fieldID, namePrefix));
    } else {
        tr.appendChild(getEmpty(fieldID));
        tr.appendChild(getEmpty(fieldID));
        tr.appendChild(getEmpty(fieldID));
        tr.appendChild(getEmpty(fieldID));
        tr.appendChild(getEmpty(fieldID));
        tr.appendChild(getEmpty(fieldID));
        tr.appendChild(getClass(fieldID, namePrefix, ""));
        tr.appendChild(getStyle(fieldID, namePrefix, ""));
        tr.appendChild(getEnd(fieldID, namePrefix));
    }
}
function inputTypeChanged(fieldID, namePrefix) {
    var tr = document.getElementById(fieldID + "_tr");
    var inputType = document.getElementById(fieldID + "_input_type").value;
    removeField(document.getElementById(fieldID + "_input_type_td"));
    removeField(document.getElementById(fieldID + "_name_td"));
    removeField(document.getElementById(fieldID + "_required_td"));
    removeField(document.getElementById(fieldID + "_options_td"));
    removeField(document.getElementById(fieldID + "_display_td"));
    removeField(document.getElementById(fieldID + "_default_value_td"));
    removeField(document.getElementById(fieldID + "_default_checked_td"));
    removeField(document.getElementById(fieldID + "_placeholder_td"));
    removeField(document.getElementById(fieldID + "_class_td"));
    removeField(document.getElementById(fieldID + "_style_td"));
    removeField(document.getElementById(fieldID + "_end_td"));
    removeFields(document.getElementsByClassName(fieldID + "empty_td"));
    if (inputType == 'text') {
        getTextInputFields(tr, fieldID, namePrefix, inputType, "", "", "", "", "", "", "");
    } else if (inputType == 'select') {
        getSelectInputFields(tr, fieldID, namePrefix, inputType, "", "", "", "", "");
    } else if (inputType == 'checkbox') {
        getCheckboxInputFields(tr, fieldID, namePrefix, inputType, "", "", "", "", "", "")
    } else if (inputType == 'image') {
        getImageInputFields(tr, fieldID, namePrefix, inputType, "", "", "", "", "", "", "");
    } else {
        getCustomInputFields(tr, fieldID, namePrefix, inputType, "", "", "", "", "", "", "");
    }
}

function createSelect(fieldID, namePrefix, fieldNameExtension, options, selected) {
    var select = document.createElement("select");
    select.setAttribute("id", fieldID + fieldNameExtension);
    select.setAttribute("name", namePrefix + "_" + fieldID + fieldNameExtension);

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
function removeFields(fields) {
    if (fields !== null) {
        while (fields.length > 0) {
            removeField(fields[0]);
        }
    }
}
function removeField(field) {
    if (field !== null) {
        field.parentElement.removeChild(field);
    }
}
