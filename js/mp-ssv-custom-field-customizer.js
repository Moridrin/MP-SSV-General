//noinspection JSUnresolvedVariable
var roles = JSON.parse(settings.roles);
var scripts = document.getElementsByTagName("script");
var pluginBaseURL = scripts[scripts.length - 1].src.split('/').slice(0, -3).join('/');
var fieldIDs = [];
var containerID = '';

function mp_ssv_add_custom_field_customizer(container, fieldType, inputType, fieldID, values, allowTabs) {
    fieldIDs.push(fieldID);
    containerID = container;
    container = document.getElementById(container);
    if (typeof values === 'undefined' || values === null) {
        values = [];
    }
    if (fieldType === 'tab') {
        getTabField(container, fieldID, values, allowTabs);
    } else if (fieldType === 'header') {
        getHeaderField(container, fieldID, values, allowTabs);
    } else if (fieldType === 'input') {
        if (inputType === 'text') {
            getTextInputField(container, fieldID, values, allowTabs);
        } else if (inputType === 'select') {
            getSelectInputField(container, fieldID, values, allowTabs);
        } else if (inputType === 'checkbox') {
            getCheckboxInputField(container, fieldID, values, allowTabs);
        } else if (inputType === 'role_checkbox') {
            getRoleCheckboxInputField(container, fieldID, values, allowTabs);
        } else if (inputType === 'role_select') {
            getRoleSelectInputField(container, fieldID, values, allowTabs);
        } else if (inputType === 'date') {
            getDateInputField(container, fieldID, values, allowTabs);
        } else if (inputType === 'image') {
            getImageInputField(container, fieldID, values, allowTabs);
        } else if (inputType === 'hidden') {
            getHiddenInputField(container, fieldID, values, allowTabs);
        } else {
            getCustomInputField(container, inputType, fieldID, values, allowTabs);
        }
    } else if (fieldType === 'label') {
        getLabelField(container, fieldID, values, allowTabs);
    }
}

