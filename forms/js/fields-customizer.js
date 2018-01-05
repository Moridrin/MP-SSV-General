let fieldsCustomizer = {
    inlineEdit: function (fieldId) {
        let tr = document.getElementById(fieldId + '_tr');
        let properties = JSON.parse(tr.dataset.properties);
        let title = properties['title'];
        let value = properties['value'];
        let classes = properties['classes'];
        let styles = properties['styles'];
        tr.setAttribute('class', 'inline-edit-row inline-edit-row-base-field quick-edit-row quick-edit-row-base-field inline-edit-base-field inline-editor');
        fieldsCustomizer.updateTrForInlineEdit(tr, fieldId, title, value, classes, styles);
    },

    cancelInlineEdit: function (fieldId) {
        let title = document.getElementById(fieldId + '_title').dataset.oldValue;
        let name = document.getElementById(fieldId + '_name').dataset.oldValue;
        let inputType = document.getElementById(fieldId + '_inputType').dataset.oldValue;
        let value = '';
        if (inputType === 'hidden') {
            value = document.getElementById(fieldId + '_value').dataset.oldValue;
        } else if (inputType === 'select' || inputType === 'role_select') {
            let select = document.getElementById(fieldId + '_options');
            let selected = [];
            for (let i = 0; i < select.length; i++) {
                if (select.options[i].selected) {
                    selected.push(select.options[i].value);
                }
            }
            value = JSON.stringify(selected);
        }
        fieldsCustomizer.updateTrForDisplay(fieldId, title, name, inputType, value);
    },

    saveInlineEdit: function (fieldId) {
        let tr = document.getElementById(fieldId + '_tr');
        let classes = {
            'div': document.getElementById(fieldId + '_div_classes').value,
            'label': document.getElementById(fieldId + '_label_classes').value,
            'input': document.getElementById(fieldId + '_input_classes').value,
        };
        let styles = {
            'div': document.getElementById(fieldId + '_div_styles').value,
            'label': document.getElementById(fieldId + '_label_styles').value,
            'input': document.getElementById(fieldId + '_input_styles').value,
        };
        let properties = {
            'title': document.getElementById(fieldId + '_title').value,
            'classes': classes,
            'styles': styles,
            'value': document.getElementById(fieldId + '_value').value,
        };
        tr.dataset.properties = JSON.stringify(properties);
        fieldsCustomizer.updateTrForDisplay(fieldId);
        // jQuery.post(
        //     urls.ajax,
        //     {
        //         action: actions.save,
        //         values: {
        //             properties: properties
        //         },
        //     }
        // );
        event.preventDefault();
    },

    updateTrForDisplay: function (fieldId) {
        let tr = document.getElementById(fieldId + '_tr');
        let name = tr.dataset.name;
        let title = tr.dataset.properties['title'];
        let fieldType = tr.dataset.fieldType;
        let inputType = tr.dataset.inputType;
        let value = tr.dataset.properties['value'];
        tr.innerHTML =
            '<td id="' + fieldId + '_field_title_td">' +
            '   <input type="hidden" name="form_fields[]" value="' + name +'">' +
            '   <strong id="' + fieldId + '_title"><?= $field->bf_title ?></strong>' +
            '   <span class="inline-actions"> | <a href="javascript:void(0)" onclick="fieldsCustomizer.inlineEdit(\'<?= $field->bf_id ?>\')" class="editinline" aria-label="Quick edit “<?= $field->bf_title ?>” inline">Quick Edit</a></span>' +
            '    <strong>' + title + '</strong>' +
            '    <div class="row-actions">' +
            '        <span class="inline hide-if-no-js"><a href="javascript:void(0)" onclick="fieldsCustomizer.inlineEdit(\'' + fieldId + '\')" class="editinline" aria-label="Quick edit “Hello world!” inline">Quick Edit</a> | </span>' +
            '        <span class="trash"><a href="javascript:void(0)" onclick="fieldsCustomizer.deleteRow(\'' + fieldId + '\')" class="submitdelete" aria-label="Move “Hello world!” to the Trash">Trash</a></span>' +
            '    </div>' +
            '</td>' +
            '<td id="' + fieldId + '_fieldType">' + fieldType + '</td>' +
            '<td id="' + fieldId + '_inputType">' + inputType + '</td>' +
            '<td id="' + fieldId + '_value">' + value + '</td>'
        ;
        tr.setAttribute('class', 'inactive');
    },

    updateTrForInlineEdit: function (tr, fieldId, title, value, classes, styles) {
        tr.innerHTML =
            '<td colspan="5" class="colspanchange">' +
            '   <fieldset class="inline-edit-col-left" style="width: 50%;">' +
            '      <legend class="inline-edit-legend">Quick Edit</legend>' +
            '      <div class="inline-edit-col">' +
            '         <label>' +
            '            <span class="title">Title</span>' +
            '            <span class="input-text-wrap">' +
            '               <input type="text" id="' + fieldId + '_title" name="title" value="' + title + '" autocomplete="off">' +
            '            </span>' +
            '         </label>' +
            '         <label>' +
            '            <span class="title">div Classes</span>' +
            '            <span class="input-text-wrap">' +
            '               <textarea id="'+fieldId+'_div_classes" name="div_classes">' + classes['div'] + '</textarea>' +
            '            </span>' +
            '         </label>' +
            '         <label>' +
            '            <span class="title">Label Classes</span>' +
            '            <span class="input-text-wrap">' +
            '               <textarea id="'+fieldId+'_label_classes" name="label_classes">' + classes['label'] + '</textarea>' +
            '            </span>' +
            '         </label>' +
            '         <label>' +
            '            <span class="title">Input Classes</span>' +
            '            <span class="input-text-wrap">' +
            '               <textarea id="'+fieldId+'_input_classes" name="input_classes">' + classes['input'] + '</textarea>' +
            '            </span>' +
            '         </label>' +
            '      </div>' +
            '   </fieldset>' +
            '   <fieldset class="inline-edit-col-right" style="width: 50%; margin-top: 32px;">' +
            '      <div class="inline-edit-col">' +
            '         <label>' +
            '            <span class="title">Value</span>' +
            '            <span class="input-text-wrap">' +
            '               <input type="text" id="' + fieldId + '_title" name="value" value="' + value + '" autocomplete="off">' +
            '            </span>' +
            '         </label>' +
            '         <label>' +
            '            <span class="title">div Styles</span>' +
            '            <span class="input-text-wrap">' +
            '               <textarea id="'+fieldId+'_div_styles" name="div_styles">' + styles['div'] + '</textarea>' +
            '            </span>' +
            '         </label>' +
            '         <label>' +
            '            <span class="title">Label Styles</span>' +
            '            <span class="input-text-wrap">' +
            '               <textarea id="'+fieldId+'_label_styles" name="label_styles">' + styles['label'] + '</textarea>' +
            '            </span>' +
            '         </label>' +
            '         <label>' +
            '            <span class="title">Input Styles</span>' +
            '            <span class="input-text-wrap">' +
            '               <textarea id="'+fieldId+'_input_styles" name="input_styles">' + styles['input'] + '</textarea>' +
            '            </span>' +
            '         </label>' +
            '      </div>' +
            '   </fieldset>' +
            '   <div class="submit inline-edit-save" style="float: none;">' +
            '      <button type="button" class="button cancel alignleft" onclick="fieldsCustomizer.cancelInlineEdit(' + fieldId + ')">Cancel</button>' +
            '      <input type="hidden" id="_inline_edit" name="_inline_edit" value="' + fieldId + '">' +
            '      <button type="button" class="button button-primary save alignright" onclick="fieldsCustomizer.saveInlineEdit(' + fieldId + ')">Update</button>' +
            '      <br class="clear">' +
            '   </div>' +
            '</td>'
        ;
    },

    addValueContainerForInlineEdit: function (fieldId, value) {
        document.getElementById(fieldId + '_value_container').innerHTML =
            '<legend class="inline-edit-legend">Value / Options</legend>' +
            '<div class="inline-edit-col">' +
            '   <label>' +
            '       <span class="title">Value</span>' +
            '       <span class="input-text-wrap">' +
            '            <input type="text" id="' + fieldId + '_value" name="value" value="' + value + '" autocomplete="off" data-old-value="' + value + '">' +
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
            '       <span class="title">Options</span>' +
            '       <span class="input-text-wrap">' +
            '            <select id="' + fieldId + '_options" name="options[]" class="form-control" multiple="multiple" style="width: 100%; height: 90px;">'
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
        jQuery('#' + fieldId + '_options').select2({
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
            fieldsCustomizer.switchNameFieldToSelect(fieldId);
        } else {
            fieldsCustomizer.switchNameFieldToInput(fieldId);
        }
        if (inputType === 'hidden') {
            fieldsCustomizer.addValueContainerForInlineEdit(fieldId, '');
        } else if (inputType === 'select') {
            fieldsCustomizer.addSelectContainerForInlineEdit(fieldId, [], [], true);
        } else if (inputType === 'role_select') {
            fieldsCustomizer.addSelectContainerForInlineEdit(fieldId, roles, [], false);
        } else {
            fieldsCustomizer.removeValueContainerForInlineEdit(fieldId);
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
            fieldsCustomizer.showEmptyTable(container.id);
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
