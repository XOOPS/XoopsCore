<{if $yourvote < 0}>
    <div id="rating" class="rating">
        <{section name=foo loop=10}>
            <button id="button-stars" class="option" value="<{$smarty.section.foo.iteration}>" title="<{$smarty.section.foo.iteration}>"><i class="stars"></i></button>
        <{/section}>
        <input type="hidden" id="content_id" value="<{$content_id}>">
    </div>

    <script language="javascript">
    <!--
    $(".option").click(function(){
        var option = $(this).val();
        var item   = $("#content_id").val();
        var token  = "<{$security}>";

        $.ajax({
            type: "POST",
            url: "<{xoAppUrl 'modules/page/jquery_rating.php'}>",
            data: "option="+option+"&content_id="+item+"&XOOPS_TOKEN_REQUEST="+token,
            success: function(responce) {
                var json = jQuery.parseJSON(responce);
//                alert( json.error );
                if(json.error == "0") {
                    $(".average").html( json.average );
                    $(".voters").html( json.voters );
                    $(".vote").html( json.vote );
                    $(".yourvote").removeClass("hide");
                    $(".rating").addClass("hide");
                }
            }
        });
    });
    //-->
    </script>
<{/if}>
