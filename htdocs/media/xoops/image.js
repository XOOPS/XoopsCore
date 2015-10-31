function resizeImage (img, maxWidth)
{
    if (img.width > maxWidth && maxWidth > 0) img.width = maxWidth;
}

function loadImage(img)
{
    tempImage = new Image();
    img = encodeURI(img);
    tempImage.src = (img);
    imageIsLoaded(img);
}

function imageIsLoaded(img)
{
    if ( (tempImage.width != 0) && (tempImage.height != 0) ) {
        showImage(img);
    } else {
        t = setTimeout("imageIsLoaded('\"+img+\"')", 20);
    }
}

function showImage(img)
{
    imageWidth = tempImage.width;
    imageHeight = tempImage.height;
    imagePopup = window.open ('','', "width=" + imageWidth + ",height=" + imageHeight);
    imagePopup.document.write ("<html><body leftmargin=0 topmargin=0>");
    imagePopup.document.write ("<a href='javascript:this.close()'><img border=0 src=");
    imagePopup.document.write (img);
    imagePopup.document.write ("></a></body></html>");
    imagePopup.document.close();
    return false;
}
