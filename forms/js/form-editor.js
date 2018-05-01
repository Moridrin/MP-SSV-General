let formEditor = {
    deleteRow: function (formId) {
        let tr = document.getElementById(formId + '_tr');
        let container = tr.parentElement;
        generalFunctions.removeElement(tr);
        jQuery.post(
            urls.ajax,
            {
                action: actions.delete,
                formIds: [formId]
            }
        );
        event.preventDefault();
        if (container.childElementCount === 0) {
            formEditor.showEmptyTable(container.id);
        }
    },

    showEmptyTable: function (containerId) {
        let container = document.getElementById(containerId);
        container.innerHTML = '' +
            '<tr id="no-items" class="no-items">' +
            '    <td class="colspanchange" colspan="4">No Forms found</td>' +
            '</tr>'
        ;
    }
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
        let formFieldsList = document.getElementById('formFieldsList');
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
                dropElement.setAttribute('draggable', 'true');
                dropElement.setAttribute('class', 'formField');
                dropElement.innerHTML =
                    '<td>' +
                    '   <input type="hidden" name="form_fields[]" value="' + field.name + '">' +
                    '   <strong class="fieldName_js">' + field.name + '</strong>' +
                    '</td>' +
                    '<td>' + field.type + '</td>' +
                    '<td>' + field.value + '</td>'
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
                    $('#field_' + fieldId).hide();
                } else {
                    $('#field_' + fieldId).show();
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
