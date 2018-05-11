// noinspection JSUnusedGlobalSymbols
let formEditor = {
    customize: function (name) {
        this.closeEditor();
        fieldsManager.editor.current = name;
        fieldsManager.editor.isOpen = true;
        let tr = document.getElementById('model_' + name);
        let properties = JSON.parse(tr.dataset.properties);
        tr.setAttribute('class', 'inline-edit-row');
        tr.removeAttribute('draggable');
        let fieldSpecification = null;
        if (fieldsManager.fieldSpecifications[properties.type] !== undefined) {
            fieldSpecification = fieldsManager.fieldSpecifications[properties.type];
        } else {
            fieldSpecification = fieldsManager.fieldSpecifications['custom'];
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
            html += fieldsManager.editor.getInputField('Title', 'title', properties.title, 'text', []);
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
                html += fieldsManager.editor.getInputField(title + ' Classes', id + '_classes', properties.classes[id], 'textarea', []);
            }
        }
        if (fieldSpecification.properties.includes('required')) {
            if (properties.required === undefined) {
                properties.required = 'false';
            }
            html += fieldsManager.editor.getCheckboxInputField('Required', 'required', properties.required, '', []);
        }
        if (fieldSpecification.properties.includes('placeholder')) {
            if (properties.placeholder === undefined) {
                properties.placeholder = '';
            }
            html += fieldsManager.editor.getInputField('Placeholder', 'placeholder', properties.placeholder, 'text', []);
        }
        if (fieldSpecification.properties.includes('pattern')) {
            if (properties.pattern === undefined) {
                properties.pattern = '';
            }
            html += fieldsManager.editor.getInputField('Pattern', 'pattern', properties.pattern, 'text', []);
        }
        if (fieldSpecification.properties.includes('min')) {
            if (properties.max === undefined) {
                properties.max = '';
            }
            html += fieldsManager.editor.getInputField('Min', 'min', properties.max, 'number', []);
        }
        if (fieldSpecification.properties.includes('size')) {
            if (properties.size === undefined) {
                properties.size = '';
            }
            html += fieldsManager.editor.getInputField('Size', 'size', properties.size, 'number', []);
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
                html += fieldsManager.editor.getSelectInputField('Default Value', 'defaultValue', JSON.parse(properties.value), properties.defaultValue, []);
            } else if (tr.dataset.type === 'checkbox') {
                html += fieldsManager.editor.getInputField('Label', 'defaultValue', properties.defaultValue, 'text', []);
            } else {
                html += fieldsManager.editor.getInputField('Default Value', 'defaultValue', properties.defaultValue, 'text', []);
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
                html += fieldsManager.editor.getInputField(title + ' Styles', id + '_styles', properties.styles[id], 'textarea', []);
            }
        }
        if (fieldSpecification.properties.includes('autoComplete')) {
            if (properties.autoComplete === undefined) {
                properties.autoComplete = 'true';
            }
            html += fieldsManager.editor.getCheckboxInputField('AutoComplete', 'autoComplete', properties.autoComplete, '', []);
        }
        if (fieldSpecification.properties.includes('optionsList')) {
            if (properties.optionsList === undefined) {
                properties.optionsList = '';
            }
            html += fieldsManager.editor.getInputField('Options List', 'optionsList', properties.optionsList, 'text', []);
        }
        if (fieldSpecification.properties.includes('step')) {
            if (properties.step === undefined) {
                properties.step = '';
            }
            html += fieldsManager.editor.getInputField('Step', 'step', properties.step, 'number', []);
        }
        if (fieldSpecification.properties.includes('max')) {
            if (properties.max === undefined) {
                properties.max = '';
            }
            html += fieldsManager.editor.getInputField('Max', 'max', properties.max, 'number', []);
        }
        if (fieldSpecification.properties.includes('multiple')) {
            if (properties.multiple === undefined) {
                properties.multiple = '';
            }
            html += fieldsManager.editor.getCheckboxInputField('Multiple', 'multiple', properties.multiple, '', []);
        }
        if (fieldSpecification.properties.includes('profileField')) {
            if (properties.profileField === undefined) {
                properties.profileField = 'true';
            }
            html += fieldsManager.editor.getCheckboxInputField('Profile Field', 'profileField', properties.profileField, '', []);
        }
        html +=
            '   </fieldset>' +
            '   <div class="submit inline-edit-save" style="float: none;">' +
            '      <button type="button" class="button cancel alignleft" onclick="formEditor.cancel(\'' + name + '\')">Cancel</button>' +
            '      <input type="hidden" id="_inline_edit" name="_inline_edit" value="' + name + '">' +
            '      <button type="button" class="button button-primary save alignright" onclick="formEditor.saveCustomization(\'' + name + '\')">Update</button>' +
            '      <br class="clear">' +
            '   </div>' +
            '</td>'
        ;
        tr.innerHTML = html;
    },

    cancel: function () {
        this.closeEditor();
    },

    saveCustomization: function () {
        let id = fieldsManager.editor.current;
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
    },

    closeEditor: function () {
        if (fieldsManager.editor.isOpen === false) {
            return;
        }
        let id = fieldsManager.editor.current;
        let tr = document.getElementById('model_' + id);
        if (id === null) {
            let container = tr.parentElement;
            generalFunctions.removeElement(tr);
            if (container.childElementCount === 0) {
                container.innerHTML = fieldsManager.getEmptyRow();
            }
            fieldsManager.editor.current = null;
            fieldsManager.editor.isOpen = false;
            return;
        }
        let properties = JSON.parse(tr.dataset.properties);
        tr.innerHTML =
            '<td>' +
            '   <strong class="fieldName_js">' + properties.title + '</strong>' +
            '   <input type="hidden" name="fields[]" value="' + tr.dataset.properties.replace(/"/g, "&quot;") + '">' +
            '</td>' +
            '<td>' + properties.type + '</td>' +
            '<td>' + properties.value +
            '    <div class="row-actions" style="float: right">' +
            '        <a href="javascript:void(0)" onclick="formEditor.customize(\'' + properties.name + '\')" class="editinline"><span class="dashicons dashicons-admin-customizer"></span></a>' +
            '    </div>' +
            '</td>'
        ;
        tr.setAttribute('draggable', 'true');
        tr.setAttribute('class', 'formField');
        fieldsManager.editor.current = null;
        fieldsManager.editor.isOpen = false;
    },
};

