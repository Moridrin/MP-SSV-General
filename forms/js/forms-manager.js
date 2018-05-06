// noinspection JSUnresolvedVariable
let params = mp_ssv_forms_manager_params;
// noinspection JSUnusedGlobalSymbols
let formsManager = {
    deleteRow: function (id) {
        console.log('test');
        let tr = document.getElementById('field_' + id);
        let container = tr.parentElement;
        generalFunctions.removeElement(tr);
        jQuery.post(
            params.urls.ajax,
            {
                action: params.actions.delete,
                id: id,
            },
            function (data) {
                generalFunctions.ajaxResponse(data);
            }
        );
        if (container.childElementCount === 0) {
            container.innerHTML = this.getEmptyRow();
        }
    },

    getEmptyRow: function () {
        return '' +
            '<tr id="no-items" class="no-items">' +
            '    <td class="colspanchange" colspan="8">No Items found</td>' +
            '</tr>'
            ;
    },
};
