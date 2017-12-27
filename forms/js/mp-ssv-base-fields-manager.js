//noinspection JSUnresolvedVariable
let scripts = document.getElementsByTagName("script");
let pluginBaseURL = scripts[scripts.length - 1].src.split('/').slice(0, -3).join('/');
let fieldIDs = [];

function mp_ssv_add_base_input_field2(container, fieldID, title, name, inputType, options, value) {
    fieldIDs.push(fieldID);
    container = document.getElementById(container);

    let tr = document.createElement("tr");
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

function mp_ssv_add_base_input_field(container, fieldId) {
    let tr = document.createElement("tr");
    tr.setAttribute("id", fieldId + "_tr");
    tr.setAttribute('class', 'inline-edit-row inline-edit-row-base-field quick-edit-row quick-edit-row-base-field inline-edit-base-field inline-editor');
    fieldsManager.updateTrForInlineEdit(tr, fieldId, '', '', '', '', true);
    container = document.getElementById(container);
    removeField(document.getElementById('no-items'));
    container.appendChild(tr);
}