(function ($) {
    $(document).ready(function () {
        let $label = $('#title-prompt-text');
        let $input = $('#title');
        $label.focus(function () {
            $(this).hide();
        });
        $input.focus(function () {
            $label.hide();
        });
        $input.focusout(function () {
            if ($input.val() === '') {
                $label.show();
            }
        });
        if ($input.val() !== '') {
            $label.hide();
        }

        $('.hndle').click(function () {
            $(this.nextSibling.nextSibling).toggle();
            $(this.parentNode).toggleClass('closed');
        });

        $('.handlediv').click(function () {
            $(this.nextSibling.nextSibling.nextSibling.nextSibling).toggle();
            $(this.parentNode).toggleClass('closed');
        });

        let formFieldsListTop = document.getElementById('formFieldsListTop');
        let formFieldsListBottom = document.getElementById('formFieldsListBottom');
        let formFieldsList = document.getElementById('the-list');
        let fieldsList = document.getElementById('fieldsList');
        let $dropPreview = $('#dropPreview');
        let $noItems = $('#no-items');
        let dragElement = null;

        function handleDragStart(e) {
            dragElement = this;
            e.dataTransfer.effectAllowed = 'copyMove';
            e.dataTransfer.setData('text/html', this.outerHTML);
            this.classList.add('dragElem');
        }

        function handleDragOver(e) {
            if (e.preventDefault) {
                e.preventDefault();
            }
            if (dragElement && (dragElement.parentNode === fieldsList)) {
                e.dataTransfer.dropEffect = 'copy';
            } else {
                e.dataTransfer.dropEffect = 'move';
            }
            return false;
        }

        function handleDragEnter(e) {
            if (e.preventDefault) {
                e.preventDefault();
            }
            $dropPreview.show();
            if (this === formFieldsListTop) {
                formFieldsList.insertBefore($dropPreview[0], formFieldsList.firstElementChild);
            } else if (this === formFieldsListBottom) {
                formFieldsList.insertBefore($dropPreview[0], $noItems[0]);
            } else {
                $dropPreview.insertAfter($(this));
            }
        }

        function handleDragLeave(e) {
            if (e.preventDefault) {
                e.preventDefault();
            }
            if (this === formFieldsListTop) {
                formFieldsList.firstElementChild.classList.remove('topHeaderHover');
            } else if (this === formFieldsListBottom) {
                formFieldsList.lastElementChild.classList.remove('bottomHeaderHover');
            } else {
                this.classList.remove('hover');
            }
        }

        function handleDrop(e) {
            if (e.stopPropagation) {
                e.stopPropagation();
            }
            let dropElement = null;
            if (dragElement && dragElement.parentNode === fieldsList) {
                let field = JSON.parse(dragElement.dataset.field);
                dropElement = document.createElement('tr');
                dropElement.setAttribute('id', 'model_' + field.name);
                dropElement.setAttribute('draggable', 'true');
                dropElement.setAttribute('class', 'formField');
                dropElement.dataset.properties = dragElement.dataset.field;
                dropElement.innerHTML =
                    '<td>' +
                    '   <strong class="fieldName_js">' + field.title + '</strong>' +
                    '   <input type="hidden" name="fields[]" value="' + dragElement.dataset.field.replace(/"/g, "&quot;") + '\">' +
                    '</td>' +
                    '<td>' + field.type + '</td>' +
                    '<td>' + field.value +
                    '    <div class="row-actions" style="float: right">' +
                    '        <a href="javascript:void(0)" onclick="formEditor.customize(\'' + field.name + '\')" class="editinline"><span class="dashicons dashicons-admin-customizer"></span></a>' +
                    '    </div>' +
                    '</td>'
                ;
            } else {
                dropElement = dragElement.cloneNode(true);
                dropElement.setAttribute('class', 'formField');
            }
            if (this === formFieldsListTop) {
                formFieldsList.insertBefore(dropElement, formFieldsList.firstElementChild);
            } else if (this === formFieldsListBottom) {
                formFieldsList.insertBefore(dropElement, $noItems[0]);
            } else {
                $(dropElement).insertAfter(this);
            }
            dragElement.isDropped = true;
            addDragEvents(dropElement);
            addDropEvents(dropElement);
            return false;
        }

        function handleDragEnd() {
            [].forEach.call(document.getElementsByClassName('topHeaderHover'), function (element) {
                element.classList.remove("topHeaderHover");
            });
            [].forEach.call(document.getElementsByClassName('bottomHeaderHover'), function (element) {
                element.classList.remove("bottomHeaderHover");
            });
            [].forEach.call(document.getElementsByClassName('hover'), function (element) {
                element.classList.remove("hover");
            });
            if (dragElement.parentElement === fieldsList) {
                if (dragElement.isDropped) {
                    $(dragElement).hide();
                }
            } else {
                let fieldId = $(dragElement).find('.fieldName_js')[0].innerText;
                if (dragElement.isDropped) {
                    $('#baseField_' + fieldId).hide();
                } else {
                    $('#baseField_' + fieldId).show();
                }
                generalFunctions.removeElement(dragElement);
            }
            dragElement.classList.remove('dragElem');
            if (formFieldsList.children.length > 2) {
                $noItems.hide();
                $noItems.insertAfter($(formFieldsList.lastElementChild));
            } else {
                $noItems.show();
            }
            $dropPreview.insertAfter($(formFieldsList.lastElementChild));
            $dropPreview.hide();
            dragElement = null;
        }

        function addDragEvents(elem) {
            elem.addEventListener('dragstart', handleDragStart, false);
            elem.addEventListener('dragend', handleDragEnd, false);
        }

        function addDropEvents(elem) {
            elem.addEventListener('dragenter', handleDragEnter, false);
            elem.addEventListener('dragover', handleDragOver, false);
            elem.addEventListener('dragleave', handleDragLeave, false);
            elem.addEventListener('drop', handleDrop, false);
        }

        addDropEvents(formFieldsListTop);
        addDropEvents(formFieldsListBottom);
        let baseFields = document.querySelectorAll('.baseField');
        [].forEach.call(baseFields, addDragEvents);
        let formFields = document.querySelectorAll('.formField');
        [].forEach.call(formFields, addDragEvents);
        [].forEach.call(formFields, addDropEvents);
        addDropEvents($dropPreview[0]);
        $dropPreview.hide();
        addDropEvents($noItems[0]);
        if (formFieldsList.children.length > 2) {
            $noItems.hide();
        }
    });
})(jQuery);
