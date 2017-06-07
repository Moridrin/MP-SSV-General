//noinspection JSUnresolvedVariable
var roles = JSON.parse(settings.roles);
var scripts = document.getElementsByTagName("script");
var pluginBaseURL = scripts[scripts.length - 1].src.split('/').slice(0, -3).join('/');
var fieldIDs = [];

function mp_ssv_add_custom_input_field_customizer(container, fieldID, inputType, values) {
    fieldIDs.push(fieldID);
    container = document.getElementById(container);
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

    var tr = getBaseFields(fieldID, fieldTitle, "Tab");
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

    var tr = getBaseFields(fieldID, fieldTitle, "Header");
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
    var classValue = '';
    var style = '';

    if (values) {
        fieldTitle = values['title'];
        classValue = values['class'];
        style = values['style'];
    }

    var tr = getBaseFields(fieldID, fieldTitle, "Label");
    tr.appendChild(getText(""));
    tr.appendChild(getClass(fieldID, classValue));
    tr.appendChild(getStyle(fieldID, style));
    tr.appendChild(getDeleteRow(fieldID));

    container.appendChild(tr);
}

function getTextInputField(container, fieldID, values) {
    //noinspection JSUnusedLocalSymbols
    var overrideRight = values['override_right'];
    var fieldTitle = '';
    var fieldType = 'input';
    var name = '';
    var required = false;
    var disabled = false;
    var defaultValue = '';
    var placeholder = '';
    var classValue = '';
    var style = '';
    if (Object.keys(values).length > 1) {
        fieldTitle = values['title'];
        name = values['name'];
        required = values['required'];
        disabled = values['disabled'];
        defaultValue = values['default_value'];
        placeholder = values['placeholder'];
        classValue = values['class'];
        style = values['style'];
    }

    var tr = getBaseFields(fieldID, fieldTitle, fieldType);
    tr = getTextInputFields(tr, fieldID, required, disabled, defaultValue, placeholder, classValue, style, overrideRight);
    container.appendChild(tr);
}
function getSelectInputField(container, fieldID, values) {
    //noinspection JSUnusedLocalSymbols
    var overrideRight = values['override_right'];
    var fieldTitle = '';
    var fieldType = 'input';
    var name = '';
    var disabled = false;
    var classValue = '';
    var style = '';
    if (Object.keys(values).length > 1) {
        fieldTitle = values['title'];
        name = values['name'];
        disabled = values['disabled'];
        classValue = values['class'];
        style = values['style'];
    }

    var tr = getBaseFields(fieldID, fieldTitle, fieldType);
    tr = getSelectInputFields(tr, fieldID, disabled, classValue, style, overrideRight);
    container.appendChild(tr);
}
function getCheckboxInputField(container, fieldID, values) {
    //noinspection JSUnusedLocalSymbols
    var overrideRight = values['override_right'];
    var fieldTitle = '';
    var fieldType = 'input';
    var name = '';
    var required = false;
    var disabled = false;
    var defaultChecked = '';
    var classValue = '';
    var style = '';
    if (Object.keys(values).length > 1) {
        fieldTitle = values['title'];
        name = values['name'];
        required = values['required'];
        disabled = values['disabled'];
        defaultChecked = values['default_checked'];
        classValue = values['class'];
        style = values['style'];
    }

    var tr = getBaseFields(fieldID, fieldTitle, fieldType);
    tr = getCheckboxInputFields(tr, fieldID, required, disabled, defaultChecked, classValue, style, overrideRight);
    container.appendChild(tr);
}
function getRoleCheckboxInputField(container, fieldID, values) {
    //noinspection JSUnusedLocalSymbols
    var overrideRight = values['override_right'];
    var fieldTitle = '';
    var fieldType = 'input';
    var name = '';
    var classValue = '';
    var style = '';
    if (Object.keys(values).length > 1) {
        fieldTitle = values['title'];
        name = values['name'];
        classValue = values['class'];
        style = values['style'];
    }

    var tr = getBaseFields(fieldID, fieldTitle, fieldType);
    tr = getRoleCheckboxInputFields(tr, fieldID, classValue, style, overrideRight);
    container.appendChild(tr);
}
function getRoleSelectInputField(container, fieldID, values) {
    //noinspection JSUnusedLocalSymbols
    var overrideRight = values['override_right'];
    var fieldTitle = '';
    var fieldType = 'input';
    var name = '';
    var classValue = '';
    var style = '';
    if (Object.keys(values).length > 1) {
        fieldTitle = values['title'];
        name = values['name'];
        classValue = values['class'];
        style = values['style'];
    }

    var tr = getBaseFields(fieldID, fieldTitle, fieldType);
    tr = getRoleSelectInputFields(tr, fieldID, classValue, style, overrideRight);
    container.appendChild(tr);
}
function getImageInputField(container, fieldID, values) {
    //noinspection JSUnusedLocalSymbols
    var overrideRight = values['override_right'];
    var fieldTitle = '';
    var fieldType = 'input';
    var name = '';
    var required = false;
    var classValue = '';
    var style = '';
    if (Object.keys(values).length > 1) {
        fieldTitle = values['title'];
        name = values['name'];
        required = values['required'];
        classValue = values['class'];
        style = values['style'];
    }

    var tr = getBaseFields(fieldID, fieldTitle, fieldType);
    tr = getImageInputFields(tr, fieldID, required, classValue, style, overrideRight);
    container.appendChild(tr);
}
function getHiddenInputField(container, fieldID, values) {
    //noinspection JSUnusedLocalSymbols
    var overrideRight = values['override_right'];
    var fieldTitle = '';
    var fieldType = 'input';
    var name = '';
    var defaultValue = '';
    var classValue = '';
    var style = '';
    if (Object.keys(values).length > 1) {
        fieldTitle = values['title'];
        name = values['name'];
        defaultValue = values['default_value'];
        classValue = values['class'];
        style = values['style'];
    }

    var tr = getBaseFields(fieldID, fieldTitle, fieldType);
    tr = getHiddenInputFields(tr, fieldID, defaultValue, classValue, style);
    container.appendChild(tr);
}
function getCustomInputField(container, fieldID, values) {
    //noinspection JSUnusedLocalSymbols
    var overrideRight = values['override_right'];
    var fieldTitle = '';
    var fieldType = 'input';
    var name = '';
    var required = false;
    var disabled = false;
    var defaultValue = '';
    var placeholder = '';
    var classValue = '';
    var style = '';
    if (Object.keys(values).length > 1) {
        fieldTitle = values['title'];
        name = values['name'];
        required = values['required'];
        disabled = values['disabled'];
        defaultValue = values['default_value'];
        placeholder = values['placeholder'];
        classValue = values['class'];
        style = values['style'];
    }

    var tr = getBaseFields(fieldID, fieldTitle, fieldType);
    tr = getCustomInputFields(tr, fieldID, required, disabled, defaultValue, placeholder, classValue, style, overrideRight);
    container.appendChild(tr);
}
function getDateInputField(container, fieldID, values) {
    //noinspection JSUnusedLocalSymbols
    var overrideRight = values['override_right'];
    var fieldTitle = '';
    var fieldType = 'input';
    var name = '';
    var required = false;
    var disabled = false;
    var defaultValue = '';
    var dateRangeAfter = '';
    var dateRangeBefore = '';
    var classValue = '';
    var style = '';
    if (Object.keys(values).length > 1) {
        fieldTitle = values['title'];
        name = values['name'];
        required = values['required'];
        disabled = values['disabled'];
        defaultValue = values['default_value'];
        dateRangeAfter = values['date_range_after'];
        dateRangeBefore = values['date_range_before'];
        classValue = values['class'];
        style = values['style'];
    }

    var tr = getBaseFields(fieldID, fieldTitle, fieldType);
    tr = getDateInputFields(tr, fieldID, required, disabled, defaultValue, dateRangeAfter, dateRangeBefore, classValue, style, overrideRight);
    container.appendChild(tr);
}

