/**
 * Created by moridrin on 5-6-17.
 */

function getSelectValues(select) {
    var result = [];
    var options = select && select.options;
    var opt;

    for (var i = 0, iLen = options.length; i < iLen; i++) {
        opt = options[i];

        if (opt.selected) {
            result.push(opt.value || opt.text);
        }
    }
    return result;
}

//noinspection JSUnusedGlobalSymbols
function getDraggable(fieldID) {
    var draggableIcon = document.createElement("img");
    draggableIcon.setAttribute("src", pluginBaseURL + '/general/images/icon-menu.svg');
    draggableIcon.setAttribute("style", "padding-right: 15px; margin: 10px 0;");
    var draggableIconTD = document.createElement("td");
    draggableIconTD.setAttribute("style", "padding: 0;");
    draggableIconTD.setAttribute("id", fieldID + "_draggable_td");
    draggableIconTD.setAttribute("style", "vertical-align: middle; cursor: move;");
    draggableIconTD.appendChild(draggableIcon);
    return draggableIconTD;
}
