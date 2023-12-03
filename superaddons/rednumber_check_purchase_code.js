(function($) {
    "use strict";
    $( document ).ready( function () { 
    	   var loading = 0;
    		$("body").on("click",".rednumber-active",function(e){
    			e.preventDefault();
    			if(loading != 0){
    				return ;
    			}
    			var bnt = $(this);
    			bnt.html("Checking...");
    			loading = 1;
    			var ip = $(this).closest('.rednumber-purchase-container').find("input");
    			var data = {
					'action': 'rednumber_check_purchase_code',
					'code': ip.val(),
					'id': ip.data("id")
				};
    			jQuery.post(ajaxurl, data, function(response) {
    				console.log(response);
    				loading = 0;
    				bnt.html("Active");
					if( response == "ok" ){
						$(".rednumber-purchase-container_show").removeClass('hidden');
						$(".rednumber-purchase-container_form").addClass('hidden');
					}else{
						alert(response);
					}
				});

    		})
    		$("body").on("click",".rednumber-remove",function(e){
    			e.preventDefault();
				var remove = {
					'action': 'rednumber_check_purchase_code_remove',
					'id': $(this).data("id")
				};
				jQuery.post(ajaxurl, remove, function(response) {
					$(".rednumber-purchase-container_form").removeClass('hidden');
					$(".rednumber-purchase-container_show").addClass('hidden');
				});

    		})
    })
})(jQuery);