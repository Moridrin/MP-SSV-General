// noinspection JSUnusedGlobalSymbols
/**
 * Created by moridrin on 5-6-17.
 */
let generalFunctions = {
    getCurrentDate: function () {
        let today = new Date();
        let yyyy = today.getFullYear();
        let mm = today.getMonth() + 1; //January is 0!
        let dd = today.getDate();
        if (dd < 10) {
            dd = '0' + dd
        }
        if (mm < 10) {
            mm = '0' + mm
        }
        return yyyy + '-' + mm + '-' + dd;
    },

    getCurrentTime: function () {
        let today = new Date();
        let hh = today.getHours();
        let mm = today.getMinutes();
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

    ajaxResponse: function (data) {
        let messageContainer = document.getElementById('messagesContainer');
        try {
            data = JSON.parse(data);
            if (data['errors']) {
                messageContainer.innerHTML = data['errors']; // TODO Fix possible security risk
                return false;
            }
        } catch (e) {
            messageContainer.innerHTML = '<div class="notice notice-warning"><p>The server gave an unexpected response. The last action might not have been performed correctly.</p></div>';
        }
        return true;
    },
};
