let fieldTypesObjects = {
    'text': [
        'div',
        'label',
        'input',
    ],
    'email': [
        'div',
        'label',
        'input',
    ],
    'password': [
        'div',
        'label',
        'input',
    ],
    'checkbox': [
        'div',
        'label',
        'input',
    ],
    'date': [
        'div',
        'label',
        'input',
    ],
    'file': [
        'div',
        'label',
        'input',
    ],
    'select': [
        'div',
        'label',
        'input',
    ],
    'number': [
        'div',
        'label',
        'input',
    ],
    'custom': [
        'div',
        'label',
        'input',
    ],
};
let propertiesForField = {
    'text': [
        'title',
        'classes',
        'defaultValue',
        'styles',
        'required',
        'autocomplete',
        'placeholder',
        'list',
        'pattern',
    ],
    'email': [
        'title',
        'classes',
        'defaultValue',
        'styles',
        'required',
        'autocomplete',
        'placeholder',
        'list',
        'pattern',
    ],
    'password': [
        'title',
        'classes',
        'defaultValue',
        'styles',
        'required',
        'autocomplete',
        'list',
        'pattern',
    ],
    'checkbox': [
        'title',
        'defaultValue',
        'classes',
        'styles',
        'required',
    ],
    'date': [
        'title',
        'classes',
        'defaultValue',
        'styles',
        'required',
        'autocomplete',
        'placeholder',
        'pattern',
    ],
    'file': [
        'title',
        'classes',
        'defaultValue',
        'styles',
        'required',
    ],
    'select': [
        'title',
        'classes',
        'defaultValue',
        'styles',
        'required',
        'multiple',
        'size',
    ],
    'number': [
        'title',
        'classes',
        'defaultValue',
        'styles',
        'required',
        'placeholder',
        'step',
        'min',
        'max',
    ],
    'custom': [
        'title',
        'classes',
        'defaultValue',
        'styles',
        'required',
        'autocomplete',
        'placeholder',
        'list',
        'pattern',
        'step',
        'min',
        'max',
    ],
};

