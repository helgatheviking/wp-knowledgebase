jQuery(document).ready(function($) {

	$('.kbe_admin_order').sortable({
		revert: false,
		tolerance: "pointer",
		start: function( event, ui ){
			$('#message').fadeOut();
			},
		update: function( event, ui ){
			var order = $(event.target).sortable('toArray', { 'attribute': 'data-id' });
			$(event.target).next('.custom_order').val(order);
		}
	});

	$('.kbe-reorder-submit').click( function(e){

		var msg, type;

		e.preventDefault();

		// show the spinner
		var $spinner = $(this).next();		
		$spinner.css('visibility', 'visible');
		
		// pick the data type
		type = $(this).data('type');

		$.ajax({
			type: "POST",
			url: ajaxurl,
			data: { 
				action: 'kbe_reorder', 
				security: $('#kbe_order_nonce').val(), 
				order: $(this).prev('.custom_order').val(),
				type: type
			}
		}).done(function( response ) {

			// stop the spinner
			$spinner.css('visibility', 'hidden');
	
			// choose the message
			$message = $('#message');

			if( response == 1 ){
				msg = type + '_success';
			} else {
				msg = type + '_fail';
				$message.addClass('error');
			}

			// show the message
			$message.html('<p>' + kbe_reorder_messages[msg] + '</p>' ).show();
			$("html, body").animate({ scrollTop: 0 });

		 });
	});
	


});
