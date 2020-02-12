/* jshint asi: true */
jQuery(document).ready(function($){
	
	var $deactivateButton = $('#the-list tr.active').filter( function() { return $(this).data('plugin') === 'giveaways-contests/contest-cat.php' } ).find('.deactivate a')
		
	$deactivateButton.click(function(e){
		e.preventDefault()
		$deactivateButton.unbind('click')
		$('body').append(fca_cc.html)
		fca_cc_uninstall_button_handlers( $deactivateButton.attr('href') )
		
	})
}) 

function fca_cc_uninstall_button_handlers( url ) {
	var $ = jQuery
	$('#fca-cc-deactivate-skip').click(function(){
		$(this).prop( 'disabled', true )
		window.location.href = url
	})
	$('#fca-cc-deactivate-send').click(function(){
		$(this).prop( 'disabled', true )
		$(this).html('...')
		$('#fca-cc-deactivate-skip').hide()
		$.ajax({
			url: fca_cc.ajaxurl,
			type: 'POST',
			data: {
				"action": "fca_cc_uninstall",
				"nonce": fca_cc.nonce,
				"msg": $('#fca-cc-deactivate-textarea').val()
			}
		}).done( function( response ) {
			console.log ( response )
			window.location.href = url		
		})	
	})
	
}