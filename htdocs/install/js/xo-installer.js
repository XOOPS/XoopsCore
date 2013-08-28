function showHideHelp( butt )
{
    butt.className = ( butt.className == 'on' ) ? 'off': 'on';
    document.body.className = ( butt.className == 'on' ) ? 'show-help': '';
}

function xoopsExternalLinks()
{
    if (!document.getElementsByTagName) return;
    var anchors = document.getElementsByTagName("a");
    for (var i=0; i<anchors.length; i++) {
        var anchor = anchors[i];
        if (anchor.getAttribute("href") ) {
            // Check rel value with extra rels, like "external noflow". No test for performance yet
            $pattern = new RegExp("external", "i");
            if ($pattern.test(anchor.getAttribute("rel"))) {
                anchor.target = "_blank";
            }
        }
    }
}

function xoopsGetElementById(id)
{
    return $(id);
}

function selectModule( id , button)
{
    element = xoopsGetElementById(id);
    if ( button.value == 1) {
        element.style.background = '#E6EFC2';
    } else {
        element.style.background = 'transparent';
    }
}

function showThemeSelected( element )
{
    if (!document.getElementsByTagName) return;
    var divs = document.getElementsByTagName("div");
    for (var i=0; i<divs.length; i++) {
        var div = divs[i];
        divname = div.getAttribute("id");
        if (div.getAttribute("rel") ) {
            $(divname).hide();
            if ( divname == element.value ) {
                $(divname).show();
            }
        }
    }
}

function passwordStrength(password)
{
    if (password.length == 0) {
        var score   = 0;
    } else {
        var score   = 1;

        //if password bigger than 6 give 1 point
        if (password.length > 6) score++;

        //if password has both lower and uppercase characters give 1 point
        if ( ( password.match(/[a-z]/) ) && ( password.match(/[A-Z]/) ) ) score++;

        //if password has at least one number give 1 point
        if (password.match(/\d+/)) score++;

        //if password has at least one special caracther give 1 point
        if ( password.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/) )        score++;

        //if password bigger than 12 give another 1 point
        if (password.length > 12) score++;
    }

    document.getElementById("passwordDescription").innerHTML = desc[score];
    document.getElementById("passwordStrength").className = "strength" + score;
}

function suggestPassword( passwordlength )
{
    var pwchars = "abcdefhjmnpqrstuvwxyz23456789ABCDEFGHJKLMNPQRSTUVWYXZ.,:";
    var pwchars = "abcdefhjmnpqrstuvwxyz1234567890,?;.:!$=+@_-&|#ABCDEFGHJKLMNPQRSTUVWYXZ";
    var passwd = document.getElementById('generated_pw');
    passwd.value = '';

    for ( i = 0; i < passwordlength; i++ ) {
        passwd.value += pwchars.charAt( Math.floor( Math.random() * pwchars.length ) )
    }
    return passwd.value;
}


/**
 * Copy the generated password (or anything in the field) to the form
 *
 * @param   string   the form name
 *
 * @return  boolean  always true
 */
function suggestPasswordCopy(id)
{
    generated_pw = xoopsGetElementById('generated_pw');

    adminpass = xoopsGetElementById('adminpass')
    adminpass.value = generated_pw.value;

    adminpass2 = xoopsGetElementById('adminpass2')
    adminpass2.value = generated_pw.value;

    passwordStrength(adminpass.value)
    return true;
}

window.onload = xoopsExternalLinks;