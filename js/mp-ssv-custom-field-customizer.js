//noinspection JSUnresolvedVariable
var roles = JSON.parse(settings.roles);
var scripts = document.getElementsByTagName("script");
var pluginBaseURL = scripts[scripts.length - 1].src.split('/').slice(0, -3).join('/');
var fieldIDs = [];
var containerID = 0;

function mp_ssv_add_custom_input_field_customizer(container, fieldID, inputType, values) {
    fieldIDs.push(fieldID);
    container = document.getElementById(container);
    containerID = container.getAttribute("container_id");
    if (!values) {
        values = [];
    }

    if (inputType === 'text') {
        getTextInputField(container, fieldID, values);
    } else if (inputType === 'select') {
        getSelectInputField(container, fieldID, values);
    } else if (inputType === 'checkbox') {
        getCheckboxInputField(container, fieldID, values);
    } else if (inputType === 'role_checkbox') {
        getRoleCheckboxInputField(container, fieldID, values);
    } else if (inputType === 'role_select') {
        getRoleSelectInputField(container, fieldID, values);
    } else if (inputType === 'date') {
        getDateInputField(container, fieldID, values);
    } else if (inputType === 'image') {
        getImageInputField(container, fieldID, values);
    } else if (inputType === 'hidden') {
        getHiddenInputField(container, fieldID, values);
    } else {
        getCustomInputField(container, fieldID, values);
    }
}

function mp_ssv_add_custom_tab_field_customizer(container, fieldID, values) {
    container = document.getElementById(container);
    var fieldTitle = '';
    var classValue = '';
    var style = '';
    var fields = {};
    if (values) {
        fieldTitle = values['title'];
        classValue = values['class'];
        style = values['style'];
        fields = values['fields'];
    }

    var tr = getBaseFields(fieldID, '[tab]', fieldTitle, "Tab");
    tr.appendChild(getEmpty(fieldID));
    tr.appendChild(getEmpty(fieldID, "disabled"));
    tr.appendChild(getEmpty(fieldID, "required"));
    tr.appendChild(getEmpty(fieldID, "placeholder"));
    tr.appendChild(getEmpty(fieldID, "override_right"));
    tr.appendChild(getClass(fieldID, classValue));
    tr.appendChild(getStyle(fieldID, style));
    tr.appendChild(getDeleteRow(fieldID));

    container.appendChild(tr);
    for (var i in fields) {
        mp_ssv_add_custom_input_field_customizer(container, fields[i]['id'], fields[i]['input_type'], fields[i]);
    }
}

function mp_ssv_add_custom_header_field_customizer(container, fieldID, values) {
    container = document.getElementById(container);
    var fieldTitle = '';
    var classValue = '';
    var style = '';

    if (values) {
        fieldTitle = values['title'];
        classValue = values['class'];
        style = values['style'];
    }

    var tr = getBaseFields(fieldID, '[header]', fieldTitle, "Header");
    tr.appendChild(getEmpty(fieldID));
    tr.appendChild(getEmpty(fieldID, "disabled"));
    tr.appendChild(getEmpty(fieldID, "required"));
    tr.appendChild(getEmpty(fieldID, "placeholder"));
    tr.appendChild(getEmpty(fieldID, "override_right"));
    tr.appendChild(getClass(fieldID, classValue));
    tr.appendChild(getStyle(fieldID, style));
    tr.appendChild(getDeleteRow(fieldID));

    container.appendChild(tr);
}

function mp_ssv_add_custom_label_field_customizer(container, fieldID, values) {
    container = document.getElementById(container);
    var fieldTitle = '';
    var text = '';
    var classValue = '';
    var style = '';

    if (values) {
        fieldTitle = values['title'];
        text = values['text'];
        classValue = values['class'];
        style = values['style'];
    }

    var tr = getBaseFields(fieldID, '[label]', fieldTitle, "Label");
    tr.appendChild(getText(text));
    tr.appendChild(getClass(fieldID, classValue));
    tr.appendChild(getStyle(fieldID, style));
    tr.appendChild(getDeleteRow(fieldID));

    container.appendChild(tr);
}

