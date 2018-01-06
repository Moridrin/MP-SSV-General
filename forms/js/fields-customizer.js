let propertiesForField = {
    'text': [
        'autocomplete',
        'placeholder',
        'list',
        'pattern',
    ],
    'password': [
        'autocomplete',
        'list',
        'pattern',
    ],
    'number': [
        'placeholder',
        'step',
        'min',
        'max',
    ],
    'custom': [
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
            '<td colspan="5" class="colspanchange">' +
            '   <fieldset class="inline-edit-col-left" style="width: 50%;">' +
            '       <legend class="inline-edit-legend">Quick Edit</legend>' +
            '       <div class="inline-edit-col">'
        ;
        html += fieldsCustomizer.getCustomizationFieldInput(fieldId, 'Title', 'title', 'text', properties.title);
        html += fieldsCustomizer.getCustomizationFieldInput(fieldId, 'div Classes', 'div_classes', 'textarea', properties.classes['div']);
        html += fieldsCustomizer.getCustomizationFieldInput(fieldId, 'Label Classes', 'label_classes', 'textarea', properties.classes['label']);
        html += fieldsCustomizer.getCustomizationFieldInput(fieldId, 'Input Classes', 'input_classes', 'textarea', properties.classes['input']);
        html += fieldsCustomizer.getCustomizationFieldInput(fieldId, 'Required', 'required', 'checkbox', properties.required);
        if (propertyKeys.includes('placeholder')) {
            html += fieldsCustomizer.getCustomizationFieldInput(fieldId, 'Placeholder', 'placeholder', 'text', properties.placeholder);
        }
        if (propertyKeys.includes('pattern')) {
            html += fieldsCustomizer.getCustomizationFieldInput(fieldId, 'Pattern', 'pattern', 'text', properties.pattern);
        }
        html +=
            '       </div>' +
            '   </fieldset>' +
            '   <fieldset class="inline-edit-col-right" style="width: 50%; margin-top: 32px;">' +
            '       <div class="inline-edit-col">'
        ;
        html += fieldsCustomizer.getCustomizationFieldInput(fieldId, 'Value', 'value', 'text', properties.value);
        html += fieldsCustomizer.getCustomizationFieldInput(fieldId, 'div Styles', 'div_styles', 'textarea', properties.styles['div']);
        html += fieldsCustomizer.getCustomizationFieldInput(fieldId, 'Label Styles', 'label_styles', 'textarea', properties.styles['label']);
        html += fieldsCustomizer.getCustomizationFieldInput(fieldId, 'Input Styles', 'input_styles', 'textarea', properties.styles['input']);
        if (propertyKeys.includes('autocomplete')) {
            html += fieldsCustomizer.getCustomizationFieldInput(fieldId, 'Autocomplete', 'autocomplete', 'checkbox', properties.autocomplete);
        }
        if (propertyKeys.includes('list')) {
            html += fieldsCustomizer.getCustomizationFieldInput(fieldId, 'List', 'list', 'text', properties.list);
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
        let properties = {
            'title': document.getElementById(fieldId + '_title').value,
            'classes': {
                'div': document.getElementById(fieldId + '_div_classes').value,
                'label': document.getElementById(fieldId + '_label_classes').value,
                'input': document.getElementById(fieldId + '_input_classes').value,
            },
            'styles': {
                'div': document.getElementById(fieldId + '_div_styles').value,
                'label': document.getElementById(fieldId + '_label_styles').value,
                'input': document.getElementById(fieldId + '_input_styles').value,
            },
            'value': document.getElementById(fieldId + '_value').value,
            'required': document.getElementById(fieldId + '_required').checked,
        };
        let inputType = tr.dataset.inputType;
        let propertyFields = null;
        if (typeof(propertiesForField[inputType]) !== 'undefined') {
            propertyFields = propertiesForField[inputType];
        } else {
            propertyFields = propertiesForField['custom'];
        }
        for (let i = 0; i < propertyFields.length; ++i) {
            let element = document.getElementById(fieldId + '_'  + propertyFields[i]);
            if (element.tagName.toLocaleLowerCase() === 'input') {
                if (element.getAttribute('type') === 'checkbox') {
                    properties[propertyFields[i]] = element.checked;
                } else {
                    properties[propertyFields[i]] = element.value;
                }
            } else if (element.tagName.toLocaleLowerCase() === 'textarea') {
                properties[propertyFields[i]] = element.value;
            }
        }
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
        let inputType = tr.dataset.inputType;
        let properties = JSON.parse(tr.dataset.properties);
        let title = properties['title'];
        let value = properties['value'];
        tr.innerHTML =
            '<td>' +
            '   <input type="hidden" name="form_fields[]" value="' + name + '">' +
            '   <strong>' + title + '</strong>' +
            '<span class="inline-actions"> | <a href="javascript:void(0)" onclick="fieldsCustomizer.inlineEdit(' + fieldId + ')" class="editinline" aria-label="Quick edit “' + title + '” inline">Quick Edit</a></span>' +
            '</td>' +
            '<td>' + inputType + '</td>' +
            '<td>' + value + '</td>'
        ;
        tr.setAttribute('class', 'inactive');
        tr.setAttribute('draggable', 'draggable');
    },

    getCustomizationFieldInput: function (fieldId, title, name, type, value) {
        let html =
            '<label>' +
            '   <span class="title">' + title + '</span>' +
            '   <span class="input-text-wrap">'
        ;
        if (type === 'textarea') {
            html += '<textarea id="' + fieldId + '_' + name + '" name="' + name + '">' + value + '</textarea>';
        } else if (type === 'checkbox') {
            let checked = value === true ? 'checked="checked"' : '';
            html += '<input type="hidden" name="' + name + '" value="false">';
            html += '<input type="checkbox" id="' + fieldId + '_' + name + '" name="' + name + '" value="true" ' + checked + '>';
        } else {
            html += '<input type="' + type + '" id="' + fieldId + '_' + name + '" name="' + name + '" value="' + value + '" autocomplete="off">';
        }
        html +=
            '   </span>' +
            '</label>'
        ;
        return html;
    }
};
