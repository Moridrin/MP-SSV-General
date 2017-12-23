//noinspection JSUnresolvedVariable
var scripts = document.getElementsByTagName("script");
var pluginBaseURL = scripts[scripts.length - 1].src.split('/').slice(0, -3).join('/');
var fieldIDs = [];

function mp_ssv_add_base_input_field(container, fieldID, title, name, inputType, options, value) {
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