function getTextInputField(container, fieldID, values) {
    //noinspection JSUnusedLocalSymbols
    var overrideRight = values['override_right'];
    var fieldName = '';
    var fieldTitle = '';
    var fieldType = 'input';
    var required = false;
    var disabled = false;
    var defaultValue = '';
    var placeholder = '';
    var classValue = '';
    var style = '';
    if (Object.keys(values).length > 1) {
        fieldName = values['name'];
        fieldTitle = values['title'];
        required = values['required'];
        disabled = values['disabled'];
        defaultValue = values['default_value'];
        placeholder = values['placeholder'];
        classValue = values['class'];
        style = values['style'];
    }

    var tr = getBaseFields(fieldID, fieldName, fieldTitle, fieldType);
    tr = getTextInputFields(tr, fieldID, required, disabled, defaultValue, placeholder, classValue, style, overrideRight);
    container.appendChild(tr);
}

function getSelectInputField(container, fieldID, values) {
    //noinspection JSUnusedLocalSymbols
    var overrideRight = values['override_right'];
    var fieldName = '';
    var fieldTitle = '';
    var fieldType = 'input';
    var name = '';
    var disabled = false;
    var classValue = '';
    var style = '';
    if (Object.keys(values).length > 1) {
        fieldName = values['name'];
        fieldTitle = values['title'];
        disabled = values['disabled'];
        classValue = values['class'];
        style = values['style'];
    }

    var tr = getBaseFields(fieldID, fieldName, fieldTitle, fieldType);
    tr = getSelectInputFields(tr, fieldID, disabled, classValue, style, overrideRight);
    container.appendChild(tr);
}

function getCheckboxInputField(container, fieldID, values) {
    //noinspection JSUnusedLocalSymbols
    var overrideRight = values['override_right'];
    var fieldName = '';
    var fieldTitle = '';
    var fieldType = 'input';
    var name = '';
    var required = false;
    var disabled = false;
    var defaultChecked = '';
    var classValue = '';
    var style = '';
    if (Object.keys(values).length > 1) {
        fieldName = values['name'];
        fieldTitle = values['title'];
        required = values['required'];
        disabled = values['disabled'];
        defaultChecked = values['default_checked'];
        classValue = values['class'];
        style = values['style'];
    }

    var tr = getBaseFields(fieldID, fieldName, fieldTitle, fieldType);
    tr = getCheckboxInputFields(tr, fieldID, required, disabled, defaultChecked, classValue, style, overrideRight);
    container.appendChild(tr);
}

function getRoleCheckboxInputField(container, fieldID, values) {
    //noinspection JSUnusedLocalSymbols
    var overrideRight = values['override_right'];
    var fieldName = '';
    var fieldTitle = '';
    var fieldType = 'input';
    var classValue = '';
    var style = '';
    if (Object.keys(values).length > 1) {
        fieldName = values['name'];
        fieldTitle = values['title'];
        classValue = values['class'];
        style = values['style'];
    }

    var tr = getBaseFields(fieldID, fieldName, fieldTitle, fieldType);
    tr = getRoleCheckboxInputFields(tr, fieldID, classValue, style, overrideRight);
    container.appendChild(tr);
}

function getRoleSelectInputField(container, fieldID, values) {
    //noinspection JSUnusedLocalSymbols
    var overrideRight = values['override_right'];
    var fieldName = '';
    var fieldTitle = '';
    var fieldType = 'input';
    var name = '';
    var classValue = '';
    var style = '';
    if (Object.keys(values).length > 1) {
        fieldName = values['name'];
        fieldTitle = values['title'];
        classValue = values['class'];
        style = values['style'];
    }

    var tr = getBaseFields(fieldID, fieldName, fieldTitle, fieldType);
    tr = getRoleSelectInputFields(tr, fieldID, classValue, style, overrideRight);
    container.appendChild(tr);
}

function getImageInputField(container, fieldID, values) {
    //noinspection JSUnusedLocalSymbols
    var overrideRight = values['override_right'];
    var fieldName = '';
    var fieldTitle = '';
    var fieldType = 'input';
    var name = '';
    var required = false;
    var classValue = '';
    var style = '';
    if (Object.keys(values).length > 1) {
        fieldName = values['name'];
        fieldTitle = values['title'];
        required = values['required'];
        classValue = values['class'];
        style = values['style'];
    }

    var tr = getBaseFields(fieldID, fieldName, fieldTitle, fieldType);
    tr = getImageInputFields(tr, fieldID, required, classValue, style, overrideRight);
    container.appendChild(tr);
}

