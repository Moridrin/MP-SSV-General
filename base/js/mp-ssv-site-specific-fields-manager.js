//noinspection JSUnresolvedVariable
var roles = JSON.parse(settings.roles);
var scripts = document.getElementsByTagName("script");
var pluginBaseURL = scripts[scripts.length - 1].src.split('/').slice(0, -3).join('/');
var fieldIDs = [];

function mp_ssv_add_site_specific_input_field(container, fieldID, title, name, inputType, options, value) {
    fieldIDs.push(fieldID);
    container = document.getElementById(container);

    var tr = document.createElement("tr");
    tr.setAttribute("id", fieldID + "_tr");
    tr.appendChild(getFieldIDs(fieldID));
    tr.appendChild(getFieldID(fieldID));
    tr.appendChild(getFieldTitle(fieldID, title));
    if (inputType === 'role_checkbox') {
        tr.appendChild(getRoleCheckbox(fieldID, name));
    } else {
        tr.appendChild(getName(fieldID, name));
    }
    tr.appendChild(getInputType(fieldID, inputType));
    if (inputType === 'select') {
        tr.appendChild(getOptions(fieldID, options));
    } else if (inputType === 'hidden') {
        tr.appendChild(getValue(fieldID, value));
    } else if (inputType === 'role_select') {
        tr.appendChild(getRoleSelect(fieldID, JSON.parse(options)));
    } else {
        tr.appendChild(getEmpty(fieldID));
    }
    tr.appendChild(getDeleteRow(fieldID));
    container.appendChild(tr);
}

function getBR() {
    var br = document.createElement("div");
    br.innerHTML = '<br/>';
    return br.childNodes[0];
}

function getEmpty(fieldID, columnClass) {
    var td = document.createElement("td");
    td.setAttribute("style", "padding: 0;");
    td.setAttribute("id", fieldID + "_empty_td");
    if (columnClass) {
        td.classList.add(columnClass);
    }
    return td;
}

function getFieldIDs(fieldID, isTab) {
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

function getInputType(fieldID, value) {
    var inputType = document.createElement("input");
    inputType.setAttribute("id", fieldID + "_inputType");
    inputType.setAttribute("name", "custom_field_" + fieldID + "_inputType");
    inputType.setAttribute("style", "width: 100%;");
    inputType.setAttribute("list", "inputType");
    if (value) {
        inputType.setAttribute("value", value);
    }
    inputType.onchange = function () {
        inputTypeChanged(fieldID);
    };
    var inputTypeLabel = document.createElement("label");
    inputTypeLabel.setAttribute("style", "white-space: nowrap;");
    inputTypeLabel.setAttribute("for", fieldID + "_inputType");
    inputTypeLabel.innerHTML = "Input Type";
    var inputTypeTD = document.createElement("td");
    inputTypeTD.setAttribute("style", "padding: 0;");
    inputTypeTD.setAttribute("id", fieldID + "_inputType_td");
    inputTypeTD.appendChild(inputTypeLabel);
    inputTypeTD.appendChild(getBR());
    inputTypeTD.appendChild(inputType);
    return inputTypeTD;
}

function getName(fieldID, value) {
    var name = document.createElement("input");
    name.setAttribute("id", fieldID + "_name");
    name.setAttribute("name", "custom_field_" + fieldID + "_name");
    name.setAttribute("style", "width: 100%;");
    name.setAttribute("pattern", "[a-z0-9_]+");
    if (value) {
        name.setAttribute("value", value);
    }
    name.setAttribute("required", "required");
    var nameLabel = document.createElement("label");
    nameLabel.setAttribute("style", "white-space: nowrap;");
    nameLabel.setAttribute("for", fieldID + "_name");
    nameLabel.innerHTML = "Name";
    var nameTD = document.createElement("td");
    nameTD.setAttribute("style", "padding: 0;");
    nameTD.setAttribute("id", fieldID + "_name_td");
    nameTD.appendChild(nameLabel);
    nameTD.appendChild(getBR());
    nameTD.appendChild(name);
    return nameTD;
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
    optionsTD.appendChild(optionsLabel);
    optionsTD.appendChild(getBR());
    optionsTD.appendChild(options);
    return optionsTD;
}

function getValue(fieldID, value) {
    var valueField = document.createElement("input");
    valueField.setAttribute("id", fieldID + "_value");
    valueField.setAttribute("name", "custom_field_" + fieldID + "_value");
    valueField.setAttribute("style", "width: 100%;");
    if (value) {
        valueField.setAttribute("value", value);
    }
    var valueLabel = document.createElement("label");
    valueLabel.setAttribute("style", "white-space: nowrap;");
    valueLabel.setAttribute("for", fieldID + "_value");
    valueLabel.innerHTML = "Value";
    var valueTD = document.createElement("td");
    valueTD.setAttribute("style", "padding: 0;");
    valueTD.setAttribute("id", fieldID + "_value_td");
    valueTD.appendChild(valueLabel);
    valueTD.appendChild(getBR());
    valueTD.appendChild(valueField);
    return valueTD;
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

function inputTypeChanged(fieldID) {
    var tr = document.getElementById(fieldID + "_tr");
    var inputType = document.getElementById(fieldID + "_inputType").value;
    removeField(document.getElementById(fieldID + "_empty_td"));
    removeField(document.getElementById(fieldID + "_options_td"));
    if (inputType === 'select') {
        tr.insertBefore(getOptions(fieldID), document.getElementById(fieldID + "_delete_row_td"));
    } else if (inputType === 'hidden') {
        tr.insertBefore(getValue(fieldID, ""), document.getElementById(fieldID + "_delete_row_td"));
    } else {
        tr.insertBefore(getEmpty(fieldID), document.getElementById(fieldID + "_delete_row_td"));
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
