function inlineEdit(fieldId) {
    let tr = document.getElementById(fieldId + '_tr');
    let title = document.getElementById(fieldId + '_field_title_td').children[0].innerText;
    let name = document.getElementById(fieldId + '_name_td').innerText;
    let inputType = document.getElementById(fieldId + '_inputType_td').innerText;
    let value = '';
    if (inputType === 'select' || inputType === 'hidden') {
        value = document.getElementById(fieldId + '_value_td').innerText;
    }
    tr.setAttribute('class', 'inline-edit-row inline-edit-row-base-field quick-edit-row quick-edit-row-base-field inline-edit-base-field inline-editor');
    updateTrForInlineEdit(tr, fieldId, title, name, inputType, value, false);
}

function cancelInlineEdit(fieldId) {
    let title = document.getElementById(fieldId + '_title').dataset.oldValue;
    let name = document.getElementById(fieldId + '_name').dataset.oldValue;
    let inputType = document.getElementById(fieldId + '_inputType').dataset.oldValue;
    let value = '';
    if (inputType === 'select' || inputType === 'hidden') {
        value = document.getElementById(fieldId + '_value').dataset.oldValue;
    }
    updateTrForDisplay(fieldId, title, name,  inputType, value);
}

function saveInlineEdit(fieldId) {
    let title = document.getElementById(fieldId + '_title').value;
    let name = document.getElementById(fieldId + '_name').value;
    let inputType = document.getElementById(fieldId + '_inputType').value;
    let value = '';
    if (inputType === 'select' || inputType === 'hidden') {
        value = document.getElementById(fieldId + '_value').value;
    }
    updateTrForDisplay(fieldId, title, name,  inputType, value);
}

function updateTrForDisplay(fieldId, title, name, inputType, value) {
    let tr = document.getElementById(fieldId + '_tr');
    tr.innerHTML =
        '<th id="' + fieldId + '_id_td" class="check-column">' +
        '    <input type="checkbox" id="' + fieldId + '_id" name="selected_field_ids[]" value="' + fieldId + '">' +
        '</th>' +
        '<td id="' + fieldId + '_field_title_td">' +
        '    <strong>' + title + '</strong>' +
        '    <div class="row-actions">' +
        '        <span class="inline hide-if-no-js"><a href="javascript:void(0)" onclick="inlineEdit(\'' + fieldId + '\')" class="editinline" aria-label="Quick edit “Hello world!” inline">Quick Edit</a> | </span>' +
        '        <span class="trash"><a href="javascript:void(0)" onclick="deleteRow(\'' + fieldId + '\')" class="submitdelete" aria-label="Move “Hello world!” to the Trash">Trash</a></span>' +
        '    </div>' +
        '</td>' +
        '<td id="' + fieldId + '_name_td">' + name + '</td>' +
        '<td id="' + fieldId + '_inputType_td">' + inputType + '</td>' +
        '<td id="' + fieldId + '_value_td">' + value + '</td>'
    ;
    tr.setAttribute('class', 'inactive');
}

function updateTrForInlineEdit(tr, fieldId, title, name, inputType, value, isNew) {
    let addUpdateLabel;
    if (isNew) {
        addUpdateLabel = 'Add';
    } else {
        addUpdateLabel = 'Update';
    }
    tr.innerHTML =
        '<td colspan="5" class="colspanchange">' +
        '   <fieldset class="inline-edit-col-left" style="width: 50%;">' +
        '      <legend class="inline-edit-legend">Quick Edit</legend>' +
        '      <div class="inline-edit-col">' +
        '          <label>' +
        '              <span class="title">Title</span>' +
        '              <span class="input-text-wrap">' +
        '                  <input type="text" id="' + fieldId + '_title" name="custom_field_' + fieldId + '_title" value="' + title + '" data-old-value="' + title + '">' +
        '              </span>' +
        '          </label>' +
        '          <label>' +
        '              <span class="title">Name</span>' +
        '              <span class="input-text-wrap">' +
        '                  <input type="text" id="' + fieldId + '_name" name="custom_field_' + fieldId + '_name" value="' + name + '" data-old-value="' + name + '">' +
        '              </span>' +
        '          </label>' +
        '          <label>' +
        '              <span class="title">InputType</span>' +
        '              <span class="input-text-wrap">' +
        '                  <input type="text" id="' + fieldId + '_inputType" name="custom_field_' + fieldId + '_inputType" list="inputType" value="' + inputType + '" oninput="inputTypeChanged(' + fieldId + ')" data-old-value="' + inputType + '">' +
        '              </span>' +
        '          </label>' +
        '      </div>' +
        '   </fieldset>' +
        '   <fieldset id="' + fieldId + '_value_container" class="inline-edit-col-center" style="width: 50%;">' +
        '   </fieldset>' +
        '   <div class="submit inline-edit-save">' +
        '       <button type="button" class="button cancel alignleft" onclick="cancelInlineEdit(' + fieldId + ')">Cancel</button>' +
        '       <input type="hidden" id="_inline_edit" name="_inline_edit" value="' + fieldId + '">' +
        '       <button type="button" class="button button-primary save alignright" onclick="saveInlineEdit(' + fieldId + ')">' + addUpdateLabel +'</button>' +
        '       <br class="clear">' +
        '   </div>' +
        '</td>'
    ;
    if (inputType === 'select' || inputType === 'hidden') {
        addValueContainerForInlineEdit(fieldId, value);
    }
}