function getTabField(container, fieldID, values, allowTabs) {
    var fieldType = 'tab';
    var fieldTitle = '';
    var classValue = '';
    var style = '';
    var fields = {};
    if (typeof values !== 'undefined') {
        fieldTitle = values['title'];
        classValue = values['class'];
        style = values['style'];
        fields = values['fields'];
    }

    var tr = getBaseFields(fieldID, fieldTitle, fieldType, allowTabs);
    tr.appendChild(getEmpty(fieldID));
    tr.appendChild(getEmpty(fieldID));
    tr.appendChild(getEmpty(fieldID));
    tr.appendChild(getEmpty(fieldID));
    tr.appendChild(getEmpty(fieldID, "column-default"));
    tr.appendChild(getEmpty(fieldID, "column-placeholder"));
    tr.appendChild(getClass(fieldID, classValue));
    tr.appendChild(getStyle(fieldID, style));
    tr.appendChild(getEmpty(fieldID, 'column-override_right'));
    tr.appendChild(getDeleteRow(fieldID));

    container.appendChild(tr);
    for (var i in fields) {
        mp_ssv_add_custom_field(container, fields[i]['field_type'], fields[i]['input_type'], fields[i]['id'], fields[i], allowTabs);
    }
}
function getHeaderField(container, fieldID, values, allowTabs) {
    var fieldTitle = '';
    var fieldType = 'header';
    var classValue = '';
    var style = '';

    if (Object.keys(values).length > 1) {
        fieldTitle = values['title'];
        classValue = values['class'];
        style = values['style'];
    }

    var tr = getBaseFields(fieldID, fieldTitle, fieldType, allowTabs);
    tr.appendChild(getEmpty(fieldID));
    tr.appendChild(getEmpty(fieldID));
    tr.appendChild(getEmpty(fieldID));
    tr.appendChild(getEmpty(fieldID));
    tr.appendChild(getEmpty(fieldID, "column-default"));
    tr.appendChild(getEmpty(fieldID, "column-placeholder"));
    tr.appendChild(getClass(fieldID, classValue));
    tr.appendChild(getStyle(fieldID, style));
    tr.appendChild(getEmpty(fieldID, 'column-override_right'));
    tr.appendChild(getDeleteRow(fieldID));

    container.appendChild(tr);
}
function getTextInputField(container, fieldID, values, allowTabs) {
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

    var tr = getBaseFields(fieldID, fieldTitle, fieldType, allowTabs);
    tr = getTextInputFields(tr, fieldID, name, required, disabled, defaultValue, placeholder, classValue, style, overrideRight);
    container.appendChild(tr);
}
function getSelectInputField(container, fieldID, values, allowTabs) {
    //noinspection JSUnusedLocalSymbols
    var overrideRight = values['override_right'];
    var fieldTitle = '';
    var fieldType = 'input';
    var name = '';
    var options = '';
    var disabled = false;
    var classValue = '';
    var style = '';
    if (Object.keys(values).length > 1) {
        fieldTitle = values['title'];
        name = values['name'];
        options = values['options'];
        disabled = values['disabled'];
        classValue = values['class'];
        style = values['style'];
    }

    var tr = getBaseFields(fieldID, fieldTitle, fieldType, allowTabs);
    tr = getSelectInputFields(tr, fieldID, name, options, disabled, classValue, style, overrideRight);
    container.appendChild(tr);
}
function getCheckboxInputField(container, fieldID, values, allowTabs) {
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

    var tr = getBaseFields(fieldID, fieldTitle, fieldType, allowTabs);
    tr = getCheckboxInputFields(tr, fieldID, name, required, disabled, defaultChecked, classValue, style, overrideRight);
    container.appendChild(tr);
}
function getRoleCheckboxInputField(container, fieldID, values, allowTabs) {
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

    var tr = getBaseFields(fieldID, fieldTitle, fieldType, allowTabs);
    tr = getRoleCheckboxInputFields(tr, fieldID, name, classValue, style, overrideRight);
    container.appendChild(tr);
}
function getRoleSelectInputField(container, fieldID, values, allowTabs) {
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
        options = values['options'];
        classValue = values['class'];
        style = values['style'];
    }

    var tr = getBaseFields(fieldID, fieldTitle, fieldType, allowTabs);
    tr = getRoleSelectInputFields(tr, fieldID, name, options, classValue, style, overrideRight);
    container.appendChild(tr);
}
function getImageInputField(container, fieldID, values, allowTabs) {
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

    var tr = getBaseFields(fieldID, fieldTitle, fieldType, allowTabs);
    tr = getImageInputFields(tr, fieldID, name, required, classValue, style, overrideRight);
    container.appendChild(tr);
}
function getHiddenInputField(container, fieldID, values, allowTabs) {
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

    var tr = getBaseFields(fieldID, fieldTitle, fieldType, allowTabs);
    tr = getHiddenInputFields(tr, fieldID, name, defaultValue, classValue, style);
    container.appendChild(tr);
}
function getCustomInputField(container, inputType, fieldID, values, allowTabs) {
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

    var tr = getBaseFields(fieldID, fieldTitle, fieldType, allowTabs);
    tr = getCustomInputFields(tr, fieldID, inputType, name, required, disabled, defaultValue, placeholder, classValue, style, overrideRight);
    container.appendChild(tr);
}
function getDateInputField(container, fieldID, values, allowTabs) {
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

    var tr = getBaseFields(fieldID, fieldTitle, fieldType, allowTabs);
    tr = getDateInputFields(tr, fieldID, name, required, disabled, defaultValue, dateRangeAfter, dateRangeBefore, classValue, style, overrideRight);
    container.appendChild(tr);
}
function getLabelField(container, fieldID, values, allowTabs) {
    //noinspection JSUnusedLocalSymbols
    var overrideRight = values['override_right'];
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

    var tr = getBaseFields(fieldID, fieldTitle, fieldType, allowTabs);
    tr.appendChild(getText(fieldID, text));
    tr.appendChild(getClass(fieldID, classValue));
    tr.appendChild(getStyle(fieldID, style));
    tr.appendChild(getEmpty(fieldID, 'column-override_right'));
    tr.appendChild(getDeleteRow(fieldID));

    container.appendChild(tr);
}

