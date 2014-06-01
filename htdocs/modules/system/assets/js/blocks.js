$(document).ready(
	function(){
		// Controls Drag + Drop
		$('.xo-blocksection').sortable({
				accept: 'xo-block',
				cancel: '.xo-title',
				items: '.xo-block',
				connectWith: '.xo-blocksection',
				update: function(event, ui) {
                    var list = $(this).sortable( 'serialize');
                    $.post( 'admin.php?fct=blocksadmin&op=order', list );
                },
				receive: function(event, ui) {
                    var side = $(this).attr('side');
                    var bid = $(ui.item).attr('bid');
                    var list = $(this).sortable( 'serialize');
                    
                    $.post( 'admin.php', { fct: 'blocksadmin', op: 'drag', bid: bid, side: side } );
                    
                    $.post( 'admin.php?fct=blocksadmin&op=order', list );
                      
                }
			}
		);
		$(".xo-blocksection").disableSelection();
		
		$('.xo-blockhide').sortable({
				accept: 'xo-block',
				cancel: '.xo-title',
				items: '.xo-block',
				connectWith: '.xo-blocksection'/*,
				update: function(event, ui) {
                    var list = $(this).sortable( 'serialize');
                    $.post( 'admin.php?fct=blocksadmin&op=order', list );
                },
				receive: function(event, ui) {
                    var side = $(this).attr('side');
                    var bid = $(ui.item).attr('bid');
                    var list = $(this).sortable( 'serialize');
                    
                    $.post( 'admin.php', { fct: 'blocksadmin', op: 'drag', bid: bid, side: side } );
                    
                    $.post( 'admin.php?fct=blocksadmin&op=order', list );
                      
                }*/
			}
		);
		$(".xo-blockhide").disableSelection();
	}
);