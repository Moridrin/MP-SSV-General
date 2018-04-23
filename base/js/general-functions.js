/**
 * Created by moridrin on 5-6-17.
 */
let generalFunctions = {
    getCurrentDate: function () {
        var today = new Date();
        var yyyy = today.getFullYear();
        var mm = today.getMonth() + 1; //January is 0!
        var dd = today.getDate();
        if (dd < 10) {
            dd = '0' + dd
        }
        if (mm < 10) {
            mm = '0' + mm
        }
        return yyyy + '-' + mm + '-' + dd;
    },

    getCurrentTime: function () {
        var today = new Date();
        var hh = today.getHours();
        var mm = today.getMinutes();
        if (hh < 10) {
            hh = '0' + hh
        }
        if (mm < 10) {
            mm = '0' + mm
        }
        return hh + ':' + mm;
    },

    removeElements: function (fields) {
        if (fields !== null) {
            while (fields.length > 0) {
                generalFunctions.removeElement(fields[0]);
            }
        }
    },

    removeElement: function (field) {
        if (Array.isArray(field)) {
            generalFunctions.removeElements(field);
        }
        if (field !== null) {
            field.parentElement.removeChild(field);
        }
    },

    showNotice: function (message) {
        document.getElementById('messagesContainer').innerHTML = message;
    }
};