function getHiddenInputField(container, fieldID, values) {
    //noinspection JSUnusedLocalSymbols
    var overrideRight = values['override_right'];
    var fieldName = '';
    var fieldTitle = '';
    var fieldType = 'input';
    var defaultValue = '';
    var classValue = '';
    var style = '';
    if (Object.keys(values).length > 1) {
        fieldName = values['name'];
        fieldTitle = values['title'];
        defaultValue = values['default_value'];
        classValue = values['class'];
        style = values['style'];
    }

    var tr = getBaseFields(fieldID, fieldName, fieldTitle, fieldType);
    tr = getHiddenInputFields(tr, fieldID, defaultValue, classValue, style);
    container.appendChild(tr);
}

function getCustomInputField(container, fieldID, values) {
    //noinspection JSUnusedLocalSymbols
    var overrideRight = values['override_right'];
    var fieldName = '';
    var fieldTitle = '';
    var fieldType = 'input';
    var required = false;
    var disabled = false;
    var defaultValue = '';
    var placeholder = '';
    var classValue = '';
    var style = '';
    if (Object.keys(values).length > 1) {
        fieldName = values['name'];
        fieldTitle = values['title'];
        required = values['required'];
        disabled = values['disabled'];
        defaultValue = values['default_value'];
        placeholder = values['placeholder'];
        classValue = values['class'];
        style = values['style'];
    }

    var tr = getBaseFields(fieldID, fieldName, fieldTitle, fieldType);
    tr = getCustomInputFields(tr, fieldID, required, disabled, defaultValue, placeholder, classValue, style, overrideRight);
    container.appendChild(tr);
}

function getDateInputField(container, fieldID, values) {
    //noinspection JSUnusedLocalSymbols
    var overrideRight = values['override_right'];
    var fieldName = '';
    var fieldTitle = '';
    var fieldType = 'input';
    var required = false;
    var disabled = false;
    var defaultValue = '';
    var dateRangeAfter = '';
    var dateRangeBefore = '';
    var classValue = '';
    var style = '';
    if (Object.keys(values).length > 1) {
        fieldName = values['name'];
        fieldTitle = values['title'];
        required = values['required'];
        disabled = values['disabled'];
        defaultValue = values['default_value'];
        dateRangeAfter = values['date_range_after'];
        dateRangeBefore = values['date_range_before'];
        classValue = values['class'];
        style = values['style'];
    }

    var tr = getBaseFields(fieldID, fieldName, fieldTitle, fieldType);
    tr = getDateInputFields(tr, fieldID, required, disabled, defaultValue, dateRangeAfter, dateRangeBefore, classValue, style, overrideRight);
    container.appendChild(tr);
}

function getBaseFields(fieldID, fieldName, fieldTitle, placeholder) {
    var tr = document.createElement("tr");
    tr.setAttribute("id", containerID + "_" + fieldID + "_tr");
    tr.appendChild(getFieldIDs(fieldID));
    tr.appendChild(getDraggable(fieldID));
    tr.appendChild(getFieldName(fieldID, fieldName, placeholder));
    tr.appendChild(getFieldTitle(fieldID, fieldTitle, placeholder));
    return tr;
}

function getTextInputFields(tr, fieldID, required, disabled, defaultValue, placeholder, classValue, style, overrideRight) {
    tr.appendChild(getDefaultValue(fieldID, defaultValue));
    tr.appendChild(getDisabled(fieldID, disabled));
    tr.appendChild(getRequired(fieldID, required));
    tr.appendChild(getPlaceholder(fieldID, placeholder));
    tr.appendChild(getOverrideRight(fieldID, overrideRight));
    tr.appendChild(getClass(fieldID, classValue));
    tr.appendChild(getStyle(fieldID, style));
    tr.appendChild(getDeleteRow(fieldID));
    return tr;
}

function getSelectInputFields(tr, fieldID, disabled, classValue, style) {
    tr.appendChild(getEmpty(fieldID));
    tr.appendChild(getDisabled(fieldID, disabled));
    tr.appendChild(getEmpty(fieldID, "required"));
    tr.appendChild(getEmpty(fieldID, "placeholder"));
    tr.appendChild(getEmpty(fieldID, "override_right"));
    tr.appendChild(getClass(fieldID, classValue));
    tr.appendChild(getStyle(fieldID, style));
    tr.appendChild(getDeleteRow(fieldID));
    return tr;
}

