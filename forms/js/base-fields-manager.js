function mp_ssv_add_base_input_field(container, fieldId) {
    let tr = document.createElement("tr");
    tr.setAttribute("id", fieldId + "_tr");
    tr.setAttribute('class', 'inline-edit-row inline-edit-row-base-field quick-edit-row quick-edit-row-base-field inline-edit-base-field inline-editor');
    fieldsManager.updateTrForInlineEdit(tr, fieldId, '', '', '', '', true);
    container = document.getElementById(container);
    removeField(document.getElementById('no-items'));
    container.appendChild(tr);
}
