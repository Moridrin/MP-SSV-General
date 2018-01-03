let formsManager = {
    deleteRow: function (formId) {
        let tr = document.getElementById(formId + '_tr');
        let container = tr.parentElement;
        removeField(tr);
        jQuery.post(
            urls.ajax,
            {
                action: actions.delete,
                formIds: [formId],
            }
        );
        event.preventDefault();
        if (container.childElementCount === 0) {
            formsManager.showEmptyTable(container.id);
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