function getCheckboxInputFields(tr, fieldID, required, disabled, defaultChecked, classValue, style, overrideRight) {
    tr.appendChild(getDefaultChecked(fieldID, defaultChecked));
    tr.appendChild(getDisabled(fieldID, disabled));
    tr.appendChild(getRequired(fieldID, required));
    tr.appendChild(getEmpty(fieldID, "placeholder"));
    tr.appendChild(getOverrideRight(fieldID, overrideRight));
    tr.appendChild(getClass(fieldID, classValue));
    tr.appendChild(getStyle(fieldID, style));
    tr.appendChild(getDeleteRow(fieldID));
    return tr;
}

function getRoleCheckboxInputFields(tr, fieldID, classValue, style, overrideRight) {
    tr.appendChild(getEmpty(fieldID));
    tr.appendChild(getEmpty(fieldID, "disabled"));
    tr.appendChild(getEmpty(fieldID, "required"));
    tr.appendChild(getEmpty(fieldID, "placeholder"));
    tr.appendChild(getOverrideRight(fieldID, overrideRight));
    tr.appendChild(getClass(fieldID, classValue));
    tr.appendChild(getStyle(fieldID, style));
    tr.appendChild(getDeleteRow(fieldID));
    return tr;
}

function getRoleSelectInputFields(tr, fieldID, classValue, style, overrideRight) {
    tr.appendChild(getEmpty(fieldID));
    tr.appendChild(getEmpty(fieldID, "disabled"));
    tr.appendChild(getEmpty(fieldID, "required"));
    tr.appendChild(getEmpty(fieldID, "placeholder"));
    tr.appendChild(getOverrideRight(fieldID, overrideRight));
    tr.appendChild(getClass(fieldID, classValue));
    tr.appendChild(getStyle(fieldID, style));
    tr.appendChild(getDeleteRow(fieldID));
    return tr;
}

function getImageInputFields(tr, fieldID, required, classValue, style, overrideRight) {
    tr.appendChild(getPreview(fieldID, required));
    tr.appendChild(getRequired(fieldID, required));
    tr.appendChild(getEmpty(fieldID, "disabled"));
    tr.appendChild(getEmpty(fieldID, "placeholder"));
    tr.appendChild(getOverrideRight(fieldID, overrideRight));
    tr.appendChild(getClass(fieldID, classValue));
    tr.appendChild(getStyle(fieldID, style));
    tr.appendChild(getDeleteRow(fieldID));
    return tr;
}

function getHiddenInputFields(tr, fieldID, value, classValue, style) {
    tr.appendChild(getValue(fieldID, value));
    tr.appendChild(getEmpty(fieldID, "disabled"));
    tr.appendChild(getEmpty(fieldID, "required"));
    tr.appendChild(getEmpty(fieldID, "placeholder"));
    tr.appendChild(getEmpty(fieldID, "override_right"));
    tr.appendChild(getClass(fieldID, classValue));
    tr.appendChild(getStyle(fieldID, style));
    tr.appendChild(getDeleteRow(fieldID));
    return tr;
}

function getCustomInputFields(tr, fieldID, required, disabled, defaultValue, placeholder, classValue, style, overrideRight) {
    tr.appendChild(getDefaultValue(fieldID, defaultValue));
    tr.appendChild(getDisabled(fieldID, disabled));
    tr.appendChild(getRequired(fieldID, required));
    tr.appendChild(getPlaceholder(fieldID, placeholder));
    tr.appendChild(getOverrideRight(fieldID, overrideRight));
    tr.appendChild(getClass(fieldID, classValue));
    tr.appendChild(getStyle(fieldID, style));
    tr.appendChild(getDeleteRow(fieldID));
    return tr;
}

function getDateInputFields(tr, fieldID, required, disabled, defaultValue, dateRangeAfter, dateRangeBefore, classValue, style, overrideRight) {
    tr.appendChild(getDefaultValue(fieldID, defaultValue, 'yyyy-mm-dd'));
    tr.appendChild(getDisabled(fieldID, disabled));
    tr.appendChild(getRequired(fieldID, required));
    tr.appendChild(getDateRange(fieldID, dateRangeAfter, dateRangeBefore));
    tr.appendChild(getOverrideRight(fieldID, overrideRight));
    tr.appendChild(getClass(fieldID, classValue));
    tr.appendChild(getStyle(fieldID, style));
    tr.appendChild(getDeleteRow(fieldID));
    return tr;
}

function getBR() {
    var br = document.createElement("div");
    br.innerHTML = '<br/>';
    return br.childNodes[0];
}