function getBaseFields(fieldID, fieldTitle) {
    var tr = document.createElement("tr");
    tr.setAttribute("id", fieldID + "_tr");
    tr.appendChild(getStart(fieldID));
    tr.appendChild(getFieldID(fieldID));
    tr.appendChild(getDraggable(fieldID));
    tr.appendChild(getFieldTitle(fieldID, fieldTitle));
    return tr;
}
function getTextInputFields(tr, fieldID, name, required, disabled, defaultValue, placeholder, classValue, style, overrideRight) {
    tr.appendChild(getDisabled(fieldID, disabled));
    tr.appendChild(getRequired(fieldID, required));
    tr.appendChild(getDefaultValue(fieldID, defaultValue));
    tr.appendChild(getPlaceholder(fieldID, placeholder));
    tr.appendChild(getClass(fieldID, classValue));
    tr.appendChild(getStyle(fieldID, style));
    tr.appendChild(getOverrideRight(fieldID, overrideRight));
    tr.appendChild(getDeleteRow(fieldID));
    return tr;
}
function getSelectInputFields(tr, fieldID, name, options, disabled, classValue, style) {
    tr.appendChild(getDisabled(fieldID, disabled));
    tr.appendChild(getOptions(fieldID, options, "column-placeholder"));
    tr.appendChild(getClass(fieldID, classValue));
    tr.appendChild(getStyle(fieldID, style));
    tr.appendChild(getEmpty(fieldID, 'column-override_right', style));
    tr.appendChild(getDeleteRow(fieldID));
    return tr;
}
function getCheckboxInputFields(tr, fieldID, name, required, disabled, defaultChecked, classValue, style, overrideRight) {
    tr.appendChild(getDisabled(fieldID, disabled));
    tr.appendChild(getRequired(fieldID, required));
    tr.appendChild(getDefaultSelected(fieldID, defaultChecked));
    tr.appendChild(getEmpty(fieldID));
    tr.appendChild(getClass(fieldID, classValue));
    tr.appendChild(getStyle(fieldID, style));
    tr.appendChild(getOverrideRight(fieldID, overrideRight));
    tr.appendChild(getDeleteRow(fieldID));
    return tr;
}
function getRoleCheckboxInputFields(tr, fieldID, role, classValue, style, overrideRight) {
    tr.appendChild(getRoleCheckbox(fieldID, role));
    tr.appendChild(getEmpty(fieldID));
    tr.appendChild(getEmpty(fieldID));
    tr.appendChild(getEmpty(fieldID));
    tr.appendChild(getEmpty(fieldID));
    tr.appendChild(getClass(fieldID, classValue));
    tr.appendChild(getStyle(fieldID, style));
    tr.appendChild(getOverrideRight(fieldID, overrideRight));
    tr.appendChild(getDeleteRow(fieldID));
    return tr;
}
function getRoleSelectInputFields(tr, fieldID, name, role, classValue, style, overrideRight) {
    tr.appendChild(getRoleSelect(fieldID, role));
    tr.appendChild(getEmpty(fieldID));
    tr.appendChild(getEmpty(fieldID));
    tr.appendChild(getEmpty(fieldID));
    tr.appendChild(getClass(fieldID, classValue));
    tr.appendChild(getStyle(fieldID, style));
    tr.appendChild(getOverrideRight(fieldID, overrideRight));
    tr.appendChild(getDeleteRow(fieldID));
    return tr;
}
function getImageInputFields(tr, fieldID, name, required, classValue, style, overrideRight) {
    tr.appendChild(getPreview(fieldID, required));
    tr.appendChild(getRequired(fieldID, required));
    tr.appendChild(getEmpty(fieldID));
    tr.appendChild(getEmpty(fieldID));
    tr.appendChild(getClass(fieldID, classValue));
    tr.appendChild(getStyle(fieldID, style));
    tr.appendChild(getOverrideRight(fieldID, overrideRight));
    tr.appendChild(getDeleteRow(fieldID));
    return tr;
}
function getHiddenInputFields(tr, fieldID, name, defaultValue, classValue, style) {
    tr.appendChild(getEmpty(fieldID));
    tr.appendChild(getEmpty(fieldID));
    tr.appendChild(getDefaultValue(fieldID, defaultValue));
    tr.appendChild(getEmpty(fieldID, "column-placeholder"));
    tr.appendChild(getClass(fieldID, classValue));
    tr.appendChild(getStyle(fieldID, style));
    tr.appendChild(getEmpty(fieldID, 'column-override_right'));
    tr.appendChild(getDeleteRow(fieldID));
    return tr;
}
function getCustomInputFields(tr, fieldID, inputType, name, required, disabled, defaultValue, placeholder, classValue, style, overrideRight) {
    tr.appendChild(getDisabled(fieldID, disabled));
    tr.appendChild(getRequired(fieldID, required));
    tr.appendChild(getDefaultValue(fieldID, defaultValue));
    tr.appendChild(getPlaceholder(fieldID, placeholder));
    tr.appendChild(getClass(fieldID, classValue));
    tr.appendChild(getStyle(fieldID, style));
    tr.appendChild(getOverrideRight(fieldID, overrideRight));
    tr.appendChild(getDeleteRow(fieldID));
    return tr;
}
function getDateInputFields(tr, fieldID, name, required, disabled, defaultValue, dateRangeAfter, dateRangeBefore, classValue, style, overrideRight) {
    tr.appendChild(getDisabled(fieldID, disabled));
    tr.appendChild(getRequired(fieldID, required));
    tr.appendChild(getDefaultValue(fieldID, defaultValue, 'yyyy-mm-dd'));
    tr.appendChild(getDateRange(fieldID, dateRangeAfter, dateRangeBefore));
    tr.appendChild(getClass(fieldID, classValue));
    tr.appendChild(getStyle(fieldID, style));
    tr.appendChild(getOverrideRight(fieldID, overrideRight));
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
        td.classList.add(columnClass);
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
function getFieldID(fieldID) {
    var fieldIDElement = document.createElement("input");
    fieldIDElement.setAttribute("type", "hidden");
    fieldIDElement.setAttribute("id", fieldID + "_id");
    fieldIDElement.setAttribute("name", "custom_field_" + fieldID + "_id");
    fieldIDElement.setAttribute("value", fieldID);
    var fieldIDTD = document.createElement("td");
    fieldIDTD.setAttribute("style", "padding: 0;");
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
function getFieldTitle(fieldID, value) {
    var fieldTitle = document.createElement("input");
    fieldTitle.setAttribute("id", fieldID + "_title");
    fieldTitle.setAttribute("name", "custom_field_" + fieldID + "_title");
    fieldTitle.setAttribute("style", "width: 100%;");
    if (value) {
        fieldTitle.setAttribute("value", value);
    }
    var fieldTitleLabel = document.createElement("label");
    fieldTitleLabel.setAttribute("style", "white-space: nowrap;");
    fieldTitleLabel.setAttribute("for", fieldID + "_field_title");
    fieldTitleLabel.innerHTML = "Field Title";
    var fieldTitleTD = document.createElement("td");
    fieldTitleTD.setAttribute("style", "padding: 0;");
    fieldTitleTD.setAttribute("id", fieldID + "_field_title_td");
    fieldTitleTD.appendChild(fieldTitleLabel);
    fieldTitleTD.appendChild(getBR());
    fieldTitleTD.appendChild(fieldTitle);
    return fieldTitleTD;
}
function getText(fieldID, value) {
    var fieldTitle = document.createElement("textarea");
    fieldTitle.setAttribute("id", fieldID + "_text");
    fieldTitle.setAttribute("name", "custom_field_" + fieldID + "_text");
    fieldTitle.setAttribute("style", "width: 100%;");
    fieldTitle.innerHTML = value;
    var fieldTitleLabel = document.createElement("label");
    fieldTitleLabel.setAttribute("style", "white-space: nowrap;");
    fieldTitleLabel.setAttribute("for", fieldID + "_text");
    fieldTitleLabel.innerHTML = "Text";
    var fieldTitleTD = document.createElement("td");
    fieldTitleTD.setAttribute("class", "textarea_td");
    fieldTitleTD.setAttribute("style", "padding: 0;");
    fieldTitleTD.setAttribute("id", fieldID + "_text_td");
    var selected = JSON.parse(settings.columns);
    var textAreaColspan = 4;
    if (selected.indexOf("default") !== -1) {
        textAreaColspan++;
    }
    if (selected.indexOf("placeholder") !== -1) {
        textAreaColspan++;
    }
    fieldTitleTD.setAttribute("colspan", textAreaColspan.toString());
    fieldTitleTD.appendChild(fieldTitleLabel);
    fieldTitleTD.appendChild(getBR());
    fieldTitleTD.appendChild(fieldTitle);
    return fieldTitleTD;
}
function getRoleCheckbox(fieldID, value) {
    var inputType = createSelect(fieldID, "_name", roles, value);
    inputType.setAttribute("style", "width: 100%;");
    var inputTypeLabel = document.createElement("label");
    inputTypeLabel.setAttribute("style", "white-space: nowrap;");
    inputTypeLabel.setAttribute("for", fieldID + "_name");
    inputTypeLabel.innerHTML = "Role";
    var inputTypeTD = document.createElement("td");
    inputTypeTD.setAttribute("style", "padding: 0;");
    inputTypeTD.setAttribute("id", fieldID + "_name_td");
    inputTypeTD.appendChild(inputTypeLabel);
    inputTypeTD.appendChild(getBR());
    inputTypeTD.appendChild(inputType);
    return inputTypeTD;
}
function getRoleSelect(fieldID, value) {
    var inputType = createMultiSelect(fieldID, "_options", roles, value);
    inputType.setAttribute("style", "width: 100%;");
    var inputTypeLabel = document.createElement("label");
    inputTypeLabel.setAttribute("style", "white-space: nowrap;");
    inputTypeLabel.setAttribute("for", fieldID + "_options");
    inputTypeLabel.innerHTML = "Role";
    var inputTypeTD = document.createElement("td");
    inputTypeTD.setAttribute("style", "padding: 0;");
    inputTypeTD.setAttribute("id", fieldID + "_options_td");
    inputTypeTD.appendChild(inputTypeLabel);
    inputTypeTD.appendChild(getBR());
    inputTypeTD.appendChild(inputType);
    return inputTypeTD;
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
    var requiredLabel = document.createElement("label");
    requiredLabel.setAttribute("style", "white-space: nowrap;");
    requiredLabel.setAttribute("for", fieldID + "_required");
    requiredLabel.innerHTML = "Required";
    var requiredTD = document.createElement("td");
    requiredTD.setAttribute("style", "padding: 0;");
    requiredTD.setAttribute("id", fieldID + "_required_td");
    requiredTD.appendChild(requiredLabel);
    requiredTD.appendChild(getBR());
    requiredTD.appendChild(requiredReset);
    requiredTD.appendChild(required);
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
    var previewLabel = document.createElement("label");
    previewLabel.setAttribute("style", "white-space: nowrap;");
    previewLabel.setAttribute("for", fieldID + "_preview");
    previewLabel.innerHTML = "Preview";
    var previewTD = document.createElement("td");
    previewTD.setAttribute("style", "padding: 0;");
    previewTD.setAttribute("id", fieldID + "_preview_td");
    previewTD.appendChild(previewLabel);
    previewTD.appendChild(getBR());
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
    var disabledLabel = document.createElement("label");
    disabledLabel.setAttribute("style", "white-space: nowrap;");
    disabledLabel.setAttribute("for", fieldID + "_disabled");
    disabledLabel.innerHTML = "Disabled";
    var disabledTD = document.createElement("td");
    disabledTD.setAttribute("style", "padding: 0;");
    disabledTD.setAttribute("id", fieldID + "_disabled_td");
    disabledTD.appendChild(disabledLabel);
    disabledTD.appendChild(getBR());
    disabledTD.appendChild(disabledReset);
    disabledTD.appendChild(disabled);
    return disabledTD;
}
function getOptions(fieldID, value) {
    var options = document.createElement("input");
    options.setAttribute("id", fieldID + "_options");
    options.setAttribute("name", "custom_field_" + fieldID + "_options");
    options.setAttribute("style", "width: 100%;");
    if (value) {
        options.setAttribute("value", value);
    }
    options.setAttribute("required", "required");
    options.setAttribute("placeholder", "Separate with ','");
    var optionsLabel = document.createElement("label");
    optionsLabel.setAttribute("style", "white-space: nowrap;");
    optionsLabel.setAttribute("for", fieldID + "_options");
    optionsLabel.innerHTML = "Options";
    var optionsTD = document.createElement("td");
    optionsTD.setAttribute("class", "options_td");
    optionsTD.setAttribute("style", "padding: 0;");
    optionsTD.setAttribute("id", fieldID + "_options_td");
    var selected = JSON.parse(settings.columns);
    var optionsColspan = 1;
    if (selected.indexOf("default") !== -1) {
        optionsColspan++;
    }
    if (selected.indexOf("placeholder") !== -1) {
        optionsColspan++;
    }
    optionsTD.setAttribute("colspan", optionsColspan.toString());
    optionsTD.appendChild(optionsLabel);
    optionsTD.appendChild(getBR());
    optionsTD.appendChild(options);
    return optionsTD;
}
function getDefaultValue(fieldID, value, placeholder) {
    var defaultValue = document.createElement("input");
    var show = JSON.parse(settings.columns).indexOf('default') !== -1;
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
    if (!show) {
        defaultValueTD.classList.add("hidden");
    }
    var defaultValueLabel = document.createElement("label");
    defaultValueLabel.setAttribute("style", "white-space: nowrap;");
    defaultValueLabel.setAttribute("for", fieldID + "_default_value");
    defaultValueLabel.innerHTML = "Default Value";
    defaultValueTD.appendChild(defaultValueLabel);
    defaultValueTD.appendChild(getBR());
    defaultValueTD.appendChild(defaultValue);
    return defaultValueTD;
}
function getDefaultSelected(fieldID, value) {
    var defaultSelected = document.createElement("input");
    var show = JSON.parse(settings.columns).indexOf('default') !== -1;
    defaultSelected.setAttribute("type", "checkbox");
    defaultSelected.setAttribute("id", fieldID + "_default_checked");
    defaultSelected.setAttribute("name", "custom_field_" + fieldID + "_default_checked");
    defaultSelected.setAttribute("value", "true");
    if (value) {
        defaultSelected.setAttribute("checked", "checked");
    }
    var defaultSelectedReset = document.createElement("input");
    defaultSelectedReset.setAttribute("type", "hidden");
    defaultSelectedReset.setAttribute("id", fieldID + "_default_checked");
    defaultSelectedReset.setAttribute("name", "custom_field_" + fieldID + "_default_checked");
    defaultSelectedReset.setAttribute("value", "false");
    var requiredTD = document.createElement("td");
    requiredTD.setAttribute("style", "padding: 0;");
    requiredTD.setAttribute("id", fieldID + "_default_checked_td");
    requiredTD.setAttribute("class", "column-default");
    if (!show) {
        requiredTD.classList.add("hidden");
    }
    var requiredLabel = document.createElement("label");
    requiredLabel.setAttribute("style", "white-space: nowrap;");
    requiredLabel.setAttribute("for", fieldID + "_default_checked");
    requiredLabel.innerHTML = "Default Selected";
    requiredTD.appendChild(requiredLabel);
    requiredTD.appendChild(getBR());
    requiredTD.appendChild(defaultSelectedReset);
    requiredTD.appendChild(defaultSelected);
    return requiredTD;
}
function getPlaceholder(fieldID, value) {
    var placeholder = document.createElement("input");
    var show = JSON.parse(settings.columns).indexOf('placeholder') !== -1;
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
    if (!show) {
        placeholderTD.classList.add("hidden");
    }
    var placeholderLabel = document.createElement("label");
    placeholderLabel.setAttribute("style", "white-space: nowrap;");
    placeholderLabel.setAttribute("for", fieldID + "_placeholder");
    placeholderLabel.innerHTML = "Placeholder";
    placeholderTD.appendChild(placeholderLabel);
    placeholderTD.appendChild(getBR());
    placeholderTD.appendChild(placeholder);
    return placeholderTD;
}
function getDateRange(fieldID, valueAfter, valueBefore) {
    var show = JSON.parse(settings.columns).indexOf('placeholder') !== -1;
    var dateRangeAfter = document.createElement("input");
    var dateRangeBefore = document.createElement("input");
    dateRangeAfter.setAttribute("id", fieldID + "_date_range_after");
    dateRangeBefore.setAttribute("id", fieldID + "_date_range_before");
    dateRangeAfter.setAttribute("name", "custom_field_" + fieldID + "_date_range_after");
    dateRangeBefore.setAttribute("name", "custom_field_" + fieldID + "_date_range_before");
    dateRangeAfter.setAttribute("style", "width: 49%;");
    dateRangeBefore.setAttribute("style", "width: 49%;");
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
    if (!show) {
        dateRangeTD.classList.add("hidden");
    }
    var dateRangeLabel = document.createElement("label");
    dateRangeLabel.setAttribute("style", "white-space: nowrap;");
    dateRangeLabel.setAttribute("for", fieldID + "_date_range");
    dateRangeLabel.innerHTML = title;
    dateRangeTD.appendChild(dateRangeLabel);
    dateRangeTD.appendChild(getBR());
    dateRangeTD.appendChild(dateRangeAfter);
    dateRangeTD.appendChild(dateRangeBefore);
    return dateRangeTD;
}
function getClass(fieldID, value) {
    var classField = document.createElement("input");
    var show = JSON.parse(settings.columns).indexOf('class') !== -1;
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
    if (!show) {
        classTD.classList.add("hidden");
    }
    var classLabel = document.createElement("label");
    classLabel.setAttribute("style", "white-space: nowrap;");
    classLabel.setAttribute("for", fieldID + "_class");
    classLabel.innerHTML = "Class";
    classTD.appendChild(classLabel);
    classTD.appendChild(getBR());
    classTD.appendChild(classField);
    return classTD;
}
function getStyle(fieldID, value) {
    var style = document.createElement("input");
    var show = JSON.parse(settings.columns).indexOf('style') !== -1;
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
    if (!show) {
        styleTD.classList.add("hidden");
    }
    var styleLabel = document.createElement("label");
    styleLabel.setAttribute("style", "white-space: nowrap;");
    styleLabel.setAttribute("for", fieldID + "_style");
    styleLabel.innerHTML = "Style";
    styleTD.appendChild(styleLabel);
    styleTD.appendChild(getBR());
    styleTD.appendChild(style);
    return styleTD;
}
function getOverrideRight(fieldID, value) {
    var overrideRight = document.createElement("input");
    var show = JSON.parse(settings.columns).indexOf('override_right') !== -1;
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
    if (!show) {
        overrideRightTD.classList.add("hidden");
    }
    var overrideRightLabel = document.createElement("label");
    overrideRightLabel.setAttribute("style", "white-space: nowrap;");
    overrideRightLabel.setAttribute("for", fieldID + "_override_right");
    overrideRightLabel.innerHTML = "Override Right";
    overrideRightTD.appendChild(overrideRightLabel);
    overrideRightTD.appendChild(getBR());
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

function fieldTypeChanged(fieldID) {
    var tr = document.getElementById(fieldID + "_tr");
    var fieldType = document.getElementById(fieldID + "_field_type").value;
    removeField(document.getElementById(fieldID + "_text_td"));
    removeField(document.getElementById(fieldID + "_input_type_td"));
    removeField(document.getElementById(fieldID + "_name_td"));
    removeField(document.getElementById(fieldID + "_preview_td"));
    removeField(document.getElementById(fieldID + "_required_td"));
    removeField(document.getElementById(fieldID + "_options_td"));
    removeField(document.getElementById(fieldID + "_disabled_td"));
    removeField(document.getElementById(fieldID + "_default_value_td"));
    removeField(document.getElementById(fieldID + "_default_checked_td"));
    removeField(document.getElementById(fieldID + "_date_range_td"));
    removeField(document.getElementById(fieldID + "_placeholder_td"));
    removeField(document.getElementById(fieldID + "_class_td"));
    removeField(document.getElementById(fieldID + "_style_td"));
    removeField(document.getElementById(fieldID + "_override_right_td"));
    removeField(document.getElementById(fieldID + "_delete_row_td"));
    removeFields(document.getElementsByClassName(fieldID + "_empty_td"));
    if (fieldType === 'input') {
        tr.appendChild(getDisabled(fieldID, ""));
        tr.appendChild(getRequired(fieldID, ""));
        tr.appendChild(getDefaultValue(fieldID, ""));
        tr.appendChild(getPlaceholder(fieldID, ""));
        tr.appendChild(getClass(fieldID, ""));
        tr.appendChild(getStyle(fieldID, ""));
        tr.appendChild(getOverrideRight(fieldID, ""));
        tr.appendChild(getDeleteRow(fieldID));
    } else if (fieldType === 'label') {
        tr.appendChild(getText(fieldID, ""));
        tr.appendChild(getClass(fieldID, ""));
        tr.appendChild(getStyle(fieldID, ""));
        tr.appendChild(getEmpty(fieldID, "column-override_right"));
        tr.appendChild(getDeleteRow(fieldID));
    } else {
        tr.appendChild(getEmpty(fieldID));
        tr.appendChild(getEmpty(fieldID));
        tr.appendChild(getEmpty(fieldID));
        tr.appendChild(getEmpty(fieldID));
        tr.appendChild(getEmpty(fieldID, "column-default"));
        tr.appendChild(getEmpty(fieldID, "column-placeholder"));
        tr.appendChild(getClass(fieldID, ""));
        tr.appendChild(getStyle(fieldID, ""));
        tr.appendChild(getEmpty(fieldID, "column-override_right"));
        tr.appendChild(getDeleteRow(fieldID));
    }
}
function inputTypeChanged(fieldID) {
    var tr = document.getElementById(fieldID + "_tr");
    var inputType = document.getElementById(fieldID + "_input_type").value;
    removeField(document.getElementById(fieldID + "_input_type_td"));
    removeField(document.getElementById(fieldID + "_name_td"));
    removeField(document.getElementById(fieldID + "_preview_td"));
    removeField(document.getElementById(fieldID + "_required_td"));
    removeField(document.getElementById(fieldID + "_options_td"));
    removeField(document.getElementById(fieldID + "_disabled_td"));
    removeField(document.getElementById(fieldID + "_default_value_td"));
    removeField(document.getElementById(fieldID + "_default_checked_td"));
    removeField(document.getElementById(fieldID + "_date_range_td"));
    removeField(document.getElementById(fieldID + "_placeholder_td"));
    removeField(document.getElementById(fieldID + "_class_td"));
    removeField(document.getElementById(fieldID + "_style_td"));
    removeField(document.getElementById(fieldID + "_override_right_td"));
    removeField(document.getElementById(fieldID + "_delete_row_td"));
    removeFields(document.getElementsByClassName(fieldID + "_empty_td"));
    if (inputType === 'text') {
        getTextInputFields(tr, fieldID, "", "", "", "", "", "", "", "");
    } else if (inputType === 'select') {
        getSelectInputFields(tr, fieldID, "", "", "", "", "");
    } else if (inputType === 'checkbox') {
        getCheckboxInputFields(tr, fieldID, "", "", "", "", "", "", "");
    } else if (inputType === 'date') {
        getDateInputFields(tr, fieldID, "", "", "", "", "", "", "", "");
    } else if (inputType === 'role_checkbox') {
        getRoleCheckboxInputFields(tr, fieldID, "", "", "", "");
    } else if (inputType === 'role_select') {
        getRoleSelectInputFields(tr, fieldID, "", "", "", "", "");
    } else if (inputType === 'image') {
        getImageInputFields(tr, fieldID, "", "", "", "", "");
    } else if (inputType === 'hidden') {
        getHiddenInputFields(tr, fieldID, "", "", "", "");
    } else {
        getCustomInputFields(tr, fieldID, "", "", "", "", "", "", "", "");
    }
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
    if (selected.indexOf("default") !== -1) {
        textAreaColspan++;
    }
    if (selected.indexOf("placeholder") !== -1) {
        textAreaColspan++;
    }
    for (i = 0; i < textColumns.length; i++) {
        textColumns[i].setAttribute("colspan", textAreaColspan);
    }

    var optionsColumns = document.getElementById(containerID).getElementsByClassName("options_td");
    var optionsColspan = 1;
    if (selected.indexOf("default") !== -1) {
        optionsColspan++;
    }
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
