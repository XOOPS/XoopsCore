$(document).ready(function() {
    var MenuDom = xoopsGetElementById('image_item');
    if (MenuDom != null) {
        for (var i = 0; i < MenuDom.options.length; i++) {
            MenuDom.options[i].selected = true;
        }
    }

});

function publisher_appendSelectOption(fromMenuId, toMenuId) {
    var fromMenuDom = xoopsGetElementById(fromMenuId);
    var toMenuDom = xoopsGetElementById(toMenuId);
    var newOption = new Option(fromMenuDom.options[fromMenuDom.selectedIndex].text, fromMenuDom.options[fromMenuDom.selectedIndex].value);
    newOption.selected = true;
    toMenuDom.options[toMenuDom.options.length] = newOption;
    fromMenuDom.remove(fromMenuDom.selectedIndex);
}

function publisher_updateSelectOption(fromMenuId, toMenuId) {
    var fromMenuDom = xoopsGetElementById(fromMenuId);
    var toMenuDom = xoopsGetElementById(toMenuId);
    for (var i = 0; i < toMenuDom.options.length; i++) {
        toMenuDom.remove(toMenuDom.options[i]);
    }
    var index = 0;
    for (var i = 0; i < fromMenuDom.options.length; i++) {
        if (fromMenuDom.options[i].selected) {
            var newOption = new Option(fromMenuDom.options[i].text, fromMenuDom.options[i].value);
            toMenuDom.options[index] = newOption;
            index++;
        }
    }
}