function getEmpty(fieldID, columnClass) {
    var td = document.createElement("td");
    td.setAttribute("style", "padding: 0 5px;");
    td.setAttribute("class", fieldID + "_empty_td");
    if (columnClass) {
        td.classList.add('column-' + columnClass);
        if (JSON.parse(settings.columns).indexOf(columnClass) === -1) {
            td.classList.add("hidden");
        }
    }
    return td;
}

function getFieldIDs(fieldID, isTab) {
    var fieldIDs = document.createElement("input");
    fieldIDs.setAttribute("type", "hidden");
    fieldIDs.setAttribute("name", "field_ids[]");
    fieldIDs.setAttribute("value", containerID + "_" + fieldID);
    var fieldIDsTD = document.createElement("td");
    fieldIDsTD.setAttribute("style", "padding: 0 5px;");
    if (isTab) {
        fieldIDsTD.setAttribute("style", "border-left: solid;");
    }
    fieldIDsTD.setAttribute("id", containerID + "_" + fieldID + "_start_td");
    fieldIDsTD.appendChild(fieldIDs);
    return fieldIDsTD;
}

function getDraggable(fieldID) {
    var draggableIcon = document.createElement("img");
    draggableIcon.setAttribute("src", pluginBaseURL + '/general/images/icon-menu.svg');
    draggableIcon.setAttribute("style", "padding-right: 15px; margin: 10px 0;");
    var draggableIconTD = document.createElement("td");
    draggableIconTD.setAttribute("id", containerID + "_" + fieldID + "_draggable_td");
    draggableIconTD.setAttribute("style", "vertical-align: middle; cursor: move;");
    draggableIconTD.appendChild(draggableIcon);
    return draggableIconTD;
}

function getFieldName(fieldID, value) {
    var fieldName = document.createElement("input");
    fieldName.setAttribute("type", "hidden");
    fieldName.setAttribute("id", containerID + "_" + fieldID + "_name");
    fieldName.setAttribute("name", "custom_field_" + fieldID + "_name");
    fieldName.setAttribute("value", value);
    var fieldNameLabel = document.createElement("label");
    fieldNameLabel.setAttribute("id", containerID + "_" + fieldID + "_name_label");
    fieldNameLabel.innerHTML = value;
    var fieldNameTD = document.createElement("td");
    fieldNameTD.setAttribute("style", "padding: 0 5px;");
    fieldNameTD.setAttribute("id", containerID + "_" + fieldID + "_field_title_td");
    fieldNameTD.appendChild(fieldName);
    fieldNameTD.appendChild(fieldNameLabel);
    return fieldNameTD;
}

function getFieldTitle(fieldID, value) {
    var fieldTitle = document.createElement("input");
    fieldTitle.setAttribute("id", containerID + "_" + fieldID + "_title");
    fieldTitle.setAttribute("name", "custom_field_" + fieldID + "_title");
    fieldTitle.setAttribute("style", "width: 100%;");
    if (value) {
        fieldTitle.setAttribute("value", value);
    }
    var fieldTitleTD = document.createElement("td");
    fieldTitleTD.setAttribute("style", "padding: 0 5px;");
    fieldTitleTD.setAttribute("id", containerID + "_" + fieldID + "_field_title_td");
    fieldTitleTD.appendChild(fieldTitle);
    return fieldTitleTD;
}

function getText(fieldID, value) {
    var fieldTitle = document.createElement("textarea");
    fieldTitle.setAttribute("id", containerID + "_" + fieldID + "_text");
    fieldTitle.setAttribute("name", "custom_field_" + fieldID + "_text");
    fieldTitle.setAttribute("style", "width: 100%;");
    if (value) {
        fieldTitle.innerHTML = value;
    }
    var fieldTitleTD = document.createElement("td");
    fieldTitleTD.setAttribute("class", "textarea_td");
    fieldTitleTD.setAttribute("style", "padding: 0 5px;");
    fieldTitleTD.setAttribute("id", containerID + "_" + fieldID + "_text_td");
    var selected = JSON.parse(settings.columns);
    var textAreaColspan = 1;
    if (selected.indexOf("disabled") !== -1) {
        textAreaColspan++;
    }
    if (selected.indexOf("required") !== -1) {
        textAreaColspan++;
    }
    if (selected.indexOf("placeholder") !== -1) {
        textAreaColspan++;
    }
    if (selected.indexOf("override_right") !== -1) {
        textAreaColspan++;
    }
    if (selected.indexOf("class") !== -1) {
        textAreaColspan++;
    }
    if (selected.indexOf("style") !== -1) {
        textAreaColspan++;
    }
    console.log(textAreaColspan);
    fieldTitleTD.setAttribute("colspan", textAreaColspan.toString());
    fieldTitleTD.appendChild(fieldTitle);
    return fieldTitleTD;
}

