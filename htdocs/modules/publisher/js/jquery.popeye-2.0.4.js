/*
 * jQuery Popeye 2.0.4 - http://dev.herr-schuessler.de/jquery/popeye/
 *
 * converts a HTML image list in image gallery with inline enlargement
 *
 * Copyright (C) 2008 - 2010 Christoph Schuessler (schreib@herr-schuessler.de)
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 */
 
(function ($) {


    ////////////////////////////////////////////////////////////////////////////
    //
    // $.fn.popeye
    // popeye definition
    //
    ////////////////////////////////////////////////////////////////////////////
    $.fn.popeye = function (options) {
    
        // build main options before element iteration
        //----------------------------------------------------------------------
        var opts = $.extend({}, $.fn.popeye.defaults, options);
        
        ////////////////////////////////////////////////////////////////////////////////
        //
        // firebug console output
        // @param text String the debug message
        // @param type String the message type [info | warn] (optional)
        //
        ////////////////////////////////////////////////////////////////////////////////
        function debug(text,type) {
            if (window.console && window.console.log && opts.debug) {
                if(type == 'info' && window.console.info) {
                    window.console.info(text);
                }
                else if(type == 'warn' && window.console.warn) {
                    window.console.warn(text);
                }
                else {
                    window.console.log(text);
                }
            }
        }
                   
        // let's go!
        //----------------------------------------------------------------------
        return this.each(function(){
            
            // first thing to do: make ppy html visible           
            $(this).addClass('ppy-active');
            
            // cache object
            var $self           = $(this),
            
            // images
            img                 = $self.find('.ppy-imglist > li > a > img'),
            a                   = $self.find('.ppy-imglist > li > a'),
            tot                 = img.length,
            
            // single image mode
            singleImageMode     = (tot == 1) ? true : false,
            
            // start in compact mode
            enlarged            = false,
            
            // counter vars
            cur             = 0,                // array index of currently displayed image
 
            // extra classes
            eclass          = 'ppy-expanded',       //class to be applied to enlarged popeye-box
            lclass          = 'ppy-loading',        //class to be applied to stage while loading image
            sclass          = 'ppy-single-image',   //class to be applied to popeye-box if there's only one image to display
            
            // html nodes
            ppyPlaceholder      = $('<div class="ppy-placeholder"></div>'),
            ppyStageWrap        = $('<div class="ppy-stagewrap"></div>'),
            ppyCaptionWrap      = $('<div class="ppy-captionwrap"></div>'),
            ppyOuter            = $self.find('.ppy-outer'),
            ppyStage            = $self.find('.ppy-stage'),
            ppyNav              = $self.find('.ppy-nav'),
            ppyPrev             = $self.find('.ppy-prev'),
            ppyNext             = $self.find('.ppy-next'),
            ppySwitchEnlarge    = $self.find('.ppy-switch-enlarge'),
            ppySwitchCompact    = $self.find('.ppy-switch-compact').addClass('ppy-hidden'),
            ppyCaption          = $self.find('.ppy-caption'),
            ppyText             = $self.find('.ppy-text'),
            ppyCounter          = $self.find('.ppy-counter'),
            ppyCurrent          = $self.find('.ppy-current'),
            ppyTotal            = $self.find('.ppy-total'),
            
            // css objects
            cssSelf = {
                position:       'absolute',
                width:          'auto',
                height:         'auto',
                margin:         0,
                top:            0,
                left:           (opts.direction == 'right') ? 0 : 'auto',
                right:          (opts.direction == 'left') ? 0 : 'auto'
            },
            cssStage    = {
                height:         ppyStage.height(),
                width:          ppyStage.width()
            },
            cssCaption    = {
                height:         ppyCaption.height()
            },
            cssPlaceholder = {
                height:         (opts.caption == 'hover' || false) ? ppyOuter.outerHeight() : $self.outerHeight(),
                width:          (opts.caption == 'hover' || false) ? ppyOuter.outerWidth() : $self.outerWidth(),
                float:          $self.css('float'),
                marginTop:      $self.css('margin-top'),
                marginRight:    $self.css('margin-right'),
                marginBottom:   $self.css('margin-bottom'),
                marginLeft:     $self.css('margin-left')
            };
            
            // make caption array from caption element or alt tag           
            var cap = [];
            for(var i=0; i<img.length; i++) {
                var extcap = $self.find('.ppy-imglist li').eq(i).find('.ppy-extcaption');
                cap[i]   = extcap.length > 0 ? extcap.html() : img[i].alt;
            }
            
            // check for html errors
            if( !ppyStage.length || !ppyNav.length || !ppyOuter.length ) {
                debug('$.fn.popeye: Incorrect HTML structure','warn');
            }
            
            // check for images
            else if( tot === 0 ) {
                debug('$.fn.popeye: No images found','warn');
            }
            // no errors, setup done! 
            //------------------------------------------------------------------
            else {
                singleImageMode ? debug('$.fn.popeye -> SingleImageMode started') : debug('$.fn.popeye -> ' + tot + ' thumbnails found.');
                init();
            }
            
                ////////////////////////////////////////////////////////////////////
                //
                // $.fn.popeye.showThumb
                // show thumbnail
                // @param i Int the index of the thumbnail to show (optional)
                // @param transition Bool show transition between images (optional)
                //
                ////////////////////////////////////////////////////////////////////
                function showThumb(i, transition) {
                     
                    // optional parameters
                    transition = transition || false;
                    i = i || cur;
                    
                    // set selected thumb as background image of stage
                    var cssStageImage = {
                        backgroundImage:    'url(' + img[i].src + ')'
                    };
                    // bogus animation css for IE 
                    var cssTemp = {
                        height:             '+=0'
                    };
                    
                    // if we are in enlarged mode, return to thumb mode
                    if(enlarged) {
                    
                        hideCaption();
                        
                        // fade image out and compact stage with transition
                        ppyStage.fadeTo((opts.duration/2),0).animate( cssStage, {
                            queue:      false,
                            duration:   opts.duration,
                            easing:     opts.easing,
                            complete:   function() {
                                
                                enlarged = false;
                                debug('$.fn.showThumb: Entering COMPACT MODE','info');
        
                                // remove extra styling and reset z-index
                                $self.removeClass(eclass);
                                $self.css('z-index','');
                                
                                // switch buttons
                                ppySwitchEnlarge.removeClass('ppy-hidden');
                                ppySwitchCompact.addClass('ppy-hidden');
                                
                                // recursive function call
                                showThumb();
                                
                                // fade the stage back in
                                $(this).fadeTo((opts.duration/2),1);
                            }
                        });
                    }
                    else {
                    
                        // if we navigate from one image to the next, fade out the stage
                        if(transition) {
                        
                            // fade out image so that background shines through
                            // background can contain loading gfx
                            ppyStageWrap.addClass(lclass);
                            ppyStage.fadeTo((opts.duration/2), 0);
                            
                            // once thumb has loadded...
                            var thumbPreloader = new Image();
                            thumbPreloader.onload = function() {
                                debug('$.fn.popeye.showThumb: Thumbnail ' + i + ' loaded', 'info');
        
                                // remove loading indicator
                                ppyStageWrap.removeClass(lclass);
                                
                                // add all upcoming animations to the queue so that 
                                // they won't start when the preolader has loaded but when the fadeOut has finished
                                ppyStage.animate(cssTemp,1,'linear',function(){
                                    
                                    // set the new image
                                    ppyStage.css(cssStageImage);
                                    // fade the stage back in
                                    $(this).fadeTo((opts.duration/2),1);
                                
                                    // update counter and caption
                                    if(opts.caption == 'hover') {
                                        showCaption(cap[i]);
                                    }
                                    else if(opts.caption == 'permanent') {
                                        updateCaption(cap[i]);
                                    }
                                    updateCounter();
                                });
                                
                                //  fix IE animated gif bug
                                thumbPreloader.onload = function(){};
                            };
                            // preload thumb
                            thumbPreloader.src = img[i].src;
                        }
                        
                        // or just drag the image to the stage
                        else {
                            ppyStage.css(cssStageImage);
                            updateCounter();
                            showCaption(cap[i],true);
                        }
                        
                        // preload big image for instant availability
                        var preloader = new Image();
                        
                        preloader.onload = function() {
                            debug('$.fn.popeye.showThumb: Image ' + i + ' loaded','info');
                            preloader.onload = function(){};
                        };
                        
                        preloader.src = a[i].href;
                    }
                }
                
                
                ////////////////////////////////////////////////////////////////////
                //
                // $.fn.popeye.showImage
                // show large image
                // @param i Int the index of the image to show (optional)
                //
                ////////////////////////////////////////////////////////////////////
                function showImage(i) {
                
                    // optional parameter i
                    i = i || cur;
                    
                    // fade out image so that background shines through
                    // background can contain loading gfx
                    ppyStageWrap.addClass(lclass);
                    ppyStage.fadeTo((opts.duration/2), 0);
                    
                    // if there are multiple popeyes opened at the same time,
                    // make sure the current one gets a higher z-index
                    var allPpy = $('.' + eclass);
                    allPpy.css('z-index',opts.zindex-1);
                    $self.css('z-index',opts.zindex);
                    
                    // once image has loadded...
                    var preloader = new Image();
                    preloader.onload = function() {
                    
                        // remove loading class
                        ppyStageWrap.removeClass(lclass);
    
                        // set css
                        var cssStageTo = {
                            width:              preloader.width,
                            height:             preloader.height
                        };
                        var cssStageIm = {
                            backgroundImage:    'url(' + a[i].href + ')',
                            backgroundPosition: 'left top'
                        };
                        
                        hideCaption();
                        
                        // show transitional animation
                        ppyStage.animate( cssStageTo, {
                            queue:      false,
                            duration:   opts.duration,
                            easing:     opts.easing,
                            complete:   function(){
                                
                                if(opts.navigation == 'hover') {
                                    showNav();
                                }
                                
                                enlarged = true;
                                debug('$.fn.popeye.showImage: Entering ENLARGED MODE','info');
                                
                                // add extra class, expanded box can be styled accordingly
                                $self.addClass(eclass);
                                
                                // switch buttons
                                ppySwitchCompact.removeClass('ppy-hidden');
                                ppySwitchEnlarge.addClass('ppy-hidden');
                                
                                updateCounter();
                                
                                // set new bg image and fade it in
                                $(this).css(cssStageIm).fadeTo((opts.duration/2),1);
                                
                                // show caption
                                showCaption(cap[i]);
                                
                                preloadNeighbours();
                            }
                        });
                    };
                    
                    // preload image
                    preloader.src = a[i].href;
                    
                }
                
                
                ////////////////////////////////////////////////////////////////////
                //
                // $.fn.popeye.updateCounter
                // update image counter
                // @param i Int the index of the image (optional)
                //
                ////////////////////////////////////////////////////////////////////
                function updateCounter(i) {
                    
                    // optional parameter
                    i = i || cur;
                
                    ppyTotal.text(tot);        // total images
                    ppyCurrent.text(i + 1);    // current image number
                    debug('$.fn.popeye.updateCounter: Displaying image ' + (i + 1) + ' of ' + tot);
                }
                
                ////////////////////////////////////////////////////////////////////
                //
                // $.fn.popeye.preloadNeighbours
                // preload next and previos image
                // @param i Int the index of the current image (optional)
                //
                ////////////////////////////////////////////////////////////////////
                function preloadNeighbours(i) {
                    
                    // optional parameter
                    i = i || cur;
                    
                    var preloaderNext = new Image();
                    var preloaderPrev = new Image();
                    
                    var neighbour = i;
                    
                    // next image
                    if( neighbour < ( tot - 1) ) {
                        neighbour++; 
                    } else {
                        neighbour = 0;
                    }
                    preloaderNext.src = a[i].href[neighbour];
                    
                    // previous image
                    neighbour = i;
                    if( neighbour <= 0 ) {
                        neighbour = tot - 1;
                    } else {
                        neighbour--;
                    }
                    preloaderPrev.src = a[i].href[neighbour];
                }
                
                
                ////////////////////////////////////////////////////////////////////
                //
                // $.fn.popeye.showNav
                //
                ////////////////////////////////////////////////////////////////////
                function showNav() {
                    ppyNav.stop().fadeTo(150,opts.opacity);
                }
                
                
                ////////////////////////////////////////////////////////////////////
                //
                // $.fn.popeye.hideNav
                //
                ////////////////////////////////////////////////////////////////////
                function hideNav() {
                    ppyNav.stop().fadeTo(150,0);
                }
                
                
                ////////////////////////////////////////////////////////////////////
                //
                // $.fn.popeye.updateCaption
                // @param caption String the caption string
                //
                ////////////////////////////////////////////////////////////////////
                function updateCaption(caption) {
                
                    if(opts.caption) {
                        // update text box
                        ppyText.html(caption);
                    }
                }
                
                ////////////////////////////////////////////////////////////////////
                //
                // $.fn.popeye.showCaption
                // @param caption String the caption string
                // @param force Boolean force caption display even if caption string is empty
                //
                ////////////////////////////////////////////////////////////////////
                function showCaption(caption,force) {
                               
                    // if caption string is not empty...
                    if(caption && opts.caption) {
                        updateCaption(caption);
                        
                        debug('$.fn.popeye.showCaption -> ppyCaptionWrap.outerHeight(true): ' + ppyCaptionWrap.outerHeight(true));
                        
                        // make caption box visible
                        var cssTempCaption = {
                            visibility:   'visible' 
                        };
                        ppyCaption.css(cssTempCaption);
                        
                        if(opts.caption === 'permanent' && !enlarged) {
                            
                            // return to original caption height
                            ppyCaption.css(cssCaption);
                        }
                        else {
                        
                            // or animate it to its childs height
                            ppyCaption.animate({'height': ppyCaptionWrap.outerHeight(true)}, {
                                queue:      false,
                                duration:   90,
                                easing:     opts.easing
                            });
                        }
                    }
                    // if there's no caption to show...
                    else if(!caption && !force) {
                        hideCaption();
                    }
                }
                
                
                ////////////////////////////////////////////////////////////////////
                //
                // $.fn.popeye.hideCaption
                //
                ////////////////////////////////////////////////////////////////////
                function hideCaption() {
                    
                    // css to hide caption but allow its inner text box to expand to content height
                    var cssTempCaption = {
                        visibility:   'hidden',
                        overflow:     'hidden'
                    };
                    
                    // slide up caption box and hide it when done
                    ppyCaption.animate( {'height': '0px'}, {
                        queue:      false,
                        duration:   90,
                        easing:     opts.easing,
                        complete:   function() {
                            ppyCaption.css(cssTempCaption);
                        }
                    });
                }
                
                ////////////////////////////////////////////////////////////////////
                //
                // $.fn.popeye.previous
                // show previous image
                //
                ////////////////////////////////////////////////////////////////////
                function previous() {
                    if( cur <= 0 ) {
                        cur = tot - 1;
                    } else {
                        cur--;
                    }
                    if(enlarged) {
                        showImage(cur);
                    }
                    else {
                        showThumb(cur, true);
                    }
                    return cur;
                }
                
                ////////////////////////////////////////////////////////////////////
                //
                // $.fn.popeye.next
                // show next image
                //
                ////////////////////////////////////////////////////////////////////
                function next() {
                    if( cur < ( tot - 1) ) {
                        cur++; 
                    } else {
                        cur = 0;
                    }
                    if(enlarged) {
                        showImage(cur);
                    }
                    else {
                        showThumb(cur, true);
                    }
                    return cur;
                }
                
                ////////////////////////////////////////////////////////////////////
                //
                // $.fn.popeye.init
                // setup of popeye DOM and events
                //
                ////////////////////////////////////////////////////////////////////
                function init() {
                    
                    // popeye dom setup
                    //--------------------------------------------------------------
                    
                    // add css 
                    ppyPlaceholder.css(cssPlaceholder);
                    $self.css(cssSelf);
                    
                    // wrap popeye in placeholder 
                    $self.wrap(ppyPlaceholder);
                    
                    // wrap stage in container for extra styling (e.g. loading gfx)
                    ppyStageWrap = ppyStage.wrap(ppyStageWrap).parent();
                    
                    // wrap caption contents in wrapper (can't use wrap() here...)
                    ppyCaptionWrap = ppyCaption.wrapInner(ppyCaptionWrap).children().eq(0);
                    
                    // display first image
                    showThumb();
                    
                    // add event handlers
                    //--------------------------------------------------------------
                    // hover behaviour for navigation
                    if(opts.navigation == 'hover') {
                        hideNav();
                        $self.hover(
                            function(){
                                showNav();
                            },
                            function(){
                                hideNav();
                            }
                        );
                        ppyNav.hover(
                            function(){
                                showNav();
                            },
                            function(){
                                hideNav();
                            }
                        );
                    }
                    if(!singleImageMode) {
                        
                        // previous image button
                        ppyPrev.click(previous);
                        
                        // next image button
                        ppyNext.click(next);
                    
                    }
                    else {
                        $self.addClass(sclass);
                        ppyPrev.remove();
                        ppyNext.remove();
                        ppyCounter.remove();
                    }
                    
                    // hover behaviour for caption
                    if(opts.caption == 'hover') {
                        hideCaption();
                        $self.hover(
                            function(){
                                    showCaption(cap[cur]);
                            },
                            function(){
                                    hideCaption(true);
                            }
                        );
                     }
                     
                    // enlarge image button
                    ppySwitchEnlarge.click(function(){
                        showImage();
                        return false;
                    });
                    
                    // compact image button                          
                    ppySwitchCompact.click(function(){
                        showThumb(cur);
                        return false;
                    });
                }
        });
    };
    
    ////////////////////////////////////////////////////////////////////////////
    //
    // $.fn.popeye.defaults
    // set default  options
    //
    ////////////////////////////////////////////////////////////////////////////
    $.fn.popeye.defaults = {
        
        navigation: 'hover',            //visibility of navigation - can be 'permanent' or 'hover'
        caption:    'hover',            //visibility of caption, based on image title - can be false, 'permanent' or 'hover'
        
        zindex:     10000,              //z-index of the expanded popeye-box. enter a z-index that works well with your site and doesn't overlay your site's navigational elements like dropdowns
        
        direction:  'right',            //direction that popeye-box opens, can be 'left' or 'right'
        duration:   240,                //duration of transitional effect when enlarging or closing the box
        opacity:    0.8,                //opacity of navigational overlay (only applicable if 'navigation' is set to 'hover'
        easing:     'swing',            //easing type, can be 'swing', 'linear' or any of jQuery Easing Plugin types (Plugin required)
        
        debug:      false               //turn on console output (slows down IE8!)

    };
       
// end of closure, bind to jQuery Object
})(jQuery); 


////////////////////////////////////////////////////////////////////////////////
//
// avoid content flicker for non-js user agents
// (in order to use this, the js-files have to be included in the head of the
// html file!)
//
////////////////////////////////////////////////////////////////////////////////
jQuery('head').append('<style type="text/css"> .ppy-imglist { position: absolute; top: -1000em; left: -1000em; } </style>');