let fieldsCustomizer = {
    inlineEdit: function (fieldId) {
        let tr = document.getElementById(fieldId + '_tr');
        let properties = JSON.parse(tr.dataset.properties);
        tr.setAttribute('class', 'inline-edit-row inline-edit-row-base-field quick-edit-row quick-edit-row-base-field inline-edit-base-field inline-editor');
        let propertyKeys = null;
        if (typeof(propertiesForField[tr.dataset.inputType]) === 'undefined') {
            propertyKeys = propertiesForField['custom'];
        } else {
            propertyKeys = propertiesForField[tr.dataset.inputType];
        }
        let html =
            '<input type="hidden" name="form_fields[]" value="' + tr.dataset.baseFieldName + '">' +
            '<td colspan="5" class="colspanchange">' +
            '   <fieldset class="inline-edit-col-left" style="width: 50%;">' +
            '       <legend class="inline-edit-legend">Quick Edit</legend>' +
            '       <div class="inline-edit-col">'
        ;
        if (propertyKeys.includes('title')) {
            html += fieldsCustomizer.getCustomizationFieldInput(fieldId, 'Title', 'title', 'text', properties.title);
        }
        if (propertyKeys.includes('classes')) {
            for (let i = 0; i < fieldTypesObjects[tr.dataset.inputType].length; ++i) {
                let id = fieldTypesObjects[tr.dataset.inputType][i];
                let title = id.charAt(0).toUpperCase() + id.slice(1);
                html += fieldsCustomizer.getCustomizationFieldInput(fieldId, title + ' Classes', id + '_classes', 'textarea', properties.classes[id]);
            }
        }
        if (propertyKeys.includes('required')) {
            html += fieldsCustomizer.getCustomizationFieldInput(fieldId, 'Required', 'required', 'checkbox', properties.required);
        }
        if (propertyKeys.includes('placeholder')) {
            html += fieldsCustomizer.getCustomizationFieldInput(fieldId, 'Placeholder', 'placeholder', 'text', properties.placeholder);
        }
        if (propertyKeys.includes('pattern')) {
            html += fieldsCustomizer.getCustomizationFieldInput(fieldId, 'Pattern', 'pattern', 'text', properties.pattern);
        }
        if (propertyKeys.includes('min')) {
            html += fieldsCustomizer.getCustomizationFieldInput(fieldId, 'Min', 'min', 'number', properties.max);
        }
        if (propertyKeys.includes('size')) {
            html += fieldsCustomizer.getCustomizationFieldInput(fieldId, 'Size', 'size', 'number', properties.size);
        }
        html +=
            '       </div>' +
            '   </fieldset>' +
            '   <fieldset class="inline-edit-col-right" style="width: 50%; margin-top: 32px;">' +
            '       <div class="inline-edit-col">'
        ;
        if (propertyKeys.includes('defaultValue')) {
            if (tr.dataset.inputType === 'select') {
                html += fieldsCustomizer.getCustomizationFieldInput(fieldId, 'Default Value', 'defaultValue', 'select', properties.defaultValue, JSON.parse(tr.dataset.options));
            } else if (tr.dataset.inputType === 'checkbox') {
                html += fieldsCustomizer.getCustomizationFieldInput(fieldId, 'Label', 'defaultValue', 'text', properties.defaultValue);
            } else {
                html += fieldsCustomizer.getCustomizationFieldInput(fieldId, 'Default Value', 'defaultValue', 'text', properties.defaultValue);
            }
        }
        if (propertyKeys.includes('styles')) {
            for (let i = 0; i < fieldTypesObjects[tr.dataset.inputType].length; ++i) {
                let id = fieldTypesObjects[tr.dataset.inputType][i];
                let title = id.charAt(0).toUpperCase() + id.slice(1);
                html += fieldsCustomizer.getCustomizationFieldInput(fieldId, title + ' Styles', id + '_styles', 'textarea', properties.styles[id]);
            }
        }
        if (propertyKeys.includes('autocomplete')) {
            html += fieldsCustomizer.getCustomizationFieldInput(fieldId, 'Autocomplete', 'autocomplete', 'checkbox', properties.autocomplete);
        }
        if (propertyKeys.includes('list')) {
            html += fieldsCustomizer.getCustomizationFieldInput(fieldId, 'List', 'list', 'text', properties.list);
        }
        if (propertyKeys.includes('step')) {
            html += fieldsCustomizer.getCustomizationFieldInput(fieldId, 'Step', 'step', 'number', properties.step);
        }
        if (propertyKeys.includes('max')) {
            html += fieldsCustomizer.getCustomizationFieldInput(fieldId, 'Max', 'max', 'number', properties.max);
        }
        if (propertyKeys.includes('multiple')) {
            html += fieldsCustomizer.getCustomizationFieldInput(fieldId, 'Multiple', 'multiple', 'checkbox', properties.multiple);
        }
        html +=
            '      </div>' +
            '   </fieldset>' +
            '   <div class="submit inline-edit-save" style="float: none;">' +
            '      <button type="button" class="button cancel alignleft" onclick="fieldsCustomizer.cancelInlineEdit(\'' + fieldId + '\')">Cancel</button>' +
            '      <input type="hidden" id="_inline_edit" name="_inline_edit" value="' + fieldId + '">' +
            '      <button type="button" class="button button-primary save alignright" onclick="fieldsCustomizer.saveInlineEdit(\'' + fieldId + '\')">Update</button>' +
            '      <br class="clear">' +
            '   </div>' +
            '</td>'
        ;
        tr.innerHTML = html;
        tr.removeAttribute('draggable');
    },

    cancelInlineEdit: function (fieldId) {
        fieldsCustomizer.updateTrForDisplay(fieldId);
    },

    saveInlineEdit: function (fieldId) {
        let tr = document.getElementById(fieldId + '_tr');
        let formId = document.getElementById('form_id').value;
        let properties = {};
        let inputType = tr.dataset.inputType;
        let propertyFields = null;
        if (typeof(propertiesForField[inputType]) !== 'undefined') {
            propertyFields = propertiesForField[inputType];
        } else {
            propertyFields = propertiesForField['custom'];
        }
        for (let i = 0; i < propertyFields.length; ++i) {
            if (propertyFields[i] === 'classes' || propertyFields[i] === 'styles') {
                let fieldTypeObjects = null;
                if (typeof(fieldTypesObjects[inputType]) !== 'undefined') {
                    fieldTypeObjects = fieldTypesObjects[inputType];
                } else {
                    fieldTypeObjects = fieldTypesObjects['custom'];
                }
                properties[propertyFields[i]] = {};
                for (let j = 0; j < fieldTypeObjects.length; ++j) {
                    let element = document.getElementById(fieldId + '_' + fieldTypeObjects[j] + '_' + propertyFields[i]);
                    properties[propertyFields[i]][fieldTypeObjects[j]] = element.value;
                }
            } else {
                console.log(fieldId + '_' + propertyFields[i]);
                let element = document.getElementById(fieldId + '_' + propertyFields[i]);
                if (element.getAttribute('type') === 'checkbox') {
                    properties[propertyFields[i]] = element.checked;
                } else {
                    properties[propertyFields[i]] = element.value;
                }
            }
        }
        tr.dataset.properties = JSON.stringify(properties);
        fieldsCustomizer.updateTrForDisplay(fieldId);
        jQuery.post(
            urls.ajax,
            {
                action: actions.save,
                values: {
                    cf_f_id: formId,
                    cf_bf_name: tr.dataset.baseFieldName,
                    cf_json: properties,
                },
            }
        );
        event.preventDefault();
    },

    updateTrForDisplay: function (fieldId) {
        let tr = document.getElementById(fieldId + '_tr');
        let name = tr.dataset.name;
        let inputType = tr.dataset.inputType;
        let properties = JSON.parse(tr.dataset.properties);
        let title = properties['title'];
        let defaultValue = properties['defaultValue'];
        tr.innerHTML =
            '<td>' +
            '   <input type="hidden" name="form_fields[]" value="' + name + '">' +
            '   <strong>' + title + '</strong>' +
            '<span class="inline-actions"> | <a href="javascript:void(0)" onclick="fieldsCustomizer.inlineEdit(\'' + fieldId + '\')" class="editinline" aria-label="Quick edit “' + title + '” inline">Quick Edit</a></span>' +
            '</td>' +
            '<td>' + inputType + '</td>' +
            '<td>' + defaultValue + '</td>'
        ;
        tr.setAttribute('class', 'inactive');
        tr.setAttribute('draggable', 'draggable');
    },

    getCustomizationFieldInput: function (fieldId, title, name, type, value, options) {
        let html =
            '<label>' +
            '   <span class="title">' + title + '</span>' +
            '   <span class="input-text-wrap">'
        ;
        if (type === 'textarea') {
            html += '<textarea id="' + fieldId + '_' + name + '" name="' + name + '">' + value + '</textarea>';
        } else if (type === 'number') {
            html += '<input type="number" id="' + fieldId + '_' + name + '" name="' + name + '" value="' + value + '" autocomplete="off" onkeydown="fieldsCustomizer.onInlineEditKeyDown(\'' + fieldId + '\')" style="width: 100%;">';
        } else if (type === 'select') {
            html += '<select id="' + fieldId + '_' + name + '" name="' + name + '" style="width: 100%;">';
            for (let i = 0; i < options.length; ++i) {
                html += '<option>' + options[i] + '</option>';
            }
            html += '</select>';
        } else if (type === 'checkbox') {
            let checked = value === true || value === 'true' ? 'checked="checked"' : '';
            html += '<input type="hidden" name="' + name + '" value="false">';
            html += '<input type="checkbox" id="' + fieldId + '_' + name + '" name="' + name + '" value="true" ' + checked + '>';
        } else {
            html += '<input type="' + type + '" id="' + fieldId + '_' + name + '" name="' + name + '" value="' + value + '" autocomplete="off" onkeydown="fieldsCustomizer.onInlineEditKeyDown(\'' + fieldId + '\')">';
        }
        html +=
            '   </span>' +
            '</label>'
        ;
        return html;
    },

    onInlineEditKeyDown: function (fieldId) {
        if (event.keyCode === 13) {
            fieldsCustomizer.saveInlineEdit(fieldId);
            event.preventDefault();
            return false;
        }
    }
};