function getRequired(fieldID, value) {
    var required = document.createElement("input");
    required.setAttribute("type", "checkbox");
    required.setAttribute("id", containerID + "_" + fieldID + "_required");
    required.setAttribute("name", "custom_field_" + fieldID + "_required");
    required.setAttribute("value", "true");
    if (value) {
        required.setAttribute("checked", "checked");
    }
    var requiredReset = document.createElement("input");
    requiredReset.setAttribute("type", "hidden");
    requiredReset.setAttribute("id", containerID + "_" + fieldID + "_required");
    requiredReset.setAttribute("name", "custom_field_" + fieldID + "_required");
    requiredReset.setAttribute("value", "false");
    var requiredTD = document.createElement("td");
    requiredTD.setAttribute("style", "padding: 0 5px;");
    requiredTD.setAttribute("id", containerID + "_" + fieldID + "_required_td");
    requiredTD.appendChild(requiredReset);
    requiredTD.appendChild(required);
    if (JSON.parse(settings.columns).indexOf('required') === -1) {
        requiredTD.classList.add("hidden");
    }
    return requiredTD;
}

function getPreview(fieldID, value) {
    var preview = document.createElement("input");
    preview.setAttribute("type", "checkbox");
    preview.setAttribute("id", containerID + "_" + fieldID + "_preview");
    preview.setAttribute("name", "custom_field_" + fieldID + "_preview");
    preview.setAttribute("value", "true");
    if (value) {
        preview.setAttribute("checked", "checked");
    }
    var previewReset = document.createElement("input");
    previewReset.setAttribute("type", "hidden");
    previewReset.setAttribute("id", containerID + "_" + fieldID + "_preview");
    previewReset.setAttribute("name", "custom_field_" + fieldID + "_preview");
    previewReset.setAttribute("value", "false");
    var previewTD = document.createElement("td");
    previewTD.setAttribute("style", "padding: 0 5px;");
    previewTD.setAttribute("id", containerID + "_" + fieldID + "_preview_td");
    previewTD.appendChild(previewReset);
    previewTD.appendChild(preview);
    return previewTD;
}

function getDisabled(fieldID, value) {
    var disabled = document.createElement("input");
    disabled.setAttribute("type", "checkbox");
    disabled.setAttribute("id", containerID + "_" + fieldID + "_disabled");
    disabled.setAttribute("name", "custom_field_" + fieldID + "_disabled");
    disabled.setAttribute("value", "true");
    if (value) {
        disabled.setAttribute("checked", "checked");
    }
    var disabledReset = document.createElement("input");
    disabledReset.setAttribute("type", "hidden");
    disabledReset.setAttribute("id", containerID + "_" + fieldID + "_disabled");
    disabledReset.setAttribute("name", "custom_field_" + fieldID + "_disabled");
    disabledReset.setAttribute("value", "false");
    var disabledTD = document.createElement("td");
    disabledTD.setAttribute("style", "padding: 0 5px;");
    disabledTD.setAttribute("id", containerID + "_" + fieldID + "_disabled_td");
    disabledTD.appendChild(disabledReset);
    disabledTD.appendChild(disabled);
    if (JSON.parse(settings.columns).indexOf('disabled') === -1) {
        disabledTD.classList.add("hidden");
    }
    return disabledTD;
}

function getValue(fieldID, value) {
    var valueField = document.createElement("input");
    valueField.setAttribute("id", containerID + "_" + fieldID + "_value");
    valueField.setAttribute("name", "custom_field_" + fieldID + "_value");
    valueField.setAttribute("style", "width: 100%;");
    if (value) {
        valueField.setAttribute("value", value);
    }
    valueField.setAttribute("required", "required");
    var valueTD = document.createElement("td");
    valueTD.setAttribute("style", "padding: 0 5px;");
    valueTD.setAttribute("id", containerID + "_" + fieldID + "_value_td");
    valueTD.appendChild(valueField);
    return valueTD;
}

