//noinspection JSUnresolvedVariable
var roles = JSON.parse(settings.roles);
var scripts = document.getElementsByTagName("script");
var pluginBaseURL = scripts[scripts.length - 1].src.split('/').slice(0, -3).join('/');
var fieldIDs = [];

function getBR() {
    var br = document.createElement("div");
    br.innerHTML = '<br/>';
    return br.childNodes[0];
}

function getEmpty(fieldID, columnClass) {
    var td = document.createElement("td");
    td.setAttribute("id", fieldID + "_empty_td");
    if (columnClass) {
        td.classList.add(columnClass);
    }
    return td;
}

function getFieldCheckbox(fieldID) {
    var fieldIDElement = document.createElement("input");
    fieldIDElement.setAttribute("type", "hidden");
    fieldIDElement.setAttribute("id", fieldID + "_id");
    fieldIDElement.setAttribute("name", "custom_field_" + fieldID + "_id");
    fieldIDElement.setAttribute("value", fieldID);
    var fieldSelectElement = document.createElement("input");
    fieldSelectElement.setAttribute("type", "checkbox");
    fieldSelectElement.setAttribute("id", fieldID + "_id");
    fieldSelectElement.setAttribute("name", "custom_field_" + fieldID + "_id");
    fieldSelectElement.setAttribute("value", fieldID);
    var fieldIDTD = document.createElement("th");
    fieldIDTD.setAttribute("id", fieldID + "_id_td");
    fieldIDTD.setAttribute("class", "check-column");
    fieldIDTD.appendChild(fieldIDElement);
    fieldIDTD.appendChild(fieldSelectElement);
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
    var fieldTitleTD = document.createElement("td");
    fieldTitleTD.setAttribute("id", fieldID + "_field_title_td");
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
    inputType.addEventListener('input', function () {
        inputTypeChanged(fieldID);
    });
    var inputTypeTD = document.createElement("td");
    inputTypeTD.setAttribute("id", fieldID + "_inputType_td");
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
    var nameTD = document.createElement("td");
    nameTD.setAttribute("id", fieldID + "_name_td");
    nameTD.appendChild(name);
    return nameTD;
}

function getRoleCheckbox(fieldID, value) {
    var inputType = createSelect(fieldID, "_name", roles, value);
    inputType.setAttribute("style", "width: 100%;");
    var inputTypeTD = document.createElement("td");
    inputTypeTD.setAttribute("id", fieldID + "_name_td");
    inputTypeTD.appendChild(inputType);
    return inputTypeTD;
}

function getRoleSelect(fieldID, value) {
    var inputType = createMultiSelect(fieldID, "_options", roles, value);
    inputType.setAttribute("style", "width: 100%;");
    var inputTypeTD = document.createElement("td");
    inputTypeTD.setAttribute("id", fieldID + "_options_td");
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
    var optionsTD = document.createElement("td");
    optionsTD.setAttribute("class", "options_td");
    optionsTD.setAttribute("id", fieldID + "_options_td");
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
    var valueTD = document.createElement("td");
    valueTD.setAttribute("id", fieldID + "_value_td");
    valueTD.appendChild(valueField);
    return valueTD;
}

function inputTypeChanged(fieldID) {
    var tr = document.getElementById(fieldID + "_tr");
    var inputType = document.getElementById(fieldID + "_inputType").value;
    var name = document.getElementById(fieldID + "_name").value;
    removeField(document.getElementById(fieldID + "_name_td"));
    removeField(document.getElementById(fieldID + "_empty_td"));
    removeField(document.getElementById(fieldID + "_options_td"));
    removeField(document.getElementById(fieldID + "_value_td"));
    if (inputType === 'role_checkbox') {
        tr.insertBefore(getRoleCheckbox(fieldID, name), document.getElementById(fieldID + "_inputType_td"));
    } else {
        tr.insertBefore(getName(fieldID, name), document.getElementById(fieldID + "_inputType_td"));
    }
    if (inputType === 'select') {
        tr.appendChild(getOptions(fieldID));
    } else if (inputType === 'role_checkbox') {
        tr.appendChild(getEmpty(fieldID));
    } else if (inputType === 'role_select') {
        tr.appendChild(getRoleSelect(fieldID));
    } else if (inputType === 'hidden') {
        tr.appendChild(getValue(fieldID, ""));
    } else {
        tr.appendChild(getEmpty(fieldID));
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

function deleteRow(fieldID) {
    var tr = document.getElementById(fieldID + "_tr");
    removeField(tr);
    // TODO ajax remove row.
    event.preventDefault();
}

function editRow(fieldID) {
    var tr = document.getElementById(fieldID + "_tr");
    event.preventDefault();
}