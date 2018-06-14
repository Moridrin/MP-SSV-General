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

    addNew: function (containerId) {
        let container = document.getElementById(containerId);
        let tr = document.createElement('tr');
        let properties = {
            name: '',
            type: 'text',
        };

        tr.setAttribute('id', 'model_' + null);
        tr.dataset.properties = JSON.stringify(properties);

        generalFunctions.removeElement(document.getElementById('no-items'));
        container.appendChild(tr);

        console.log(tr);
        console.log(container);

        this.edit(null);
        tr.querySelector('[name="name"]').focus();
    },

    edit: function (id) {
        this.closeEditor();
        generalFunctions.editor.current = id;
        generalFunctions.editor.isOpen = true;
        let tr = document.getElementById("model_" + id);
        console.log(tr.dataset.properties);
        let properties = jQuery.parseJSON(tr.dataset.properties);
        tr.setAttribute('class', 'inline-edit-row');

        let html =
            '<td colspan="5" class="colspanchange" id="editor">' +
            '   <fieldset class="inline-edit-col" style="width: 30%;">' +
            '      <legend class="inline-edit-legend" id="edit-type" data-edit-type="edit">Edit Field</legend>'
        ;
        html += generalFunctions.editor.getInputField('Name', 'name', properties.name, 'text', {'onkeydown': 'fieldsManager.onKeyDown()'});
        html +=
            '   </fieldset>' +
            '   <fieldset class="inline-edit-col" style="width: 30%; margin: 32px 4% 0;">'
        ;
        html += generalFunctions.editor.getSelectInputField('Input Type', 'type', params.inputTypes, properties.type, {'onkeydown': 'fieldsManager.onKeyDown()', 'onchange': 'fieldsManager.typeChanged()'});
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

        jQuery('#model_' + id + ' select[name="type"]').select2({
            tags: true,
        });
        this.typeChanged();
    },

    customize: function (id) {
        this.closeEditor();
        generalFunctions.editor.current = id;
        generalFunctions.editor.isOpen = true;
        let tr = document.getElementById('model_' + id);
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
            '<input type="hidden" name="form_fields[]" value="' + tr.dataset.baseid + '">' +
            '<td colspan="5" class="colspanchange">' +
            '   <fieldset class="inline-edit-col" style="width: 50%;">' +
            '       <legend class="inline-edit-legend" id="edit-type" data-edit-type="customize">Customize</legend>'
        ;
        if (fieldSpecification.properties.includes('title')) {
            if (properties.title === undefined) {
                properties.title = '';
            }
            html += generalFunctions.editor.getInputField('Title', 'title', properties.title, 'text', {'onkeydown': 'fieldsManager.onKeyDown()'});
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
                html += generalFunctions.editor.getInputField(title + ' Classes', id + '_classes', properties.classes[id], 'textarea', {'onkeydown': 'fieldsManager.onKeyDown()'});
            }
        }
        if (fieldSpecification.properties.includes('required')) {
            if (properties.required === undefined) {
                properties.required = 'false';
            }
            html += generalFunctions.editor.getCheckboxInputField('Required', 'required', properties.required, '', {'onkeydown': 'fieldsManager.onKeyDown()'});
        }
        if (fieldSpecification.properties.includes('placeholder')) {
            if (properties.placeholder === undefined) {
                properties.placeholder = '';
            }
            html += generalFunctions.editor.getInputField('Placeholder', 'placeholder', properties.placeholder, 'text', {'onkeydown': 'fieldsManager.onKeyDown()'});
        }
        if (fieldSpecification.properties.includes('pattern')) {
            if (properties.pattern === undefined) {
                properties.pattern = '';
            }
            html += generalFunctions.editor.getInputField('Pattern', 'pattern', properties.pattern, 'text', {'onkeydown': 'fieldsManager.onKeyDown()'});
        }
        if (fieldSpecification.properties.includes('min')) {
            if (properties.max === undefined) {
                properties.max = '';
            }
            html += generalFunctions.editor.getInputField('Min', 'min', properties.max, 'number', {'onkeydown': 'fieldsManager.onKeyDown()'});
        }
        if (fieldSpecification.properties.includes('size')) {
            if (properties.size === undefined) {
                properties.size = '';
            }
            html += generalFunctions.editor.getInputField('Size', 'size', properties.size, 'number', {'onkeydown': 'fieldsManager.onKeyDown()'});
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
                html += generalFunctions.editor.getSelectInputField('Default Value', 'defaultValue', JSON.parse(properties.value), properties.defaultValue, {'onkeydown': 'fieldsManager.onKeyDown()'});
            } else if (tr.dataset.type === 'checkbox') {
                html += generalFunctions.editor.getInputField('Label', 'defaultValue', properties.defaultValue, 'text', {'onkeydown': 'fieldsManager.onKeyDown()'});
            } else {
                html += generalFunctions.editor.getInputField('Default Value', 'defaultValue', properties.defaultValue, 'text', {'onkeydown': 'fieldsManager.onKeyDown()'});
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
                html += generalFunctions.editor.getInputField(title + ' Styles', id + '_styles', properties.styles[id], 'textarea', {'onkeydown': 'fieldsManager.onKeyDown()'});
            }
        }
        if (fieldSpecification.properties.includes('autoComplete')) {
            if (properties.autoComplete === undefined) {
                properties.autoComplete = 'true';
            }
            html += generalFunctions.editor.getCheckboxInputField('AutoComplete', 'autoComplete', properties.autoComplete, '', {'onkeydown': 'fieldsManager.onKeyDown()'});
        }
        if (fieldSpecification.properties.includes('optionsList')) {
            if (properties.optionsList === undefined) {
                properties.optionsList = '';
            }
            html += generalFunctions.editor.getInputField('Options List', 'optionsList', properties.optionsList, 'text', {'onkeydown': 'fieldsManager.onKeyDown()'});
        }
        if (fieldSpecification.properties.includes('step')) {
            if (properties.step === undefined) {
                properties.step = '';
            }
            html += generalFunctions.editor.getInputField('Step', 'step', properties.step, 'number', {'onkeydown': 'fieldsManager.onKeyDown()'});
        }
        if (fieldSpecification.properties.includes('max')) {
            if (properties.max === undefined) {
                properties.max = '';
            }
            html += generalFunctions.editor.getInputField('Max', 'max', properties.max, 'number', {'onkeydown': 'fieldsManager.onKeyDown()'});
        }
        if (fieldSpecification.properties.includes('multiple')) {
            if (properties.multiple === undefined) {
                properties.multiple = '';
            }
            html += generalFunctions.editor.getCheckboxInputField('Multiple', 'multiple', properties.multiple, '', {'onkeydown': 'fieldsManager.onKeyDown()'});
        }
        if (fieldSpecification.properties.includes('profileField')) {
            if (properties.profileField === undefined) {
                properties.profileField = 'true';
            }
            html += generalFunctions.editor.getCheckboxInputField('Profile Field', 'profileField', properties.profileField, '', {'onkeydown': 'fieldsManager.onKeyDown()'});
        }
        html +=
            '   </fieldset>' +
            '   <div class="submit inline-edit-save" style="float: none;">' +
            '      <button type="button" class="button cancel alignleft" onclick="fieldsManager.cancel(\'' + id + '\')">Cancel</button>' +
            '      <input type="hidden" id="_inline_edit" name="_inline_edit" value="' + id + '">' +
            '      <button type="button" class="button button-primary save alignright" onclick="fieldsManager.saveCustomization(\'' + id + '\')">Update</button>' +
            '      <br class="clear">' +
            '   </div>' +
            '</td>'
        ;
        tr.innerHTML = html;
    },

    deleteRow: function (id) {
        let tr = document.getElementById('model_' + id);
        let container = tr.parentElement;
        generalFunctions.removeElement(tr);
        if (id !== '') {
            jQuery.post(
                params.urls.ajax,
                {
                    action: params.actions.delete,
                    shared: params.isShared,
                    formId: params.formId,
                    id: id,
                },
                function (data) {
                    generalFunctions.ajaxResponse(data);
                }
            );
        }
        if (container.childElementCount === 0) {
            container.innerHTML = this.getEmptyRow();
        }
    },

    typeChanged: function () {
        let type = jQuery('#model_' + generalFunctions.editor.current + ' select[name=type]').val();
        if (type === 'role_checkbox') {
            fieldsManager.switchNameFieldToSelect();
        } else {
            fieldsManager.switchNameFieldToInput();
        }
        if (type === 'hidden') {
            fieldsManager.addTextValueContainer('');
        } else if (type === 'select') {
            fieldsManager.addSelectValueContainer([], true);
        } else if (type === 'role_select') {
            fieldsManager.addSelectValueContainer(params.roles, false);
        } else {
            fieldsManager.removeValueContainer();
        }
    },

    cancel: function () {
        this.closeEditor();
    },

    saveEdit: function () {
        let tr = document.getElementById('model_' + generalFunctions.editor.current);
        let id = generalFunctions.editor.current;
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
                id: id,
                name: $nameInput.value,
                properties: properties,
                oldName: generalFunctions.editor.current,
            },
            function (data) {
                if (generalFunctions.ajaxResponse(data)) {
                    let id = JSON.parse(data)['id'];
                    tr.setAttribute('id', 'model_' + id);
                    generalFunctions.editor.current = id;
                    fieldsManager.closeEditor();
                }
            }
        );
    },

    saveCustomization: function () {
        let id = generalFunctions.editor.current;
        let tr = document.getElementById('model_' + id);
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
        jQuery.post(
            params.urls.ajax,
            {
                action: params.actions.save,
                shared: params.isShared,
                formId: params.formId,
                properties: properties,
                oldName: properties['name'],
                id: id,
            },
            function (data) {
                generalFunctions.ajaxResponse(data);
            }
        );
    },

    closeEditor: function () {
        if (generalFunctions.editor.isOpen === false) {
            return;
        }
        let id = generalFunctions.editor.current;
        let tr = document.getElementById('model_' + id);
        if (id === null) {
            let container = tr.parentElement;
            generalFunctions.removeElement(tr);
            if (container.childElementCount === 0) {
                container.innerHTML = this.getEmptyRow();
            }
            generalFunctions.editor.current = null;
            generalFunctions.editor.isOpen = false;
            return;
        }
        let properties = JSON.parse(tr.dataset.properties);
        if (properties.name === '') {
            this.deleteRow(id);
        }
        tr.innerHTML =
            '<th class="check-column">' +
            '   <input type="checkbox" name="ids[]" value="' + id + '">' +
            '</th>' +
            '<td>' +
            '   <strong>' + properties.name + '</strong>' +
            '   <div class="row-actions">' +
            '       <span class="inline"><a href="javascript:void(0)" onclick="fieldsManager.edit(\'' + id + '\')" class="editinline">Edit</a> | </span>' +
            '       <span class="inline"><a href="javascript:void(0)" onclick="fieldsManager.customize(\'' + id + '\')" class="editinline">Customize</a> | </span>' +
            '       <span class="trash"><a href="javascript:void(0)" onclick="fieldsManager.deleteRow(\'' + id + '\')" class="submitdelete">Trash</a></span>' +
            '   </div>' +
            '</td>' +
            '<td>' + properties.type + '</td>' +
            '<td>' + properties.value + '</td>'
        ;
        tr.setAttribute('class', 'inactive');
        generalFunctions.editor.current = null;
        generalFunctions.editor.isOpen = false;
    },

    getEmptyRow: function () {
        return '' +
            '<tr id="no-items" class="no-items">' +
            '    <td class="colspanchange" colspan="8">No Items found</td>' +
            '</tr>'
            ;
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
        let tr = document.getElementById('model_' + generalFunctions.editor.current);
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
        document.getElementById('value_container').innerHTML = generalFunctions.editor.getSelectInputField('Options', 'options[]', options, selected, {'onkeydown': 'fieldsManager.onKeyDown()'});
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
        newField.innerHTML = generalFunctions.editor.getSelectInputField('Name', 'name', params.roles, value, {'onkeydown': 'fieldsManager.onKeyDown()'});
        container.parentElement.replaceChild(newField, container);
    },

    switchNameFieldToInput: function () {
        let container = document.getElementById('name_container');
        let value = container.querySelector('[name="name"]').value;
        let newField = document.createElement('div');
        newField.innerHTML = generalFunctions.editor.getInputField('Name', 'name', value, 'text', {'onkeydown': 'fieldsManager.onKeyDown()'});
        container.parentElement.replaceChild(newField, container);
    },

    onKeyDown: function () {
        let $nameInput = event.path[0];
        let editType = document.getElementById('edit-type').dataset.editType;
        if (event.keyCode === 13) {
            event.preventDefault();
            if (editType === 'edit') {
                this.saveEdit();
            } else {
                this.saveCustomization();
            }
            return false;
        } else if (editType === 'edit') {
            $nameInput.setCustomValidity('');
            $nameInput.reportValidity();
        }
    },
};