function getDefaultValue(fieldID, value, placeholder) {
    var defaultValue = document.createElement("input");
    defaultValue.setAttribute("id", containerID + "_" + fieldID + "_default_value");
    defaultValue.setAttribute("name", "custom_field_" + fieldID + "_default_value");
    defaultValue.setAttribute("style", "width: 100%;");
    if (placeholder) {
        defaultValue.setAttribute("placeholder", placeholder);
    }
    if (value) {
        defaultValue.setAttribute("value", value);
    }
    var defaultValueTD = document.createElement("td");
    defaultValueTD.setAttribute("style", "padding: 0 5px;");
    defaultValueTD.setAttribute("id", containerID + "_" + fieldID + "_default_value_td");
    defaultValueTD.setAttribute("class", "column-default");
    defaultValueTD.appendChild(defaultValue);
    return defaultValueTD;
}

function getDefaultChecked(fieldID, value) {
    var defaultChecked = createSelect(fieldID, "_default", ["Checked", "Unchecked"], value ? "checked" : "unchecked");
    var defaultCheckedTD = document.createElement("td");
    defaultCheckedTD.setAttribute("style", "padding: 0 5px;");
    defaultCheckedTD.setAttribute("id", containerID + "_" + fieldID + "_default_td");
    defaultCheckedTD.setAttribute("class", "column-default");
    defaultCheckedTD.appendChild(defaultChecked);
    return defaultCheckedTD;
}

function getPlaceholder(fieldID, value) {
    var placeholder = document.createElement("input");
    placeholder.setAttribute("id", containerID + "_" + fieldID + "_placeholder");
    placeholder.setAttribute("name", "custom_field_" + fieldID + "_placeholder");
    placeholder.setAttribute("style", "width: 100%;");
    if (value) {
        placeholder.setAttribute("value", value);
    }
    var placeholderTD = document.createElement("td");
    placeholderTD.setAttribute("style", "padding: 0 5px;");
    placeholderTD.setAttribute("id", containerID + "_" + fieldID + "_placeholder_td");
    placeholderTD.setAttribute("class", "column-placeholder");
    if (JSON.parse(settings.columns).indexOf('placeholder') === -1) {
        placeholderTD.classList.add("hidden");
    }
    placeholderTD.appendChild(placeholder);
    return placeholderTD;
}

function getDateRange(fieldID, valueAfter, valueBefore) {
    var dateRangeAfter = document.createElement("input");
    var dateRangeBefore = document.createElement("input");
    dateRangeAfter.setAttribute("id", containerID + "_" + fieldID + "_date_range_after");
    dateRangeBefore.setAttribute("id", containerID + "_" + fieldID + "_date_range_before");
    dateRangeAfter.setAttribute("name", "custom_field_" + fieldID + "_date_range_after");
    dateRangeBefore.setAttribute("name", "custom_field_" + fieldID + "_date_range_before");
    dateRangeAfter.setAttribute("style", "width: 100%;");
    dateRangeBefore.setAttribute("style", "width: 100%;");
    dateRangeAfter.setAttribute("placeholder", "yyyy-mm-dd");
    dateRangeBefore.setAttribute("placeholder", "yyyy-mm-dd");
    if (valueAfter) {
        dateRangeAfter.setAttribute("value", valueAfter);
    }
    if (valueBefore) {
        dateRangeBefore.setAttribute("value", valueBefore);
    }
    var dateRangeTD = document.createElement("td");
    dateRangeTD.setAttribute("style", "padding: 0 5px;");
    dateRangeTD.setAttribute("id", containerID + "_" + fieldID + "_date_range_td");
    dateRangeTD.setAttribute("class", "column-placeholder");
    if (JSON.parse(settings.columns).indexOf('placeholder') === -1) {
        dateRangeTD.classList.add("hidden");
    }
    dateRangeTD.appendChild(dateRangeAfter);
    dateRangeTD.appendChild(dateRangeBefore);
    return dateRangeTD;
}

function getClass(fieldID, value) {
    var classField = document.createElement("input");
    classField.setAttribute("id", containerID + "_" + fieldID + "_class");
    classField.setAttribute("name", "custom_field_" + fieldID + "_class");
    classField.setAttribute("style", "width: 100%;");
    if (value) {
        classField.setAttribute("value", value);
    }
    var classTD = document.createElement("td");
    classTD.setAttribute("style", "padding: 0 5px;");
    classTD.setAttribute("id", containerID + "_" + fieldID + "_class_td");
    classTD.setAttribute("class", "column-class");
    if (JSON.parse(settings.columns).indexOf('class') === -1) {
        classTD.classList.add("hidden");
    }
    classTD.appendChild(classField);
    return classTD;
}

