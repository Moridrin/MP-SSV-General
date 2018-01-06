(function ($) {
    let formFieldId = 0;
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
        let wordPressBaseFieldsList = document.getElementById('wordPressBaseFieldsList');
        let sharedBaseFieldsList = document.getElementById('sharedBaseFieldsList');
        let siteSpecificBaseFieldsList = document.getElementById('siteSpecificBaseFieldsList');
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
            if (dragElement && (dragElement.parentNode === wordPressBaseFieldsList || dragElement.parentNode === sharedBaseFieldsList || dragElement.parentNode === siteSpecificBaseFieldsList)) {
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
            if (this === formFieldsListTop) {
                formFieldsList.firstElementChild.classList.add('topHeaderHover');
            } else if (this === formFieldsListBottom && dragElement !== formFieldsList.lastElementChild) {
                formFieldsList.lastElementChild.classList.add('bottomHeaderHover');
            } else if (this !== dragElement && this !== dragElement.nextSibling) {
                this.classList.add('hover');
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
            if (dragElement && dragElement.parentNode !== formFieldsList) {
                ++formFieldId;
                let field = JSON.parse(dragElement.dataset.field);
                let value = field.bf_value !== null ? field.bf_value : '';
                let properties = {
                    'title': field.bf_title,
                    'classes': {'div': '', 'label': '', 'input': ''},
                    'styles': {'div': '', 'label': '', 'input': ''},
                    'value': value,
                };
                switch (field.bf_inputType) {
                    case 'number':
                        properties['required'] = false;
                        properties['placeholder'] = '';
                        properties['autocomplete'] = true;
                        properties['min'] = null;
                        properties['max'] = null;
                        properties['step'] = 1;
                        break;
                    case 'text':
                        properties['required'] = false;
                        properties['placeholder'] = '';
                        properties['autocomplete'] = true;
                        properties['list'] = '';
                        properties['pattern'] = '';
                        break;
                    default:
                        properties['required'] = false;
                        properties['placeholder'] = '';
                        properties['autocomplete'] = true;
                        properties['list'] = '';
                        properties['pattern'] = '';
                        properties['min'] = null;
                        properties['max'] = null;
                        properties['step'] = 1;
                        break;
                }
                dropElement = document.createElement('tr');
                dropElement.setAttribute('id', formFieldId + '_tr');
                dropElement.setAttribute('class', 'formField');
                dropElement.setAttribute('draggable', 'true');
                dropElement.dataset.name = field.bf_name;
                dropElement.dataset.inputType = field.bf_inputType;
                dropElement.dataset.properties = JSON.stringify(properties);
                dropElement.innerHTML =
                    '<td>' +
                    '   <input type="hidden" name="form_fields[]" value="' + field.bf_name + '">' +
                    '   <strong>' + field.bf_title + '</strong>' +
                    '<span class="inline-actions"> | <a href="javascript:void(0)" onclick="fieldsCustomizer.inlineEdit(' + formFieldId + ')" class="editinline" aria-label="Quick edit “' + field.bf_title + '” inline">Quick Edit</a></span>' +
                    '</td>' +
                    '<td>' + field.bf_inputType + '</td>' +
                    '<td>' + value + '</td>'
                ;
            } else {
                dropElement = dragElement.cloneNode(true);
                dropElement.setAttribute('class', 'formField');
            }
            if (this === formFieldsListTop) {
                formFieldsList.insertBefore(dropElement, formFieldsList.children.item(0));
                removeField(document.getElementById('no-items'));
            } else if (this === formFieldsListBottom) {
                formFieldsList.appendChild(dropElement);
                removeField(document.getElementById('no-items'));
            } else if (this && this.parentNode === formFieldsList) {
                formFieldsList.insertBefore(dropElement, this);
                removeField(document.getElementById('no-items'));
            }
            addDragEvents(dropElement);
            addDropEvents(dropElement);
            return false;
        }

        function handleDragEnd(e) {
            [].forEach.call(document.getElementsByClassName('topHeaderHover'), function (element) {
                element.classList.remove("topHeaderHover");
            });
            [].forEach.call(document.getElementsByClassName('bottomHeaderHover'), function (element) {
                element.classList.remove("bottomHeaderHover");
            });
            [].forEach.call(document.getElementsByClassName('hover'), function (element) {
                element.classList.remove("hover");
            });
            this.classList.remove('dragElem');
            dragElement = null;
            if (!this.classList.contains('baseField')) {
                removeField(this);
                if (formFieldsList.children.length === 0) {
                    let emptyRow = document.createElement('tr');
                    emptyRow.setAttribute('id', 'no-items');
                    emptyRow.setAttribute('class', 'no-items');
                    emptyRow.innerHTML = '<td class="colspanchange" colspan="8">There are no fields in the form yet.<br/>Drag and drop a field from the fields list to add it to the form.</td>';
                    formFieldsList.appendChild(emptyRow);
                    addDropEvents(emptyRow);
                }
            }
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
        let noItemsTr = document.getElementById('no-items');
        if (noItemsTr) {
            addDropEvents(noItemsTr);
        }
        let baseFields = document.querySelectorAll('.baseField');
        [].forEach.call(baseFields, addDragEvents);
        let formFields = document.querySelectorAll('.formField');
        [].forEach.call(formFields, addDragEvents);
        [].forEach.call(formFields, addDropEvents);
    });
})(jQuery);
