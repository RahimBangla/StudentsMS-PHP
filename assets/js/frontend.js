
(function($) {

	'use strict';
	
	$(document).ready(function () {

	    // frontend menu external link enable/disable
	    $('.ext_url').change(function () {
	        var v = (this.checked ? 1 : 0);
	        if (v) {
	            $('#external_link').prop("disabled", false);
	        } else {
	            $('#external_link').prop("disabled", true);
	        }
	    });

		// switch for frontend menu
		$('.switch_menu').on("change", function(){
			var state = $(this).prop('checked');
			var menu_id = $(this).data('menu-id');
			console.log(menu_branchID); 
			$.ajax({
				type: 'POST',
				url: base_url + 'frontend/menu/status',
				data: {'menu_id': menu_id, 'branch_id': menu_branchID, 'status' : state},
				dataType: "html",
				success: function(data) {
					swal({
						type: 'success',
						title: "Successfully",
						text: data,
						showCloseButton: true,
						focusConfirm: false,
						buttonsStyling: false,
						confirmButtonClass: 'btn btn-default swal2-btn-default',
						footer: '*Note : You can undo this action at any time'
					});
				}
			});
		});
	});
}).apply(this, [jQuery]);