function getStyle(fieldID, value) {
    var style = document.createElement("input");
    style.setAttribute("id", containerID + "_" + fieldID + "_style");
    style.setAttribute("name", "custom_field_" + fieldID + "_style");
    style.setAttribute("style", "width: 100%;");
    if (value) {
        style.setAttribute("value", value);
    }
    var styleTD = document.createElement("td");
    styleTD.setAttribute("style", "padding: 0 5px;");
    styleTD.setAttribute("id", containerID + "_" + fieldID + "_style_td");
    styleTD.setAttribute("class", "column-style");
    if (JSON.parse(settings.columns).indexOf('style') === -1) {
        styleTD.classList.add("hidden");
    }
    styleTD.appendChild(style);
    return styleTD;
}

function getOverrideRight(fieldID, value) {
    var overrideRight = document.createElement("input");
    overrideRight.setAttribute("id", containerID + "_" + fieldID + "_override_right");
    overrideRight.setAttribute("name", "custom_field_" + fieldID + "_override_right");
    overrideRight.setAttribute("style", "width: 100%;");
    overrideRight.setAttribute("list", "capabilities");
    if (value) {
        overrideRight.setAttribute("value", value);
    }
    var overrideRightTD = document.createElement("td");
    overrideRightTD.setAttribute("style", "padding: 0 5px;");
    overrideRightTD.setAttribute("id", containerID + "_" + fieldID + "_override_right_td");
    overrideRightTD.setAttribute("class", "column-override_right");
    if (JSON.parse(settings.columns).indexOf('override_right') === -1) {
        overrideRightTD.classList.add("hidden");
    }
    overrideRightTD.appendChild(overrideRight);
    return overrideRightTD;
}

function getDeleteRow(fieldID) {
    var deleteButton = document.createElement("img");
    deleteButton.setAttribute("src", pluginBaseURL + "/general/images/icon-delete.svg");
    deleteButton.setAttribute("style", "height: 20px; margin-left: 5px;");
    deleteButton.setAttribute("onclick", "deleteRow('" + fieldID + "')");
    var deleteRowTD = document.createElement("td");
    deleteRowTD.setAttribute("style", "padding: 0 5px;");
    deleteRowTD.setAttribute("id", containerID + "_" + fieldID + "_delete_row_td");
    deleteRowTD.appendChild(deleteButton);
    return deleteRowTD;
}

function createSelect(fieldID, fieldNameExtension, options, selected) {
    var select = document.createElement("select");
    select.setAttribute("id", fieldID + fieldNameExtension);
    select.setAttribute("name", "custom_field_" + fieldID + fieldNameExtension);

    for (var i = 0; i < options.length; i++) {
        var option = document.createElement("option");
        option.setAttribute("value", options[i].toLowerCase());
        if (options[i].toLowerCase() === selected) {
            option.setAttribute("selected", "selected");
        }
        option.innerHTML = options[i];
        select.appendChild(option);
    }

    return select;
}

//noinspection JSUnusedGlobalSymbols
function createMultiSelect(fieldID, fieldNameExtension, options, selected) {
    if (selected === null) {
        selected = [];
    }
    var select = document.createElement("select");
    select.setAttribute("id", fieldID + fieldNameExtension);
    select.setAttribute("name", "custom_field_" + fieldID + fieldNameExtension + "[]");
    select.setAttribute("multiple", "multiple");

    for (var i = 0; i < options.length; i++) {
        var option = document.createElement("option");
        option.setAttribute("value", options[i].toLowerCase());
        if (jQuery.inArray(options[i].toLowerCase(), selected) !== -1) {
            option.setAttribute("selected", "selected");
        }
        option.innerHTML = options[i];
        select.appendChild(option);
    }

    return select;
}

//noinspection JSUnusedGlobalSymbols
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

//noinspection JSUnusedGlobalSymbols
function deleteRow(fieldID) {
    var tr = document.getElementById(fieldID + "_tr");
    console.log(fieldID + "_tr");
    removeField(tr);
    var index = fieldIDs.indexOf(5);
    if (index > -1) {
        fieldIDs.splice(index, 1);
    }
    event.preventDefault();
}