function getBaseFields(fieldID, fieldTitle, placeholder) {
    var tr = document.createElement("tr");
    tr.setAttribute("id", fieldID + "_tr");
    tr.appendChild(getDraggable(fieldID));
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
    td.setAttribute("style", "padding: 0;");
    td.setAttribute("class", fieldID + "_empty_td");
    if (columnClass) {
        td.classList.add('column-' + columnClass);
        if (JSON.parse(settings.columns).indexOf(columnClass) === -1) {
            td.classList.add("hidden");
        }
    }
    return td;
}
function getStart(fieldID, isTab) {
    var fieldIDs = document.createElement("input");
    fieldIDs.setAttribute("type", "hidden");
    fieldIDs.setAttribute("name", "field_ids[]");
    fieldIDs.setAttribute("value", fieldID);
    var startTD = document.createElement("td");
    startTD.setAttribute("style", "padding: 0;");
    if (isTab) {
        startTD.setAttribute("style", "border-left: solid;");
    }
    startTD.setAttribute("id", fieldID + "_start_td");
    startTD.appendChild(fieldIDs);
    return startTD;
}
function getDraggable(fieldID) {
    var fieldIDElement = document.createElement("input");
    fieldIDElement.setAttribute("type", "hidden");
    fieldIDElement.setAttribute("id", fieldID + "_id");
    fieldIDElement.setAttribute("name", "custom_field_" + fieldID + "_id");
    fieldIDElement.setAttribute("value", fieldID);
    var draggableIcon = document.createElement("img");
    draggableIcon.setAttribute("src", pluginBaseURL + '/general/images/icon-menu.svg');
    draggableIcon.setAttribute("style", "padding-right: 15px; margin: 10px 0;");
    var draggableIconTD = document.createElement("td");
    draggableIconTD.setAttribute("id", fieldID + "_draggable_td");
    draggableIconTD.setAttribute("style", "vertical-align: middle; cursor: move;");
    draggableIconTD.appendChild(fieldIDElement);
    draggableIconTD.appendChild(draggableIcon);
    return draggableIconTD;
}
function getFieldTitle(fieldID, value, placeholder) {
    var fieldTitle = document.createElement("input");
    fieldTitle.setAttribute("id", fieldID + "_title");
    fieldTitle.setAttribute("name", "custom_field_" + fieldID + "_title");
    fieldTitle.setAttribute("style", "width: 100%;");
    if (value) {
        fieldTitle.setAttribute("value", value);
    }
    if (placeholder) {
        fieldTitle.setAttribute("placeholder", placeholder);
    }
    var fieldTitleTD = document.createElement("td");
    fieldTitleTD.setAttribute("style", "padding: 0;");
    fieldTitleTD.setAttribute("id", fieldID + "_field_title_td");
    fieldTitleTD.appendChild(fieldTitle);
    return fieldTitleTD;
}
function getText(fieldID, value) {
    var fieldTitle = document.createElement("textarea");
    fieldTitle.setAttribute("id", fieldID + "_text");
    fieldTitle.setAttribute("name", "custom_field_" + fieldID + "_text");
    fieldTitle.setAttribute("style", "width: 100%;");
    if (value) {
        fieldTitle.innerHTML = value;
    }
    var fieldTitleTD = document.createElement("td");
    fieldTitleTD.setAttribute("class", "textarea_td");
    fieldTitleTD.setAttribute("style", "padding: 0;");
    fieldTitleTD.setAttribute("id", fieldID + "_text_td");
    var selected = JSON.parse(settings.columns);
    var textAreaColspan = 1;
    if (selected.indexOf("placeholder") !== -1) {
        textAreaColspan++;
    }
    if (selected.indexOf("class") !== -1) {
        textAreaColspan++;
    }
    if (selected.indexOf("style") !== -1) {
        textAreaColspan++;
    }
    if (selected.indexOf("override_right") !== -1) {
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
    required.setAttribute("id", fieldID + "_required");
    required.setAttribute("name", "custom_field_" + fieldID + "_required");
    required.setAttribute("value", "true");
    if (value) {
        required.setAttribute("checked", "checked");
    }
    var requiredReset = document.createElement("input");
    requiredReset.setAttribute("type", "hidden");
    requiredReset.setAttribute("id", fieldID + "_required");
    requiredReset.setAttribute("name", "custom_field_" + fieldID + "_required");
    requiredReset.setAttribute("value", "false");
    var requiredTD = document.createElement("td");
    requiredTD.setAttribute("style", "padding: 0;");
    requiredTD.setAttribute("id", fieldID + "_required_td");
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
    preview.setAttribute("id", fieldID + "_preview");
    preview.setAttribute("name", "custom_field_" + fieldID + "_preview");
    preview.setAttribute("value", "true");
    if (value) {
        preview.setAttribute("checked", "checked");
    }
    var previewReset = document.createElement("input");
    previewReset.setAttribute("type", "hidden");
    previewReset.setAttribute("id", fieldID + "_preview");
    previewReset.setAttribute("name", "custom_field_" + fieldID + "_preview");
    previewReset.setAttribute("value", "false");
    var previewTD = document.createElement("td");
    previewTD.setAttribute("style", "padding: 0;");
    previewTD.setAttribute("id", fieldID + "_preview_td");
    previewTD.appendChild(previewReset);
    previewTD.appendChild(preview);
    return previewTD;
}
function getDisabled(fieldID, value) {
    var disabled = document.createElement("input");
    disabled.setAttribute("type", "checkbox");
    disabled.setAttribute("id", fieldID + "_disabled");
    disabled.setAttribute("name", "custom_field_" + fieldID + "_disabled");
    disabled.setAttribute("value", "true");
    if (value) {
        disabled.setAttribute("checked", "checked");
    }
    var disabledReset = document.createElement("input");
    disabledReset.setAttribute("type", "hidden");
    disabledReset.setAttribute("id", fieldID + "_disabled");
    disabledReset.setAttribute("name", "custom_field_" + fieldID + "_disabled");
    disabledReset.setAttribute("value", "false");
    var disabledTD = document.createElement("td");
    disabledTD.setAttribute("style", "padding: 0;");
    disabledTD.setAttribute("id", fieldID + "_disabled_td");
    disabledTD.appendChild(disabledReset);
    disabledTD.appendChild(disabled);
    if (JSON.parse(settings.columns).indexOf('disabled') === -1) {
        disabledTD.classList.add("hidden");
    }
    return disabledTD;
}
function getValue(fieldID, value) {
    var valueField = document.createElement("input");
    valueField.setAttribute("id", fieldID + "_value");
    valueField.setAttribute("name", "custom_field_" + fieldID + "_value");
    valueField.setAttribute("style", "width: 100%;");
    if (value) {
        valueField.setAttribute("value", value);
    }
    valueField.setAttribute("required", "required");
    var valueTD = document.createElement("td");
    valueTD.setAttribute("style", "padding: 0;");
    valueTD.setAttribute("id", fieldID + "_value_td");
    valueTD.appendChild(valueField);
    return valueTD;
}
function getDefaultValue(fieldID, value, placeholder) {
    var defaultValue = document.createElement("input");
    defaultValue.setAttribute("id", fieldID + "_default_value");
    defaultValue.setAttribute("name", "custom_field_" + fieldID + "_default_value");
    defaultValue.setAttribute("style", "width: 100%;");
    if (placeholder) {
        defaultValue.setAttribute("placeholder", placeholder);
    }
    if (value) {
        defaultValue.setAttribute("value", value);
    }
    var defaultValueTD = document.createElement("td");
    defaultValueTD.setAttribute("style", "padding: 0;");
    defaultValueTD.setAttribute("id", fieldID + "_default_value_td");
    defaultValueTD.setAttribute("class", "column-default");
    defaultValueTD.appendChild(defaultValue);
    return defaultValueTD;
}
function getDefaultChecked(fieldID, value) {
    var defaultChecked = createSelect(fieldID, "_default", ["Checked", "Unchecked"], value ? "checked" : "unchecked");
    var defaultCheckedTD = document.createElement("td");
    defaultCheckedTD.setAttribute("style", "padding: 0;");
    defaultCheckedTD.setAttribute("id", fieldID + "_default_td");
    defaultCheckedTD.setAttribute("class", "column-default");
    defaultCheckedTD.appendChild(defaultChecked);
    return defaultCheckedTD;
}
function getPlaceholder(fieldID, value) {
    var placeholder = document.createElement("input");
    placeholder.setAttribute("id", fieldID + "_placeholder");
    placeholder.setAttribute("name", "custom_field_" + fieldID + "_placeholder");
    placeholder.setAttribute("style", "width: 100%;");
    if (value) {
        placeholder.setAttribute("value", value);
    }
    var placeholderTD = document.createElement("td");
    placeholderTD.setAttribute("style", "padding: 0;");
    placeholderTD.setAttribute("id", fieldID + "_placeholder_td");
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
    dateRangeAfter.setAttribute("id", fieldID + "_date_range_after");
    dateRangeBefore.setAttribute("id", fieldID + "_date_range_before");
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
    dateRangeTD.setAttribute("style", "padding: 0;");
    dateRangeTD.setAttribute("id", fieldID + "_date_range_td");
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
    classField.setAttribute("id", fieldID + "_class");
    classField.setAttribute("name", "custom_field_" + fieldID + "_class");
    classField.setAttribute("style", "width: 100%;");
    if (value) {
        classField.setAttribute("value", value);
    }
    var classTD = document.createElement("td");
    classTD.setAttribute("style", "padding: 0;");
    classTD.setAttribute("id", fieldID + "_class_td");
    classTD.setAttribute("class", "column-class");
    if (JSON.parse(settings.columns).indexOf('class') === -1) {
        classTD.classList.add("hidden");
    }
    classTD.appendChild(classField);
    return classTD;
}
function getStyle(fieldID, value) {
    var style = document.createElement("input");
    style.setAttribute("id", fieldID + "_style");
    style.setAttribute("name", "custom_field_" + fieldID + "_style");
    style.setAttribute("style", "width: 100%;");
    if (value) {
        style.setAttribute("value", value);
    }
    var styleTD = document.createElement("td");
    styleTD.setAttribute("style", "padding: 0;");
    styleTD.setAttribute("id", fieldID + "_style_td");
    styleTD.setAttribute("class", "column-style");
    if (JSON.parse(settings.columns).indexOf('style') === -1) {
        styleTD.classList.add("hidden");
    }
    styleTD.appendChild(style);
    return styleTD;
}
function getOverrideRight(fieldID, value) {
    var overrideRight = document.createElement("input");
    overrideRight.setAttribute("id", fieldID + "_override_right");
    overrideRight.setAttribute("name", "custom_field_" + fieldID + "_override_right");
    overrideRight.setAttribute("style", "width: 100%;");
    overrideRight.setAttribute("list", "capabilities");
    if (value) {
        overrideRight.setAttribute("value", value);
    }
    var overrideRightTD = document.createElement("td");
    overrideRightTD.setAttribute("style", "padding: 0;");
    overrideRightTD.setAttribute("id", fieldID + "_override_right_td");
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
    deleteButton.setAttribute("style", "height: 20px; margin-top: 25px; margin-left: 5px;");
    deleteButton.setAttribute("onclick", "deleteRow('" + fieldID + "')");
    var deleteRowTD = document.createElement("td");
    deleteRowTD.setAttribute("style", "padding: 0;");
    deleteRowTD.setAttribute("id", fieldID + "_delete_row_td");
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

function columnsChanged() {
    var columns = event.srcElement.options;
    var selected = getSelectValues(event.srcElement);
    var i;
    var j;
    for (i = 0; i < columns.length; i++) {
        var columnElements = document.getElementsByClassName("column-" + columns[i].text);
        for (j = 0; j < columnElements.length; j++) {
            if (selected.indexOf(columns[i].text) !== -1) {
                columnElements[j].classList.remove("hidden");
            } else {
                columnElements[j].classList.add("hidden");
            }
        }
    }

    var textColumns = document.getElementById(containerID).getElementsByClassName("textarea_td");
    var textAreaColspan = 4;
    if (selected.indexOf("placeholder") !== -1) {
        textAreaColspan++;
    }
    for (i = 0; i < textColumns.length; i++) {
        textColumns[i].setAttribute("colspan", textAreaColspan);
    }

    var optionsColumns = document.getElementById(containerID).getElementsByClassName("options_td");
    var optionsColspan = 1;
    if (selected.indexOf("placeholder") !== -1) {
        optionsColspan++;
    }
    for (i = 0; i < optionsColumns.length; i++) {
        optionsColumns[i].setAttribute("colspan", optionsColspan);
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
