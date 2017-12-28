let fieldsManager = {
    inlineEdit: function (fieldId) {
        let tr = document.getElementById(fieldId + '_tr');
        let title = document.getElementById(fieldId + '_field_title_td').children[0].innerText;
        let name = document.getElementById(fieldId + '_name_td').innerText;
        let inputType = document.getElementById(fieldId + '_inputType_td').innerText;
        let value = '';
        if (inputType === 'select' || inputType === 'hidden' || inputType === 'role_select') {
            value = document.getElementById(fieldId + '_value_td').innerText;
        }
        tr.setAttribute('class', 'inline-edit-row inline-edit-row-base-field quick-edit-row quick-edit-row-base-field inline-edit-base-field inline-editor');
        fieldsManager.updateTrForInlineEdit(tr, fieldId, title, name, inputType, value, false);
    },

    cancelInlineEdit: function (fieldId) {
        let title = document.getElementById(fieldId + '_title').dataset.oldValue;
        let name = document.getElementById(fieldId + '_name').dataset.oldValue;
        let inputType = document.getElementById(fieldId + '_inputType').dataset.oldValue;
        let value = '';
        if (inputType === 'select' || inputType === 'hidden') {
            value = document.getElementById(fieldId + '_value').dataset.oldValue;
        } else if (inputType === 'role_select') {
            let select = document.getElementById(fieldId + '_value');
            let selected = [];
            for (let i = 0; i < select.length; i++) {
                if (select.options[i].selected) {
                    selected.push(select.options[i].value);
                }
            }
            value = selected.join(';');
        }
        fieldsManager.updateTrForDisplay(fieldId, title, name, inputType, value);
    },

    saveInlineEdit: function (fieldId) {
        let title = document.getElementById(fieldId + '_title').value;
        let name = document.getElementById(fieldId + '_name').value;
        let inputType = document.getElementById(fieldId + '_inputType').value;
        let value = '';
        if (inputType === 'select' || inputType === 'hidden') {
            value = document.getElementById(fieldId + '_value').value;
        } else if (inputType === 'role_select') {
            let select = document.getElementById(fieldId + '_value');
            let selected = [];
            for (let i = 0; i < select.length; i++) {
                if (select.options[i].selected) {
                    selected.push(select.options[i].value);
                }
            }
            value = selected.join(';');
        }
        fieldsManager.updateTrForDisplay(fieldId, title, name, inputType, value);
        jQuery.post(
            urls.ajax,
            {
                action: actions.save,
                values: {
                    bf_id: fieldId,
                    bf_name: name,
                    bf_title: title,
                    bf_inputType: inputType,
                    bf_value: value,
                },
            }
        );
        event.preventDefault();
    },

    updateTrForDisplay: function (fieldId, title, name, inputType, value) {
        let tr = document.getElementById(fieldId + '_tr');
        tr.innerHTML =
            '<th id="' + fieldId + '_id_td" class="check-column">' +
            '    <input type="checkbox" id="' + fieldId + '_id" name="fieldIds[]" value="' + fieldId + '">' +
            '</th>' +
            '<td id="' + fieldId + '_field_title_td">' +
            '    <strong>' + title + '</strong>' +
            '    <div class="row-actions">' +
            '        <span class="inline hide-if-no-js"><a href="javascript:void(0)" onclick="fieldsManager.inlineEdit(\'' + fieldId + '\')" class="editinline" aria-label="Quick edit “Hello world!” inline">Quick Edit</a> | </span>' +
            '        <span class="trash"><a href="javascript:void(0)" onclick="fieldsManager.deleteRow(\'' + fieldId + '\')" class="submitdelete" aria-label="Move “Hello world!” to the Trash">Trash</a></span>' +
            '    </div>' +
            '</td>' +
            '<td id="' + fieldId + '_name_td">' + name + '</td>' +
            '<td id="' + fieldId + '_inputType_td">' + inputType + '</td>' +
            '<td id="' + fieldId + '_value_td">' + value + '</td>'
        ;
        tr.setAttribute('class', 'inactive');
    },

    updateTrForInlineEdit: function (tr, fieldId, title, name, inputType, value, isNew) {
        let addUpdateLabel;
        if (isNew) {
            addUpdateLabel = 'Add';
        } else {
            addUpdateLabel = 'Update';
        }
        tr.innerHTML =
            '<td colspan="5" class="colspanchange">' +
            '   <fieldset class="inline-edit-col-left" style="width: 50%;">' +
            '      <legend class="inline-edit-legend">Quick Edit</legend>' +
            '      <div class="inline-edit-col">' +
            '          <label>' +
            '              <span class="title">Title</span>' +
            '              <span class="input-text-wrap">' +
            '                  <input type="text" id="' + fieldId + '_title" name="title" value="' + title + '" data-old-value="' + title + '">' +
            '              </span>' +
            '          </label>' +
            '          <label>' +
            '              <span class="title">Name</span>' +
            '              <span class="input-text-wrap">' +
            '                  <input type="text" id="' + fieldId + '_name" name="name" value="' + name + '" data-old-value="' + name + '">' +
            '              </span>' +
            '          </label>' +
            '          <label>' +
            '              <span class="title">InputType</span>' +
            '              <span class="input-text-wrap">' +
            '                  <input type="text" id="' + fieldId + '_inputType" name="inputType" list="inputType" value="' + inputType + '" oninput="fieldsManager.inputTypeChanged(' + fieldId + ')" data-old-value="' + inputType + '">' +
            '              </span>' +
            '          </label>' +
            '      </div>' +
            '   </fieldset>' +
            '   <fieldset id="' + fieldId + '_value_container" class="inline-edit-col-center" style="width: 50%;">' +
            '   </fieldset>' +
            '   <div class="submit inline-edit-save">' +
            '       <button type="button" class="button cancel alignleft" onclick="fieldsManager.cancelInlineEdit(' + fieldId + ')">Cancel</button>' +
            '       <input type="hidden" id="_inline_edit" name="_inline_edit" value="' + fieldId + '">' +
            '       <button type="button" class="button button-primary save alignright" onclick="fieldsManager.saveInlineEdit(' + fieldId + ')">' + addUpdateLabel + '</button>' +
            '       <br class="clear">' +
            '   </div>' +
            '</td>'
        ;
        if (inputType === 'role_checkbox') {
            fieldsManager.switchNameFieldToSelect(fieldId);
        }
        if (inputType === 'hidden') {
            fieldsManager.addValueContainerForInlineEdit(fieldId, '');
        } else if (inputType === 'select') {
            fieldsManager.addSelectContainerForInlineEdit(fieldId, value, value, true);
        } else if (inputType === 'role_select') {
            fieldsManager.addSelectContainerForInlineEdit(fieldId, roles, value, false);
        }
    },

    addValueContainerForInlineEdit: function (fieldId, value) {
        document.getElementById(fieldId + '_value_container').innerHTML =
            '<legend class="inline-edit-legend">Value / Options</legend>' +
            '<div class="inline-edit-col">' +
            '   <label>' +
            '       <span class="title">Value</span>' +
            '       <span class="input-text-wrap">' +
            '            <input type="text" id="' + fieldId + '_value" name="value" value="' + value + '" data-old-value="' + value + '">' +
            '       </span>' +
            '   </label>' +
            '</div>'
        ;
    },

    addSelectContainerForInlineEdit: function (fieldId, options, selected, tags) {
        if (options.constructor !== Array) {
            options = options.split(';');
        }
        if (selected === '') {
            selected = [];
        }
        if (selected.constructor !== Array) {
            selected = selected.split(';');
        }
        selected.forEach(function (value) {
            if (options.indexOf(value) === -1) {
                options.push(value);
            }
        });
        let html =
            '<legend class="inline-edit-legend">Value / Options</legend>' +
            '<div class="inline-edit-col">' +
            '   <label>' +
            '       <span class="title">Value</span>' +
            '       <span class="input-text-wrap">' +
            '            <select id="' + fieldId + '_value" name="value[]" class="form-control" multiple="multiple" style="width: 100%; height: 90px;">'
        ;
        options.forEach(function (option) {
            if (selected.indexOf(option) !== -1) {
                html += '   <option selected="selected">' + option + '</option>';
            } else {
                html += '   <option>' + option + '</option>';
            }
        });
        html +=
            '            </select>' +
            '       </span>' +
            '   </label>' +
            '</div>'
        ;
        document.getElementById(fieldId + '_value_container').innerHTML = html;
        jQuery('#' + fieldId + '_value').select2({
            tags: tags,
            tokenSeparators: [';']
        });
    },

    removeValueContainerForInlineEdit: function (fieldId) {
        document.getElementById(fieldId + '_value_container').innerHTML = '';
    },

    inputTypeChanged: function (fieldId) {
        let inputType = document.getElementById(fieldId + '_inputType').value;
        if (inputType === 'role_checkbox') {
            fieldsManager.switchNameFieldToSelect(fieldId);
        } else {
            fieldsManager.switchNameFieldToInput(fieldId);
        }
        if (inputType === 'hidden') {
            fieldsManager.addValueContainerForInlineEdit(fieldId, '');
        } else if (inputType === 'select') {
            fieldsManager.addSelectContainerForInlineEdit(fieldId, [], [], true);
        } else if (inputType === 'role_select') {
            fieldsManager.addSelectContainerForInlineEdit(fieldId, roles, [], false);
        } else {
            fieldsManager.removeValueContainerForInlineEdit(fieldId);
        }
    },

    switchNameFieldToSelect: function (fieldId) {
        let nameInput = document.getElementById(fieldId + '_name');
        let name = nameInput.value;
        let html = '<select id="' + fieldId + '_name" name="name" data-old-value="' + name + '" style="width: 100%;">';
        roles.forEach(function (role) {
            if (role === name) {
                html += '<option value="' + role + '" selected="selected">' + role + '</option>';
            } else {
                html += '<option value="' + role + '">' + role + '</option>';
            }
        });
        html += '</select>';
        nameInput.parentElement.innerHTML = html;
    },

    switchNameFieldToInput: function (fieldId) {
        let nameInput = document.getElementById(fieldId + '_name');
        let name = nameInput.value;
        nameInput.parentElement.innerHTML = '<input type="text" id="' + fieldId + '_name" name="name" value="' + name + '" data-old-value="' + name + '">';
    },

    deleteRow: function (fieldId) {
        let tr = document.getElementById(fieldId + '_tr');
        let container = tr.parentElement;
        removeField(tr);
        jQuery.post(
            urls.ajax,
            {
                action: actions.delete,
                fieldIds: [fieldId],
            }
        );
        event.preventDefault();
        if (container.childElementCount === 0) {
            fieldsManager.showEmptyTable(container.id);
        }
    },

    showEmptyTable: function (containerId) {
        let container = document.getElementById(containerId);
        container.innerHTML = '' +
            '<tr id="no-items" class="no-items">' +
            '    <td class="colspanchange" colspan="8">No Base Fields found</td>' +
            '</tr>'
        ;
    }
};
