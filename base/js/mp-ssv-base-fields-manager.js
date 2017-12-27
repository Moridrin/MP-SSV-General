//noinspection JSUnresolvedVariable
var scripts = document.getElementsByTagName("script");
var pluginBaseURL = scripts[scripts.length - 1].src.split('/').slice(0, -3).join('/');
var fieldIDs = [];

function mp_ssv_add_base_input_field2(container, fieldID, title, name, inputType, options, value) {
    fieldIDs.push(fieldID);
    container = document.getElementById(container);

    var tr = document.createElement("tr");
    tr.setAttribute("id", fieldID + "_tr");
    tr.setAttribute("class", "inactive");
    tr.appendChild(getFieldCheckbox(fieldID));
    tr.appendChild(getFieldTitle(fieldID, title));
    tr.appendChild(getName(fieldID, name));
    tr.appendChild(getInputType(fieldID, inputType));
    if (inputType === 'select') {
        tr.appendChild(getOptions(fieldID, options));
    } else if (inputType === 'hidden') {
        tr.appendChild(getValue(fieldID, value));
    } else {
        tr.appendChild(getEmpty(fieldID));
    }
    container.appendChild(tr);
}

function inputTypeChanged(fieldId) {
    let inputType = document.getElementById(fieldId + '_inputType').value;
    if (inputType === 'select' || inputType === 'hidden') {
        addValueContainerForInlineEdit(fieldId, '');
    } else {
        removeValueContainerForInlineEdit(fieldId);
    }
}

function mp_ssv_add_base_input_field(container, fieldId) {
    let tr = document.createElement("tr");
    tr.setAttribute("id", fieldId + "_tr");
    tr.setAttribute('class', 'inline-edit-row inline-edit-row-base-field quick-edit-row quick-edit-row-base-field inline-edit-base-field inline-editor');
    updateTrForInlineEdit(tr, fieldId, '', '', '', '', true);
    container = document.getElementById(container);
    container.appendChild(tr);
}

function deleteRow(fieldId) {
    let tr = document.getElementById(fieldId + '_tr');
    removeField(tr);
    jQuery.ajax({
        url: urls.ajax,
        type: 'POST',
        data: {
            action: 'mp_ssv_general_delete_selected_base_fields',
            fieldIds: [fieldId],
        },
        contentType: 'application/json; charset=utf-8',
    });
    event.preventDefault();
}
