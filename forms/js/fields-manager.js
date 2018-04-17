// noinspection JSUnresolvedVariable
let params = mp_ssv_fields_manager_params;

let fieldsManager = {

    fieldSpecifications: {
        text: {
            parts: [
                'div',
                'title',
                'input',
            ],
            properties: [
                'title',
                'classes',
                'defaultValue',
                'styles',
                'required',
                'autoComplete',
                'placeholder',
                'optionsList',
                'pattern',
                'profileField',
            ],
        },
        email: {
            parts: [
                'div',
                'title',
                'input',
            ],
            properties: [
                'title',
                'classes',
                'defaultValue',
                'styles',
                'required',
                'autoComplete',
                'placeholder',
                'optionsList',
                'pattern',
                'profileField',
            ],
        },
        password: {
            parts: [
                'div',
                'title',
                'input',
            ],
            properties: [
                'title',
                'classes',
                'defaultValue',
                'styles',
                'required',
                'autoComplete',
                'optionsList',
                'pattern',
            ],
        },
        checkbox: {
            parts: [
                'div',
                'title',
                'label',
            ],
            properties: [
                'title',
                'defaultValue',
                'classes',
                'styles',
                'required',
                'profileField',
            ],
        },
        datetime: {
            parts: [
                'div',
                'title',
                'input',
            ],
            properties: [
                'title',
                'classes',
                'defaultValue',
                'styles',
                'required',
                'profileField',
            ],
        },
        file: {
            parts: [
                'div',
                'title',
                'input',
            ],
            properties: [
                'title',
                'classes',
                'defaultValue',
                'styles',
                'required',
                'profileField',
            ],
        },
        select: {
            parts: [
                'div',
                'title',
                'input',
            ],
            properties: [
                'title',
                'classes',
                'defaultValue',
                'styles',
                'required',
                'multiple',
                'size',
                'profileField',
            ],
        },
        number: {
            parts: [
                'div',
                'title',
                'input',
            ],
            properties: [
                'title',
                'classes',
                'defaultValue',
                'styles',
                'required',
                'placeholder',
                'step',
                'min',
                'max',
                'profileField',
            ],
        },
        custom: {
            parts: [
                'div',
                'title',
                'input',
            ],
            properties: [
                'title',
                'classes',
                'defaultValue',
                'styles',
                'required',
                'autoComplete',
                'placeholder',
                'optionsList',
                'pattern',
                'step',
                'min',
                'max',
                'size',
                'multiple',
                'profileField',
            ],
        },
    },

    editor: {

        current: null,

        getInputField: function (title, name, value, type, events) {
            events.onkeydown = 'fieldsManager.editor.onKeyDown()';
            let eventsString = '';
            for (let [eventName, event] of Object.entries(events)) {
                eventsString += eventName + '="' + event + '" ';
            }
            let html =
                '<label id="' + name + '_container">' +
                '   <span class="title">' + title + '</span>' +
                '   <span class="input-text-wrap">'
            ;
            if (type === 'textarea') {
                html += '<textarea name="' + name + '">' + value + '</textarea>';
            } else {
                html += '<input type="' + type + '" name="' + name + '" value="' + value + '" autocomplete="off" style="width: 100%;" ' + eventsString + '>';
            }
            html +=
                '   </span>' +
                '</label>'
            ;
            return html;
        },

        getCheckboxInputField: function (title, name, value, description, events) {
            let checked = (value === true || value === 'true') ? 'checked="checked"' : '';
            let eventsString = '';
            for (let [eventName, event] of Object.entries(events)) {
                eventsString += eventName + '="' + event + '" ';
            }
            return '' +
                '<label>' +
                '   <span class="title">' + title + '</span>' +
                '   <span class="input-text-wrap">' +
                '       <input type="checkbox" name="' + name + '" value="true" ' + checked + ' title="' + description + '" ' + eventsString + '>' +
                '   </span>' +
                '</label>'
                ;
        },

        getSelectInputField: function (title, name, options, values, events) {
            let multiple = name.endsWith('[]') ? ' multiple="multiple"' : '';
            let eventsString = '';
            if (!Array.isArray(values)) {
                values = [values];
            }
            for (let [eventName, event] of Object.entries(events)) {
                eventsString += eventName + '="' + event + '" ';
            }
            let html =
                '<label>' +
                '   <span class="title">' + title + '</span>' +
                '   <span class="input-text-wrap">'
            ;
            html += '<select name="' + name + '" style="width: 100%;" ' + eventsString + multiple + '>';
            if (options instanceof Object) {
                options = Object.values(options);
            }
            for (let i = 0; i < options.length; ++i) {
                if (values.indexOf(options[i]) !== -1) {
                    html += '<option selected="selected">' + options[i] + '</option>';
                } else {
                    html += '<option>' + options[i] + '</option>';
                }
            }
            html += '</select>';
            html +=
                '   </span>' +
                '</label>'
            ;
            return html;
        },

        onKeyDown: function () {
            let $nameInput = event.path[0];
            let editType = document.getElementById('edit-type').dataset.editType;
            if (editType === 'edit') {
                if (event.keyCode === 13) {
                    fieldsManager.saveEdit();
                    event.preventDefault();
                    return false;
                } else {
                    $nameInput.setCustomValidity('');
                    $nameInput.reportValidity();
                }
            } else if (editType === 'customize') {
                if (event.keyCode === 13) {
                    fieldsManager.saveCustomization();
                    event.preventDefault();
                    return false;
                }
            }
        },

        addTextValueContainer: function (value) {
            document.getElementById('value_container').innerHTML =
                '<div class="inline-edit-col">' +
                '   <label>' +
                '       <span class="title">Value</span>' +
                '       <span class="input-text-wrap">' +
                '            <input type="text" name="value" value="' + value + '" autocomplete="off" data-old-value="' + value + '">' +
                '       </span>' +
                '   </label>' +
                '</div>'
            ;
        },

        addSelectValueContainer: function (options, tags) {
            let tr = document.getElementById('field_' + this.current);
            let properties = JSON.parse(tr.dataset.properties);
            let selected = properties.value;
            if (selected === undefined || !Array.isArray(selected)) {
                selected = [];
            }
            selected.forEach(function (value) {
                if (options.indexOf(value) === -1) {
                    options.push(value);
                }
            });
            document.getElementById('value_container').innerHTML = this.getSelectInputField('Options', 'options[]', options, selected, []);
            jQuery('[name="options[]"]').select2({
                tags: tags,
                tokenSeparators: [';']
            });
        },

        removeValueContainer: function () {
            document.getElementById('value_container').innerHTML = '';
        },

        switchNameFieldToSelect: function () {
            let container = document.getElementById('name_container');
            let value = container.querySelector('[name="name"]').value;
            let newField = document.createElement('div');
            newField.innerHTML = this.getSelectInputField('Name', 'name', params.roles, value, []);
            container.parentElement.replaceChild(newField, container);
        },

        switchNameFieldToInput: function () {
            let container = document.getElementById('name_container');
            let value = container.querySelector('[name="name"]').value;
            let newField = document.createElement('div');
            newField.innerHTML = this.getInputField('Name', 'name', value, 'text', []);
            container.parentElement.replaceChild(newField, container);
        },
    },

    addNew: function (containerId) {
        let container = document.getElementById(containerId);
        let tr = document.createElement('tr');
        let properties = {
            name: '',
            type: 'text',
        };

        tr.setAttribute('id', 'field_' + properties.name);
        tr.dataset.properties = JSON.stringify(properties);

        generalFunctions.removeElement(document.getElementById('no-items'));
        container.appendChild(tr);

        this.edit(properties.name);
        tr.querySelector('[name="name"]').focus();
    },

    edit: function (fieldName) {
        this.closeEditor();
        this.editor.current = fieldName;
        let tr = document.getElementById("field_" + fieldName);
        let properties = jQuery.parseJSON(tr.dataset.properties);
        tr.setAttribute('class', 'inline-edit-row');

        let html =
            '<td colspan="5" class="colspanchange" id="editor">' +
            '   <fieldset class="inline-edit-col" style="width: 30%;">' +
            '      <legend class="inline-edit-legend" id="edit-type" data-edit-type="edit">Edit Field</legend>'
        ;
        html += this.editor.getInputField('Name', 'name', properties.name, 'text', []);
        html +=
            '   </fieldset>' +
            '   <fieldset class="inline-edit-col" style="width: 30%; margin: 32px 4% 0;">'
        ;
        html += this.editor.getSelectInputField('Input Type', 'type', params.inputTypes, properties.type, {'onchange': 'fieldsManager.typeChanged()'});
        html +=
            '   </fieldset>' +
            '   <fieldset id="value_container" class="inline-edit-col" style="width: 30%; margin-top: 32px;">' +
            '   </fieldset>' +
            '   <div class="submit inline-edit-save">' +
            '       <button type="button" class="button cancel alignleft" onclick="fieldsManager.cancel()">Cancel</button>' +
            '       <button type="button" class="button button-primary save alignright" onclick="fieldsManager.saveEdit()">Save</button>' +
            '       <br class="clear">' +
            '   </div>' +
            '</td>'
        ;
        tr.innerHTML = html;

        jQuery('#field_' + fieldName + ' select[name="type"]').select2({
            tags: true,
        });
        this.typeChanged();
    },

    customize: function (fieldName) {
        this.closeEditor();
        this.editor.current = fieldName;
        let tr = document.getElementById('field_' + fieldName);
        let properties = JSON.parse(tr.dataset.properties);
        tr.setAttribute('class', 'inline-edit-row');
        tr.removeAttribute('draggable');
        let fieldSpecification = null;
        if (this.fieldSpecifications[properties.type] !== undefined) {
            fieldSpecification = this.fieldSpecifications[properties.type];
        } else {
            fieldSpecification = this.fieldSpecifications['custom'];
        }
        let html =
            '<input type="hidden" name="form_fields[]" value="' + tr.dataset.baseFieldName + '">' +
            '<td colspan="5" class="colspanchange">' +
            '   <fieldset class="inline-edit-col" style="width: 50%;">' +
            '       <legend class="inline-edit-legend" id="edit-type" data-edit-type="customize">Customize</legend>'
        ;
        if (fieldSpecification.properties.includes('title')) {
            if (properties.title === undefined) {
                properties.title = '';
            }
            html += this.editor.getInputField('Title', 'title', properties.title, 'text', []);
        }
        if (fieldSpecification.properties.includes('classes')) {
            if (properties.classes === undefined) {
                properties.classes = [];
            }
            for (let i = 0; i < fieldSpecification.parts.length; ++i) {
                let id = fieldSpecification.parts[i];
                let title = id.charAt(0).toUpperCase() + id.slice(1);
                if (properties.classes[id] === undefined) {
                    properties.classes[id] = '';
                }
                html += this.editor.getInputField(title + ' Classes', id + '_classes', properties.classes[id], 'textarea', []);
            }
        }
        if (fieldSpecification.properties.includes('required')) {
            if (properties.required === undefined) {
                properties.required = 'false';
            }
            html += this.editor.getCheckboxInputField('Required', 'required', properties.required, '', []);
        }
        if (fieldSpecification.properties.includes('placeholder')) {
            if (properties.placeholder === undefined) {
                properties.placeholder = '';
            }
            html += this.editor.getInputField('Placeholder', 'placeholder', properties.placeholder, 'text', []);
        }
        if (fieldSpecification.properties.includes('pattern')) {
            if (properties.pattern === undefined) {
                properties.pattern = '';
            }
            html += this.editor.getInputField('Pattern', 'pattern', properties.pattern, 'text', []);
        }
        if (fieldSpecification.properties.includes('min')) {
            if (properties.max === undefined) {
                properties.max = '';
            }
            html += this.editor.getInputField('Min', 'min', properties.max, 'number', []);
        }
        if (fieldSpecification.properties.includes('size')) {
            if (properties.size === undefined) {
                properties.size = '';
            }
            html += this.editor.getInputField('Size', 'size', properties.size, 'number', []);
        }
        html +=
            '   </fieldset>' +
            '   <fieldset class="inline-edit-col" style="width: 50%; margin-top: 32px;">'
        ;
        if (fieldSpecification.properties.includes('defaultValue')) {
            if (properties.defaultValue === undefined) {
                properties.defaultValue = '';
            }
            if (tr.dataset.type === 'select') {
                html += this.editor.getSelectInputField('Default Value', 'defaultValue', JSON.parse(properties.value), properties.defaultValue, []);
            } else if (tr.dataset.type === 'checkbox') {
                html += this.editor.getInputField('Label', 'defaultValue', properties.defaultValue, 'text', []);
            } else {
                html += this.editor.getInputField('Default Value', 'defaultValue', properties.defaultValue, 'text', []);
            }
        }
        if (fieldSpecification.properties.includes('styles')) {
            if (properties.styles === undefined) {
                properties.styles = [];
            }
            for (let i = 0; i < fieldSpecification.parts.length; ++i) {
                let id = fieldSpecification.parts[i];
                let title = id.charAt(0).toUpperCase() + id.slice(1);
                if (properties.styles[id] === undefined) {
                    properties.styles[id] = '';
                }
                html += this.editor.getInputField(title + ' Styles', id + '_styles', properties.styles[id], 'textarea', []);
            }
        }
        if (fieldSpecification.properties.includes('autoComplete')) {
            if (properties.autoComplete === undefined) {
                properties.autoComplete = 'true';
            }
            html += this.editor.getCheckboxInputField('AutoComplete', 'autoComplete', properties.autoComplete, '', []);
        }
        if (fieldSpecification.properties.includes('optionsList')) {
            if (properties.optionsList === undefined) {
                properties.optionsList = '';
            }
            html += this.editor.getInputField('Options List', 'optionsList', properties.optionsList, 'text', []);
        }
        if (fieldSpecification.properties.includes('step')) {
            if (properties.step === undefined) {
                properties.step = '';
            }
            html += this.editor.getInputField('Step', 'step', properties.step, 'number', []);
        }
        if (fieldSpecification.properties.includes('max')) {
            if (properties.max === undefined) {
                properties.max = '';
            }
            html += this.editor.getInputField('Max', 'max', properties.max, 'number', []);
        }
        if (fieldSpecification.properties.includes('multiple')) {
            if (properties.multiple === undefined) {
                properties.multiple = '';
            }
            html += this.editor.getCheckboxInputField('Multiple', 'multiple', properties.multiple, '', []);
        }
        if (fieldSpecification.properties.includes('profileField')) {
            if (properties.profileField === undefined) {
                properties.profileField = 'true';
            }
            html += this.editor.getCheckboxInputField('Profile Field', 'profileField', properties.profileField, '', []);
        }
        html +=
            '   </fieldset>' +
            '   <div class="submit inline-edit-save" style="float: none;">' +
            '      <button type="button" class="button cancel alignleft" onclick="fieldsManager.cancel(\'' + fieldName + '\')">Cancel</button>' +
            '      <input type="hidden" id="_inline_edit" name="_inline_edit" value="' + fieldName + '">' +
            '      <button type="button" class="button button-primary save alignright" onclick="fieldsManager.saveCustomization(\'' + fieldName + '\')">Update</button>' +
            '      <br class="clear">' +
            '   </div>' +
            '</td>'
        ;
        tr.innerHTML = html;
    },

    deleteRow: function (fieldName) {
        let tr = document.getElementById('field_' + fieldName);
        let container = tr.parentElement;
        generalFunctions.removeElement(tr);
        if (fieldName !== '') {
            jQuery.post(
                params.urls.ajax,
                {
                    action: params.actions.delete,
                    shared: params.isShared,
                    formId: params.formId,
                    fieldNames: [fieldName],
                },
                function (data) {
                    fieldsManager.ajaxResponse(data);
                }
            );
        }
        if (container.childElementCount === 0) {
            container.innerHTML = this.getEmptyRow();
        }
    },

    typeChanged: function () {
        let type = jQuery('#field_' + this.editor.current + ' select[name=type]').val();
        if (type === 'role_checkbox') {
            this.editor.switchNameFieldToSelect();
        } else {
            this.editor.switchNameFieldToInput();
        }
        if (type === 'hidden') {
            this.editor.addTextValueContainer('');
        } else if (type === 'select') {
            this.editor.addSelectValueContainer([], true);
        } else if (type === 'role_select') {
            this.editor.addSelectValueContainer(params.roles, false);
        } else {
            this.editor.removeValueContainer();
        }
    },

    cancel: function () {
        this.closeEditor();
    },

    saveEdit: function () {
        let tr = document.getElementById('field_' + this.editor.current);
        let properties = JSON.parse(tr.dataset.properties);
        let $nameInput = tr.querySelector('input[name="name"]');
        properties.name = $nameInput.value;
        properties.type = tr.querySelector('select[name="type"]').value;
        if (properties.type === 'hidden') {
            properties.value = tr.querySelector('input[name="value"]').value;
        } else if (properties.type === 'select' || properties.type === 'role_select') {
            let select = tr.querySelector('select[name="options[]"]');
            properties.value = [];
            for (let i = 0; i < select.length; i++) {
                if (select.options[i].selected) {
                    properties.value.push(select.options[i].value);
                }
            }
        } else {
            properties.value = '';
        }
        tr.dataset.properties = JSON.stringify(properties);
        jQuery.post(
            params.urls.ajax,
            {
                action: params.actions.save,
                shared: params.isShared,
                formId: params.formId,
                properties: properties,
                oldName: this.editor.current,
            },
            function (data) {
                if (fieldsManager.ajaxResponse(data)) {
                    fieldsManager.closeEditor();
                    tr.setAttribute('id', 'field_' + properties.name);
                }
            }
        );
    },

    saveCustomization: function () {
        let fieldName = this.editor.current;
        let tr = document.getElementById('field_' + fieldName);
        let properties = JSON.parse(tr.dataset.properties);
        let fieldSpecification = null;
        if (typeof(fieldsManager.fieldSpecifications[properties.type]) === 'undefined') {
            fieldSpecification = fieldsManager.fieldSpecifications['custom'];
        } else {
            fieldSpecification = fieldsManager.fieldSpecifications[properties.type];
        }
        let fieldTypeObjects = fieldSpecification.parts;
        for (let i = 0; i < fieldSpecification.properties.length; ++i) {
            let property = fieldSpecification.properties[i];
            if (property === 'classes' || property === 'styles') {
                properties[property] = {};
                for (let j = 0; j < fieldTypeObjects.length; ++j) {
                    properties[property][fieldTypeObjects[j]] = tr.querySelector('[name="' + fieldTypeObjects[j] + '_' + property + '"]').value;
                }
            } else {
                let element = tr.querySelector('[name="' + property + '"]');
                if (element.getAttribute('type') === 'checkbox') {
                    properties[property] = element.checked;
                } else {
                    properties[property] = element.value;
                }
            }
        }
        tr.dataset.properties = JSON.stringify(properties);
        this.closeEditor();
        tr.setAttribute('id', 'field_' + properties.name);
        jQuery.post(
            params.urls.ajax,
            {
                action: params.actions.save,
                shared: params.isShared,
                formId: params.formId,
                properties: properties,
                oldName: fieldName,
            },
            function (data) {
                fieldsManager.ajaxResponse(data);
            }
        );
    },

    closeEditor: function () {
        if (this.editor.current === null) {
            return;
        }
        let fieldName = this.editor.current;
        let tr = document.getElementById('field_' + fieldName);
        let properties = JSON.parse(tr.dataset.properties);
        if (properties.name === '') {
            this.deleteRow(properties.name);
        }
        tr.innerHTML =
            '<th class="check-column">' +
            '   <input type="checkbox" name="fieldNames[]" value="\'' + fieldName + '\'">' +
            '</th>' +
            '<td>' +
            '   <strong>' + properties.name + '</strong>' +
            '   <div class="row-actions">' +
            '       <span class="inline"><a href="javascript:void(0)" onclick="fieldsManager.edit(\'' + properties.name + '\')" class="editinline">Edit</a> | </span>' +
            '       <span class="inline"><a href="javascript:void(0)" onclick="fieldsManager.customize(\'' + properties.name + '\')" class="editinline">Customize</a> | </span>' +
            '       <span class="trash"><a href="javascript:void(0)" onclick="fieldsManager.deleteRow(\'' + properties.name + '\')" class="submitdelete">Trash</a></span>' +
            '   </div>' +
            '</td>' +
            '<td>' + properties.type + '</td>' +
            '<td>' + properties.value + '</td>'
        ;
        tr.setAttribute('class', 'inactive');
        tr.setAttribute('draggable', 'draggable');
        this.editor.current = null;
    },

    getEmptyRow: function () {
        return '' +
            '<tr id="no-items" class="no-items">' +
            '    <td class="colspanchange" colspan="8">No Fields found</td>' +
            '</tr>'
            ;
    },

    ajaxResponse: function (data) {
        if (data !== '' && data !== '0' && data !== 'success') {
            generalFunctions.showError(data);
        }
    },
};
