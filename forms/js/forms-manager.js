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
        let formFieldsList = document.getElementById('formFieldsList');
        let baseFieldsList = document.getElementById('baseFieldsList');
        let wordPressBaseFieldsList = document.getElementById('wordPressBaseFieldsList');
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
            if (dragElement && dragElement.parentNode === baseFieldsList) {
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
            console.log(this);
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
            if (dragElement.parentNode === baseFieldsList || dragElement.parentNode === wordPressBaseFieldsList) {
                let field = JSON.parse(dragElement.dataset.field);
                let fieldType = dragElement.dataset.fieldType;
                console.log(field);
                dropElement = document.createElement('tr');
                dropElement.setAttribute('draggable', 'true');
                dropElement.setAttribute('class', 'formField');
                dropElement.innerHTML =
                    '<td>' +
                    '   <input type="hidden" name="form_fields[]" value="' + field.bf_name + '">' + field.bf_title +
                    '</td>' +
                    '<td>' + fieldType + '</td>' +
                    '<td>' + field.bf_inputType + '</td>' +
                    '<td>' + field.bf_value + '</td>'
                ;
            } else {
                dropElement = dragElement.cloneNode(true);
                dropElement.setAttribute('class', 'formField');
            }
            if (this === formFieldsListTop) {
                formFieldsList.insertBefore(dropElement, formFieldsList.children.item(0));
            } else if (this === formFieldsListBottom) {
                formFieldsList.appendChild(dropElement);
            } else if (this && this.parentNode === formFieldsList) {
                formFieldsList.insertBefore(dropElement, this);
                removeField(document.getElementById('no-items'));
            }
            addDragEvents(dropElement);
            addDropEvents(dropElement);
            return false;
        }
        function handleDragEnd(e) {
            [].forEach.call(document.getElementsByClassName('topHeaderHover'), function(element) {
                element.classList.remove("topHeaderHover");
            });
            [].forEach.call(document.getElementsByClassName('bottomHeaderHover'), function(element) {
                element.classList.remove("bottomHeaderHover");
            });
            [].forEach.call(document.getElementsByClassName('hover'), function(element) {
                element.classList.remove("hover");
            });
            this.classList.remove('dragElem');
            dragElement = null;
            if (!this.classList.contains('baseField')) {
                removeField(this);
                console.log(formFieldsList.children.length);
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
    });
})(jQuery);
