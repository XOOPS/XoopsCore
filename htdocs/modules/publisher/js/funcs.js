function selectAll(formObj, fieldname, isInverse)
{
    if (fieldname.length == 0) {
        for (var i=0;i < formObj.length;i++) {
            fldObj = formObj.elements[i];
            if (fldObj.type == 'checkbox') {
                fldObj.checked = isInverse;
            }
        }
    } else {
        for (var i=0; i < formObj.length;i++) {
            fldObj = formObj.elements[i];
            if (fldObj.type == 'checkbox') {
                if (fldObj.name.indexOf(fieldname) > -1) {
                    fldObj.checked = isInverse;
                }
            }
        }
    }
}


function publisherPageWrap(id, page) {
	var revisedMessage;
	var textareaDom = xoopsGetElementById(id);
	xoopsInsertText(textareaDom, page);
	textareaDom.focus();
	return;
}

function addSelectedItemsToParent() {
	self.opener.addToParentList(window.document.forms[0].destList);
	window.close();
}

// Fill the selcted item list with the items already present in parent.
function fillInitialDestList() {
	var destList = window.document.forms[0].destList;
	var srcList = self.opener.window.document.forms[0].elements['moderators[]'];
	for (var count = destList.options.length - 1; count >= 0; count--) {
		destList.options[count] = null;
	}
	for(var i = 0; i < srcList.options.length; i++) {
		if (srcList.options[i] != null)
		destList.options[i] = new Option(srcList.options[i].text);
   }
}

// Add the selected items from the source to destination list
function addSrcToDestList() {
	destList = window.document.forms[0].destList;
	srcList = window.document.forms[0].srcList;
	var len = destList.length;
	for(var i = 0; i < srcList.length; i++) {
		if ((srcList.options[i] != null) && (srcList.options[i].selected)) {
			//Check if this value already exist in the destList or not
			//if not then add it otherwise do not add it.
			var found = false;
			for(var count = 0; count < len; count++) {
				if (destList.options[count] != null) {
					if (srcList.options[i].text == destList.options[count].text) {
						found = true;
						break;
					}
				}
			}
			if (found != true) {
				destList.options[len] = new Option(srcList.options[i].text);
				len++;
			}
		}
	}
}

// Deletes from the destination list.
function deleteFromDestList() {
	var destList  = window.document.forms[0].destList;
	var len = destList.options.length;
	for(var i = (len-1); i >= 0; i--) {
		if ((destList.options[i] != null) && (destList.options[i].selected == true)) {
			destList.options[i] = null;
		}
	}
}


function small_window(myurl, w, h) {
	// La ventana se llama "Add_from_Src_to_Dest"
	var newWindow;
	LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;
	TopPosition = (screen.height) ? (screen.height-h)/2 : 0;
	newWindow = window.open(myurl, "Add_from_Src_to_Dest", 'left='+LeftPosition+',top='+TopPosition+',width='+w+', height='+h+',scrollBars=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no');
}

// Adds the list of selected items selected in the child
// window to its list. It is called by child window to do so.
function addToParentList(sourceList) {
	destinationList = window.document.forms[0].elements['moderators[]'];
	for(var count = destinationList.options.length - 1; count >= 0; count--) {
		destinationList.options[count] = null;
	}
	for(var i = 0; i < sourceList.options.length; i++) {
		if (sourceList.options[i] != null)
			destinationList.options[i] = new Option(sourceList.options[i].text, sourceList.options[i].value );
	}
}

// Marks all the items as selected for the submit button.
function selectList(sourceList) {
	sourceList = window.document.forms[0].elements['moderators[]'];
	for(var i = 0; i < sourceList.options.length; i++) {
		if (sourceList.options[i] != null)
			sourceList.options[i].selected = true;
	}
	return true;
}

// Deletes the selected items of supplied list.
function deleteSelectedItemsFromList(sourceList) {
	var maxCnt = sourceList.options.length;
	for(var i = maxCnt - 1; i >= 0; i--) {
		if ((sourceList.options[i] != null) && (sourceList.options[i].selected == true)) {
			sourceList.options[i] = null;
		}
	}
}

function goto_URL(object)
{
	window.location.href = object.options[object.selectedIndex].value;
}

function toggle(id)
{
	if (document.getElementById) { obj = document.getElementById(id); }
	if (document.all) { obj = document.all[id]; }
	if (document.layers) { obj = document.layers[id]; }
	if (obj) {
		if (obj.style.display == "none") {
			obj.style.display = "";
		} else {
			obj.style.display = "none";
		}
	}

	var expDays = 365;
	var exp = new Date();
	exp.setTime(exp.getTime() + (expDays*24*60*60*1000));

	setCookie(window.location.pathname+"_publisher_collaps_" + obj.id , obj.style.display, exp);

	return false;
}

var iconClose = new Image();
iconClose.src = '../images/links/close12.gif';
var iconOpen = new Image();
iconOpen.src = '../images/links/open12.gif';

function toggleIcon ( iconName )
{
	if ( document.images[iconName].src == window.iconOpen.src ) {
		document.images[iconName].src = window.iconClose.src;
	} else if ( document.images[iconName].src == window.iconClose.src ) {
		document.images[iconName].src = window.iconOpen.src;
	}
	return;
}