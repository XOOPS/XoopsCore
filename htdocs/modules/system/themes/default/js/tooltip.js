/*
 * Tooltip script 
 * powered by jQuery (http://www.jquery.com)
 * 
 * written by Alen Grakalic (http://cssglobe.com)
 * 
 * for more info visit http://cssglobe.com/post/1695/easiest-tooltip-and-image-preview-using-jquery
 *
 */
 


this.tooltip = function(){	
    /* CONFIG */
    yOffset = 20;

    /* END CONFIG */		
    $(".xo-tooltip").hover(function(e){
        this.t = this.title;
        this.title = "";
        
        //Removing alt atribute for IE
        $("a.xo-tooltip img").each(function() { $(this).attr("title", ""); $(this).attr("alt", ""); });
        
        $("body").append("<p id='tooltip'>"+ this.t +"</p>");

        $("#tooltip")
            .css("top",(e.pageY + yOffset) + "px")
            .css("left",(e.pageX - ($('#tooltip').width() / 2)) + "px")
            .fadeIn("fast");
    }, function(){
        this.title = this.t;
        $("#tooltip").remove();
    });
        
    //$("a.tooltip img").hover(function(e){
       //$(this).attr("title", ""); 
       //$(this).attr("alt", ""); 
    //});
    
    $("a.tooltip").mousemove(function(e){
    
       xOffset = - ($('#tooltip').width() / 2);
       scrollBarWidth = 15; //padding from right side allways
       windowWidth = $(window).width() - scrollBarWidth; 
       
       if (e.pageX + xOffset <= 0 ) {
            xOffset = - e.pageX;
        } 
        if (e.pageX + xOffset + $('#tooltip').width() >= windowWidth) {
            xOffset = windowWidth - e.pageX - $('#tooltip').width();
        }
        
        $("#tooltip")
            .css("top",(e.pageY + yOffset) + "px")
            .css("left",(e.pageX + xOffset) + "px");
    });
};

// starting the script on page load
$(document).ready(function(){
    tooltip();
});