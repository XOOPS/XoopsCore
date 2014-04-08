/*
 # -- BEGIN LICENSE BLOCK ----------------------------------
 #
 # This file is part of tinyMCE.
 # YouTube for tinyMCE
 # Copyright (C) 2011 - 2013  Gerits Aurelien <aurelien[at]magix-dev[dot]be> - <contact[at]aurelien-gerits[dot]be>
 # This program is free software: you can redistribute it and/or modify
 # it under the terms of the GNU General Public License as published by
 # the Free Software Foundation, either version 3 of the License, or
 # (at your option) any later version.
 #
 # This program is distributed in the hope that it will be useful,
 # but WITHOUT ANY WARRANTY; without even the implied warranty of
 # MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 # GNU General Public License for more details.

 # You should have received a copy of the GNU General Public License
 # along with this program.  If not, see <http://www.gnu.org/licenses/>.
 #
 # -- END LICENSE BLOCK -----------------------------------
 */
// Namespace Youtube
var youTube = (function($, window, document, undefined){
    /*
    * Return youtubeid
    * @param url
    */
    function youtubeId(url) {
        var match = url.match((/^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/));
        return match && match[2].length === 11 ? match[2] : false;
    }

    /**
     * Change protocol URL for ssl
     * @param url
     * @returns {*}
     */
    function protocol(url){
        // Test si url valide
        if(url.match(/((http|https):\/\/[^< ]+)/g)){
            if(url.match(new RegExp("http://","g"))){
                // Replace http:// for https://
                ssl_url = url.replace(/^http:\/\//i, 'https://');
            }else{
                ssl_url = url;
            }
            return ssl_url;
        }else{
            console.log(url+' is not URL');
        }
    }

    /**
     * Return YouTube convertUrl URL
     * @param url
     * @param mode
     * @returns {*}
     */
    function convertUrl(url,mode){
        var tabURL = new Array('https://www.youtube.com/v/','http://www.youtube.com/embed/');
        var newUrl;
        switch(mode){
            case 'embed':
                newUrl = tabURL[0];
            break;
            case 'iframe':
                newUrl = tabURL[1];
            break;
        }
        if(url != ''){
            
            //Replace http://youtu.be/xxxxxxxx for http://www.youtube.com/v/xxxxxxxxxx
            if(protocol(url).match(new RegExp("https://www.youtube.com/","g"))){
                //Replace url
                yurl = protocol(url).replace(protocol(url),newUrl+youtubeId(protocol(url)));
                
            }else if(url.match(new RegExp("http://youtu.be/","g"))){
                //Replace url
                yurl = url.replace("http://youtu.be/",newUrl);
                
            }else{
                yurl = false;
            }
            return yurl;
        }
    }

    /**
     * Format HTML
     * @param mode
     * @param size
     * @param data
     * @returns {string|string}
     */
    function dataToHtml(mode,size,data){
        if(data != false){
            var Width = 'width="'+ size[0] + '"';
            var height = 'height="'+ size[1] + '"';
            switch(mode){
                case 'embed':
                    var objectCode = '<div class="youtube">';
                        objectCode +='<object type="application/x-shockwave-flash" '+Width+height+' data="'+data+'&modestbranding=1'+'">';
                        objectCode += '<param name="movie" value="'+data+'&modestbranding=1'+'" />';
                        objectCode += '<param name="wmode" value="transparent" />';
                        objectCode += '</object>';
                        objectCode += '</div>';
                    break;
                case 'iframe':
                    var objectCode ='<iframe src="'+data+'" '+Width+height+' frameborder="0" allowfullscreen>⁪&nbsp;</iframe>';
                    break;
            }
            return objectCode;
        }
    }

    /**
     * Insert content when the window form is submitted
     * @returns {string}
     */
    function insert(){
        var options = '',
        html5State = $("#video").is(":checked"),
        youtubeAutoplay = $("#youtubeAutoplay").is(":checked"),
        youtubeREL = $("#youtubeREL").is(":checked"),
        youtubeHD = $("#youtubeHD").is(":checked");

        switch (youtubeAutoplay){
            case false:
                options += '';
            break;
            case true:
                options += '&amp;autoplay=1';
            break;
            default:
            options += '';
                break;
        }
        //SELECT Include related videos
        //var relvideo = document.getElementById("youtubeREL");
        switch (youtubeREL)       {
            case false: 
                options += '';
                break;
            case true:
                options += '&amp;rel=0';
                break;
            default:
            options += '';
                break;
        }
        //SELECT Watch in HD
        //var HD = document.getElementById("youtubeHD");
        switch (youtubeHD){
            case false:
                options += '';
            break;
            case true:
                options += '&amp;hd=1';
            break;
            default:
            options += '';
                break;
        }
        //Config Size Video
        var width = $('#youtubeWidth').val();
        var height = $('#youtubeHeight').val()
        //console.log(html5State);
        /*var test = win.find('button')[0].value();
        console.log(test);*/
        if(html5State != false){
            var newYouTubeUrl = convertUrl($('#youtubeID').val(),'iframe');
        }else{
            var newYouTubeUrl = convertUrl($('#youtubeID').val(),'embed');
        }
        if(newYouTubeUrl !== undefined || newYouTubeUrl !== false){
            // Insert the contents from the input into the document
            if(html5State != false){
                return dataToHtml('iframe',[width,height],newYouTubeUrl);
            }else{
                return dataToHtml('embed',[width,height],newYouTubeUrl+options);
            }
        }
    }

    /**
     * Update Timer with keypress
     * @param ts
     * @param func
     */
    function updateTimer(ts,func){
        if (this.timer) clearTimeout(this.timer);
        this.timer = setTimeout(func, ts ? ts : 1000);
    }

    /**
     * public
     */
    return {
        preview:function(){
            //console.log(convertUrl($('#youtubeID').val()));
            $('#preview').html(
                dataToHtml('embed',[420,315],convertUrl($('#youtubeID').val(),'embed'))
            );
        },
        run:function(){
            if(insert()){
                //editor.insertContent(objectCode);
                parent.tinymce.activeEditor.insertContent(insert());
                parent.tinymce.activeEditor.windowManager.close();
            }else{
                parent.tinymce.activeEditor.windowManager.close();
            }
        },
        runPreview:function(){
            if($("#preview").length !=0){
                $('#youtubeID').keypress(function(){
                    updateTimer('','youTube.preview();');
                }).change(function(){
                    updateTimer(100,'youTube.preview();');
                });
            }
        }
    }
})(jQuery, window, document);
/**
 * Execute namespace youtube
 */
$(function(){
    // Init templatewith mustach
    var data = {
        "youtubeurl": parent.tinymce.util.I18n.translate('Youtube URL'),
        "youtubeID": parent.tinymce.util.I18n.translate('Youtube ID'),
        "youtubeWidth": parent.tinymce.util.I18n.translate('width'),
        "youtubeHeight": parent.tinymce.util.I18n.translate('height'),
        "youtubeAutoplay": parent.tinymce.util.I18n.translate('autoplay'),
        "youtubeHD": parent.tinymce.util.I18n.translate('HD video'),
        "youtubeREL": parent.tinymce.util.I18n.translate('Related video')
    };
    //Use jQuery's get method to retrieve the contents of our template file, then render the template.
    $.get('view/forms.html' , function (template) {
        filled = Mustache.render( template, data );
        $('#template-container').append(filled);
        // preview
        youTube.runPreview();
        $('#insert-btn').on('click',function(){
            youTube.run();
        });
    });
});