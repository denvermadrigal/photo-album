(function($){
	$(document).ready(function(){
    $('.show-login').click(function(){
      $('#modal-login').modal('show');
    });

		$('.nav-call').click(function(){
			var hash = $(this).attr('rel');
			// check if there are images
			$('#overlay').fadeIn('fast');
			$('#overlay_msg').append('<div>../Checking for new images</div>');
			$.get('ajax.php',{'path':hash,'do':'check.new'},function(res_new){
				var data_new = $.parseJSON(res_new);
				$('#overlay_msg').append('<div>../Found '+data_new.length+' new image(s)</div>');
				if(data_new.length){
					// check if there are thumbs to create
					$.get('ajax.php',{'path':hash,'do':'check.thumb'},function(res_chk_thumb){
						var data_chk_thumb = $.parseJSON(res_chk_thumb);
						if(data_chk_thumb.length){
							$('#overlay_msg').append('<div>../Preparing to create '+data_chk_thumb.length+' thumbnail(s)</div>');
							// create thumbs
							$.get('ajax.php',{'path':hash,'do':'create.thumb'},function(response){
								if(response > 0){
									$('#overlay_msg').append('<div>../Successfully created '+response+'/'+data_chk_thumb.length+'</div>');
									getImageList(hash);
								}else{
									$('#overlay_msg').append('<div>../For some reason no thumbnails were created</div>');
									getImageList(hash);
								}
							});
						}
					});
				}else{
					getImageList(hash);
				}
			});
		});
	});
})(jQuery);

function getImageList(hash){
	$.get('ajax.php',{'path':hash,'do':'get.list'},function(response){
		var data = $.parseJSON(response);
		if(data.length){
			var html = '<div class="page-header"><h1>'+hash.replace('albums','')+'</h1></div>';
			html+= '<div class="row-fluid">';
			var ctr = 0;
			for(var i = 0; i < data.length; i++){
				html+= '<div class="span4" style="text-align:center;">';
				html+= '<img class="visible-phone" src="'+escape(hash)+'/'+escape(data[i].thumb)+'" alt="" />';
				html+= '<a class="hidden-phone" data-toggle="lightbox" href="#lb_'+i+'"><img src="'+escape(hash)+'/'+escape(data[i].thumb)+'" alt="" /></a>';
				html+= '<div id="lb_'+i+'" class="lightbox hide fade hidden-phone" tabindex="">';
				html+= '</div>';
				if((ctr == 2)){
					html+= '</div><div class="row-fluid">';
				}
				ctr = (ctr == 2)?0:ctr+1;
			}
			html+= '</div>';
			$('#main_body').html(html);
			$('#overlay').fadeOut('fast',function(){
				$('#main_body').fadeIn();
			});
		}
	});
}