function addValueContainerForInlineEdit(fieldId, value) {
    document.getElementById(fieldId + '_value_container').innerHTML =
        '<legend class="inline-edit-legend">Value / Options</legend>' +
        '<div class="inline-edit-col">' +
        '   <label>' +
        '       <span class="title">Value</span>' +
        '       <span class="input-text-wrap">' +
        '            <input type="text" id="' + fieldId + '_value" name="custom_field_' + fieldId + '_value" value="' + value + '" data-old-value="' + value + '">' +
        '       </span>' +
        '   </label>' +
        '</div>'
    ;
}

function removeValueContainerForInlineEdit(fieldId) {
    document.getElementById(fieldId + '_value_container').innerHTML = '';
}

//noinspection JSUnresolvedVariable
// let roles = JSON.parse(settings.roles);
// let scripts = document.getElementsByTagName('script');
// let pluginBaseURL = scripts[scripts.length - 1].src.split('/').slice(0, -3).join('/');
// let fieldIds = [];

// function getBR() {
//     let br = document.createElement('div');
//     br.innerHTML = '<br/>';
//     return br.childNodes[0];
// }
//
// function getEmpty(fieldId, columnClass) {
//     let td = document.createElement('td');
//     td.setAttribute('id', fieldId + '_empty_td');
//     if (columnClass) {
//         td.classList.add(columnClass);
//     }
//     return td;
// }
//
// function getFieldCheckbox(fieldId) {
//     let fieldIdElement = document.createElement('input');
//     fieldIdElement.setAttribute('type', 'hidden');
//     fieldIdElement.setAttribute('id', fieldId + '_id');
//     fieldIdElement.setAttribute('name', 'custom_field_' + fieldId + '_id');
//     fieldIdElement.setAttribute('value', fieldId);
//     let fieldSelectElement = document.createElement('input');
//     fieldSelectElement.setAttribute('type', 'checkbox');
//     fieldSelectElement.setAttribute('id', fieldId + '_id');
//     fieldSelectElement.setAttribute('name', 'custom_field_' + fieldId + '_id');
//     fieldSelectElement.setAttribute('value', fieldId);
//     let fieldIdTD = document.createElement('th');
//     fieldIdTD.setAttribute('id', fieldId + '_id_td');
//     fieldIdTD.setAttribute('class', 'check-column');
//     fieldIdTD.appendChild(fieldIdElement);
//     fieldIdTD.appendChild(fieldSelectElement);
//     return fieldIdTD;
// }
//
// function getFieldTitle(fieldId, value) {
//     let fieldTitle = document.createElement('input');
//     fieldTitle.setAttribute('id', fieldId + '_title');
//     fieldTitle.setAttribute('name', 'custom_field_' + fieldId + '_title');
//     fieldTitle.setAttribute('style', 'width: 100%;');
//     if (value) {
//         fieldTitle.setAttribute('value', value);
//     }
//     let fieldTitleTD = document.createElement('td');
//     fieldTitleTD.setAttribute('id', fieldId + '_field_title_td');
//     fieldTitleTD.appendChild(fieldTitle);
//     return fieldTitleTD;
// }
//
// function getInputType(fieldId, value) {
//     let inputType = document.createElement('input');
//     inputType.setAttribute('id', fieldId + '_inputType');
//     inputType.setAttribute('name', 'custom_field_' + fieldId + '_inputType');
//     inputType.setAttribute('style', 'width: 100%;');
//     inputType.setAttribute('list', 'inputType');
//     if (value) {
//         inputType.setAttribute('value', value);
//     }
//     inputType.addEventListener('input', function () {
//         inputTypeChanged(fieldId);
//     });
//     let inputTypeTD = document.createElement('td');
//     inputTypeTD.setAttribute('id', fieldId + '_inputType_td');
//     inputTypeTD.appendChild(inputType);
//     return inputTypeTD;
// }
//
// function getName(fieldId, value) {
//     let name = document.createElement('input');
//     name.setAttribute('id', fieldId + '_name');
//     name.setAttribute('name', 'custom_field_' + fieldId + '_name');
//     name.setAttribute('style', 'width: 100%;');
//     name.setAttribute('pattern', '[a-z0-9_]+');
//     if (value) {
//         name.setAttribute('value', value);
//     }
//     name.setAttribute('required', 'required');
//     let nameTD = document.createElement('td');
//     nameTD.setAttribute('id', fieldId + '_name_td');
//     nameTD.appendChild(name);
//     return nameTD;
// }
//
// function getRoleCheckbox(fieldId, value) {
//     let inputType = createSelect(fieldId, '_name', roles, value);
//     inputType.setAttribute('style', 'width: 100%;');
//     let inputTypeTD = document.createElement('td');
//     inputTypeTD.setAttribute('id', fieldId + '_name_td');
//     inputTypeTD.appendChild(inputType);
//     return inputTypeTD;
// }
//
// function getRoleSelect(fieldId, value) {
//     let inputType = createMultiSelect(fieldId, '_value', roles, value);
//     inputType.setAttribute('style', 'width: 100%;');
//     let inputTypeTD = document.createElement('td');
//     inputTypeTD.setAttribute('id', fieldId + '_value_td');
//     inputTypeTD.appendChild(inputType);
//     return inputTypeTD;
// }
//
// function getOptions(fieldId, value) {
//     let options = document.createElement('input');
//     options.setAttribute('id', fieldId + '_value');
//     options.setAttribute('name', 'custom_field_' + fieldId + '_value');
//     options.setAttribute('style', 'width: 100%;');
//     if (value) {
//         options.setAttribute('value', value);
//     }
//     options.setAttribute('required', 'required');
//     options.setAttribute('placeholder', 'Separate with ","');
//     let optionsTD = document.createElement('td');
//     optionsTD.setAttribute('class', 'value_td');
//     optionsTD.setAttribute('id', fieldId + '_value_td');
//     optionsTD.appendChild(options);
//     return optionsTD;
// }
//
// function getValue(fieldId, value) {
//     let valueField = document.createElement('input');
//     valueField.setAttribute('id', fieldId + '_value');
//     valueField.setAttribute('name', 'custom_field_' + fieldId + '_value');
//     valueField.setAttribute('style', 'width: 100%;');
//     if (value) {
//         valueField.setAttribute('value', value);
//     }
//     let valueTD = document.createElement('td');
//     valueTD.setAttribute('id', fieldId + '_value_td');
//     valueTD.appendChild(valueField);
//     return valueTD;
// }
//
// function createSelect(fieldId, fieldNameExtension, options, selected) {
//     let select = document.createElement('select');
//     select.setAttribute('id', fieldId + fieldNameExtension);
//     select.setAttribute('name', 'custom_field_' + fieldId + fieldNameExtension);
//
//     for (let i = 0; i < options.length; i++) {
//         let option = document.createElement('option');
//         option.setAttribute('value', options[i].toLowerCase());
//         if (options[i].toLowerCase() === selected) {
//             option.setAttribute('selected', 'selected');
//         }
//         option.innerHTML = options[i];
//         select.appendChild(option);
//     }
//
//     return select;
// }
//
// function createMultiSelect(fieldId, fieldNameExtension, options, selected) {
//     if (selected === null) {
//         selected = [];
//     }
//     let select = document.createElement('select');
//     select.setAttribute('id', fieldId + fieldNameExtension);
//     select.setAttribute('name', 'custom_field_' + fieldId + fieldNameExtension + '[]');
//     select.setAttribute('multiple', 'multiple');
//
//     for (let i = 0; i < options.length; i++) {
//         let option = document.createElement('option');
//         option.setAttribute('value', options[i].toLowerCase());
//         if (jQuery.inArray(options[i].toLowerCase(), selected) !== -1) {
//             option.setAttribute('selected', 'selected');
//         }
//         option.innerHTML = options[i];
//         select.appendChild(option);
//     }
//
//     return select;
// }