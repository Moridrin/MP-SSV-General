// noinspection JSUnusedGlobalSymbols
let generalFunctions = {

    editor: {

        current: null,
        isOpen: false,

        getInputField: function (title, name, value, type, events, options) {
            // Default values for Events
            if (events === undefined) {
                events = {};
            }
            if (events.onkeydown === undefined) {
                alert('no onkeydown event specified');
            }

            // Default values for options
            if (options === undefined) {
                options = {};
            }
            if (options.width === undefined) {
                options.width = '100%';
            }

            // Events to String
            let eventsString = '';
            for (let [eventName, event] of Object.entries(events)) {
                eventsString += eventName + '="' + event + '" ';
            }

            // HTML
            let html =
                '<label id="' + name + '_container">' +
                '   <span class="title" style="width: 9em;">' + title + '</span>' +
                '   <span class="input-text-wrap" style="margin-left: 9em;">'
            ;
            if (type === 'textarea') {
                html += '<textarea name="' + name + '">' + value + '</textarea>';
            } else {
                html += '<input type="' + type + '" name="' + name + '" value="' + value + '" autocomplete="off" style="width: ' + options.width + ';" ' + eventsString + '>';
            }
            html +=
                '   </span>' +
                '</label>'
            ;
            return html;
        },

        getDiceInputField: function (title, name, value, events, options) {
            // Default values for Events
            if (events === undefined) {
                events = {};
            }
            if (events.onkeydown === undefined) {
                alert('no onkeydown event specified');
            }

            // Default values for options
            if (options === undefined) {
                options = {};
            }
            if (options.width === undefined) {
                options.width = '100%';
            }
            if (options.addition === undefined) {
                options.addition = false;
            }

            let eventsString = '';
            for (let [eventName, event] of Object.entries(events)) {
                eventsString += eventName + '="' + event + '" ';
            }
            let values = value.split('D');
            if (options.addition) {
                let tmp = values[1].split('+');
                values[1] = tmp[0];
                values[2] = tmp[1];
            }
            let html =
                '<label id="' + name + '_container">' +
                '   <span class="title" style="width: 9em;">' + title + '</span>' +
                '   <span class="input-text-wrap" style="margin-left: 9em;">' +
                '       <div style="display: table; table-layout: fixed; width: 100%;">' +
                '           <div style="display: table-cell;"><input type="number" name="' + name + 'C" value="' + values[0] + '" style="width: 100%;" autocomplete="off" ' + eventsString + '></div>' +
                '           <div style="display: table-cell; width: 25px; text-align: center;">D</div>' +
                '           <div style="display: table-cell;">'
            ;
            html += '<select name="' + name + 'D" style="width: 100%; vertical-align: top; margin-top: 0;" ' + eventsString + '>';
            let diceOptions = [2, 4, 6, 8, 10, 12, 20, 100];
            for (let i = 0; i < diceOptions.length; ++i) {
                if (parseInt(values[1]) === diceOptions[i]) {
                    html += '<option selected="selected" value="' + diceOptions[i] + '">' + diceOptions[i] + '</option>';
                } else {
                    html += '<option value="' + diceOptions[i] + '">' + diceOptions[i] + '</option>';
                }
            }
            html += '</select>' +
                '</div>' +
                '           <div style="display: table-cell; width: 25px; text-align: center;">+</div>'
            ;
            if (options.addition) {
                html += '<div style="display: table-cell;"><input type="number" name="' + name + 'A" value="' + values[2] + '" style="width: 100%;" autocomplete="off" ' + eventsString + '></div>'
            }
            html +=
                '       </div>' +
                '   </span>' +
                '</label>'
            ;
            return html;
        },

        getCheckboxInputField: function (title, name, value, description, events, options) {
            // Default values for Events
            if (events === undefined) {
                events = {};
            }
            if (events.onkeydown === undefined) {
                alert('no onkeydown event specified');
            }

            // Default values for options
            if (options === undefined) {
                options = {};
            }
            if (options.width === undefined) {
                options.width = '100%';
            }

            let checked = (value === true || value === 'true') ? 'checked="checked"' : '';
            let eventsString = '';
            for (let [eventName, event] of Object.entries(events)) {
                eventsString += eventName + '="' + event + '" ';
            }
            return '' +
                '<label>' +
                '   <span class="title" style="width: 9em;">' + title + '</span>' +
                '   <span class="input-text-wrap" style="margin-left: 9em;">' +
                '       <input type="checkbox" name="' + name + '" value="true" ' + checked + ' title="' + description + '" ' + eventsString + '>' +
                '   </span>' +
                '</label>'
                ;
        },

        getSelectInputField: function (title, name, possibleValues, values, events, options) {
            // Default values for Events
            if (events === undefined) {
                events = {};
            }
            if (events.onkeydown === undefined) {
                alert('no onkeydown event specified');
            }

            // Default values for options
            if (options === undefined) {
                options = {};
            }
            if (options.width === undefined) {
                options.width = '100%';
            }
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
                '   <span class="title" style="width: 9em;">' + title + '</span>' +
                '   <span class="input-text-wrap" style="margin-left: 9em;">'
            ;
            html += '<select name="' + name + '" style="width: 100%;" ' + eventsString + multiple + '>';
            if (possibleValues instanceof Object) {
                possibleValues = Object.values(possibleValues);
            }
            for (let i = 0; i < possibleValues.length; ++i) {
                if (values.indexOf(possibleValues[i]) !== -1) {
                    html += '<option selected="selected">' + possibleValues[i] + '</option>';
                } else {
                    html += '<option>' + possibleValues[i] + '</option>';
                }
            }
            html += '</select>';
            html +=
                '   </span>' +
                '</label>'
            ;
            return html;
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

    ajaxResponse: function (data, expectHtml) {
        let messageContainer = document.getElementById('messagesContainer');
        try {
            data = JSON.parse(data);
            if (data['errors']) {
                if (messageContainer !== null) {
                    messageContainer.innerHTML = data['errors']; // TODO Fix possible security risk
                } else {
                    alert(data['errors']);
                }
                return false;
            }
        } catch (e) {
            if (!expectHtml) {
                if (messageContainer !== null) {
                    messageContainer.innerHTML = '<div class="notice notice-warning"><p>The server gave an unexpected response. The last action might not have been performed correctly.</p></div>';
                } else {
                    alert('The server gave an unexpected response. The last action might not have been performed correctly.');
                }
            }
            return false;
        }
        messageContainer.innerHTML = '';
        return true;
    },
};
