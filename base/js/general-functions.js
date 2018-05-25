// noinspection JSUnusedGlobalSymbols
/**
 * Created by moridrin on 5-6-17.
 */
let generalFunctions = {

    editor: {

        current: null,
        isOpen: false,

        getInputField: function (title, name, value, type, events, options) {
            // Default values for options
            if (options === undefined) {
                options = {};
            }
            if (options.width === undefined) {
                options.width = '100%';
            }

            // Default values for Events
            if (events === undefined) {
                events = {};
            }
            if (events.onkeydown === undefined) {
                events.onkeydown = 'generalFunctions.editor.onKeyDown()';
            }

            // Events to String
            let eventsString = '';
            for (let [eventName, event] of Object.entries(events)) {
                eventsString += eventName + '="' + event + '" ';
            }

            // HTML
            let html =
                '<label id="' + name + '_container">' +
                '   <span class="title">' + title + '</span>' +
                '   <span class="input-text-wrap">'
            ;
            if (type === 'textarea') {
                html += '<textarea name="' + name + '">' + value + '</textarea>';
            } else if (type === 'dice') {
                html += '<input type="number" name="' + name + '" value="' + value + '" autocomplete="off" style="width: 50%;" ' + eventsString + '>';
                html += '' +
                    '<select name="' + name + '" style="width: 100%;" ' + eventsString + multiple + '>' +
                    '   <option value="4">4</option>' +
                    '   <option value="6">6</option>' +
                    '   <option value="8">8</option>' +
                    '   <option value="10">10</option>' +
                    '   <option value="12">12</option>' +
                    '   <option value="20">20</option>' +
                    '   <option value="100">100</option>' +
                    '</select>';
            } else {
                html += '<input type="' + type + '" name="' + name + '" value="' + value + '" autocomplete="off" style="width: ' + options.width + ';" ' + eventsString + '>';
            }
            html +=
                '   </span>' +
                '</label>'
            ;
            return html;
        },

        getDiceInputField: function (title, name, value) {
            let values = value.split('D');
            let html =
                '<label id="' + name + '_container">' +
                '   <span class="title">' + title + '</span>' +
                '   <span class="input-text-wrap">' +
                '       <div style="display: table; table-layout: fixed; width: 100%;">' +
                '           <div style="display: table-cell;"><input type="number" name="' + name + 'A" value="' + values[0] + '" style="width: 100%;" autocomplete="off"></div>' +
                '           <div style="display: table-cell; width: 25px; text-align: center;">D</div>' +
                '           <div style="display: table-cell;">'
            ;
            html += '<select name="' + name + 'D" style="width: 100%;">';
            let options = [2, 4, 6, 8, 10, 12, 20, 100];
            for (let i = 0; i < options.length; ++i) {
                if (parseInt(values[1]) === options[i]) {
                    html += '<option selected="selected" value="' + options[i] + '">' + options[i] + '</option>';
                } else {
                    html += '<option value="' + options[i] + '">' + options[i] + '</option>';
                }
            }
            html += '</select>';
            html +=
                '           </div>' +
                '       </div>' +
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
                    event.preventDefault();
                    creatureManager.saveEdit();
                    return false;
                } else {
                    $nameInput.setCustomValidity('');
                    $nameInput.reportValidity();
                }
            }
        },

        switchNamePlayerToSelect: function () {
            let container = document.getElementById('name_container');
            let value = container.querySelector('[name="name"]').value;
            let newPlayer = document.createElement('div');
            newPlayer.innerHTML = this.getSelectInputField('Name', 'name', params.roles, value, []);
            container.parentElement.replaceChild(newPlayer, container);
        },

        switchNamePlayerToInput: function () {
            let container = document.getElementById('name_container');
            let value = container.querySelector('[name="name"]').value;
            let newPlayer = document.createElement('div');
            newPlayer.innerHTML = this.getInputField('Name', 'name', value, 'text', {'onkeydown': 'generalFunctions.editor.onKeyDown()'});
            container.parentElement.replaceChild(newPlayer, container);
        },
    },

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
