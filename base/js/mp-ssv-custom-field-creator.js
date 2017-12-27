//noinspection JSUnresolvedVariable
var roles = JSON.parse(settings.roles);
var scripts = document.getElementsByTagName("script");
var pluginBaseURL = scripts[scripts.length - 1].src.split('/').slice(0, -3).join('/');
var fieldIDs = [];

function mp_ssv_add_custom_input_field(container, fieldID, inputType, values) {
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
        getCustomInputField(container, inputType, fieldID, values);
    }
}