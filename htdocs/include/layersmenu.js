// $Id$
// Deprecated, for legacy GUI only
// PHP Layers Menu 1.0.7 (c) 2001, 2002 Marco Pratesi <pratesi@telug.it>
// several "var"s inserted by CPKS to silence errors

var DOM = (document.getElementById) ? 1 : 0;
var NS4 = (document.layers) ? 1 : 0;
var IE4 = (document.all) ? 1 : 0;
var loaded = 0;	// to avoid stupid errors of Microsoft browsers
var Konqueror = (navigator.userAgent.indexOf("Konqueror") > -1) ? 1 : 0;
// We need to explicitly detect Konqueror
// because Konqueror 3 sets IE4 = 1 ... AAAAAAAAAARGHHH!!!
var Opera5 = (navigator.userAgent.indexOf("Opera 5") > -1 || navigator.userAgent.indexOf("Opera/5") > -1 || navigator.userAgent.indexOf("Opera 6") > -1 || navigator.userAgent.indexOf("Opera/6") > -1) ? 1 : 0;

// it works with NS4, Mozilla, NS6, Opera 5 and 6, IE
var currentY = -1;
function grabMouse(e) {
	if ((DOM && !IE4) || Opera5) {
		currentY = e.clientY;
	} else if (NS4) {
		currentY = e.pageY;
	} else {
		currentY = event.y;
	}
	if (DOM && !IE4 && !Opera5 && !Konqueror) {
		currentY += window.pageYOffset;
	} else if (IE4 && DOM && !Opera5 && !Konqueror) {
		currentY += document.body.scrollTop;
	}
}

// Replace deprecated captureEvents with addEventListener 
// by phppp since XOOPS 2.0.17
if (document.addEventListener){
  document.addEventListener('mousemove', grabMouse, false); 
} else if (document.attachEvent){
  document.attachEvent('onmousemove', grabMouse);
}

function popUp(menuName,on) {
	if (loaded) {	// to avoid stupid errors of Microsoft browsers
		if (on) {
//			moveLayers();
			if (DOM) {
				document.getElementById(menuName).style.visibility = "visible";
				document.getElementById(menuName).style.zIndex = 1000;
			} else if (NS4) {
				document.layers[menuName].visibility = "show";
				document.layers[menuName].zIndex = 1000;
			} else {
				document.all[menuName].style.visibility = "visible";
				document.all[menuName].style.zIndex = 1000;
			}
		} else {
			if (DOM) {
				document.getElementById(menuName).style.visibility = "hidden";
			} else if (NS4) {
				document.layers[menuName].visibility = "hide";
			} else {
				document.all[menuName].style.visibility = "hidden";
			}
		}
	}
}

function setleft(layer,x) {
	if (DOM) {
		document.getElementById(layer).style.left = x + 'px';
	} else if (NS4) {
		document.layers[layer].left = x;
	} else {
		document.all[layer].style.pixelLeft = x;
	}
}

function settop(layer,y) {
	if (DOM) {
		document.getElementById(layer).style.top = y + 'px';
	} else if (NS4) {
		document.layers[layer].top = y;
	} else {
		document.all[layer].style.pixelTop = y;
	}
}

function setwidth(layer,w) {
	if (DOM) {
		document.getElementById(layer).style.width = w;
		document.getElementById(layer).style.width = w + 'px';
	} else if (NS4) {
//		document.layers[layer].width = w;
	} else {
		document.all[layer].style.pixelWidth = w;
	}
}

function moveLayerY(menuName, ordinata, e) {
	if (loaded) {	
	// to avoid stupid errors of Microsoft browsers
	//alert (ordinata);
	// Konqueror: ordinata = -1 according to the initialization currentY = -1
	// Opera: isNaN(ordinata), currentY is NaN, it seems that Opera ignores the initialization currentY = -1
		if (ordinata != -1 && !isNaN(ordinata)) {	// The browser has detected the mouse position
			if (DOM) {
				// attenzione a "px" !!!
				if (e && e.clientY) { // just use the pos of the mouseOver event if we have it
					document.getElementById(menuName).style.top = e.clientY + 'px';
				} else {
					appoggio = parseInt(document.getElementById(menuName).style.top);
					if (isNaN(appoggio)) appoggio = 0;
					if (Math.abs(appoggio + ordinata_margin - ordinata) > thresholdY)
						document.getElementById(menuName).style.top = (ordinata - ordinata_margin) + 'px';
				}

			} else if (NS4) {
					if (Math.abs(document.layers[menuName].top + ordinata_margin - ordinata) > thresholdY)
						document.layers[menuName].top = ordinata - ordinata_margin;
			} else {
				if (Math.abs(document.all[menuName].style.pixelTop + ordinata_margin - ordinata) > thresholdY)
					document.all[menuName].style.pixelTop = ordinata - ordinata_margin;
			}
		}
	}
}
