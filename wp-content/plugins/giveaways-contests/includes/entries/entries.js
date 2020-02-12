/* jshint asi: true */
jQuery(document).ready(function($){
	
	
	$('#fca-cc-pick-winner-button').click(function(e){
		var $thisBtn = $(this).prop("disabled",true)
		var originalHtml = $thisBtn.html()
		$thisBtn.html('...')
		jQuery.ajax({
			url: adminData.ajaxurl,
			type: 'POST',
			data: {
				"action": "fca_cc_get_winner",
				"nonce": adminData.nonce,
				"post_id": adminData.post_id,
			}
		}).done( function( response ) {
			console.log ( response )
			$thisBtn.html( originalHtml ).prop("disabled",false)
			if ( response.success ) {
				$('.fca-cc-winning-entries').append('<tr><td class="fca-cc-winner-count">#</td><td>' + response.data.name + '</td><td><input class="fca-cc-email-input" type="text" readonly="readonly" onclick="this.select()" value="'
					+ response.data.email + '"/></td><td>' + response.data.time + '</td><td>' + response.data.ip + '</td><td class="fca-cc-status-toggle-td"><button type="button" class="button button-secondary fca-cc-reject-winner" data-winner-id="' + response.data.id + '" >Reject</button></td></tr>').show()
				add_reject_winner_button_click()
				renumber_winners()
			} else {
				alert ("No eligible entries found")
			}
		})	
	})
	
	function add_reject_winner_button_click() {
		$('.fca-cc-reject-winner').unbind('click')
		$('.fca-cc-reject-winner').click(function(e){
			var $thisBtn = $(this).prop("disabled",true)
			var originalHtml = $thisBtn.html()
			$thisBtn.html('...')
			jQuery.ajax({
				url: adminData.ajaxurl,
				type: 'POST',
				data: {
					"action": "fca_cc_reject_winner",
					"nonce": adminData.nonce,
					"post_id": adminData.post_id,
					"id": $(this).data("winner-id"),
				}
			}).done( function( response ) {
				
				if (  response.success ) {
					$thisBtn.closest('tr').fadeOut( 400, function(){
							$(this).remove();
							renumber_winners()
						})
					
				} else {
					$thisBtn.html( originalHtml )
					$thisBtn.prop("disabled",false)
					alert ("An error occured - try reloading the page and trying again.")
				}
			})		
		})
	}
	add_reject_winner_button_click()
	
	
	$('.fca-cc-toggle-eligible').click(function(e){
		var $thisButton = $(this).prop("disabled",true)
		var originalHtml = $thisButton.html()
		$thisButton.html('...')
		jQuery.ajax({
			url: adminData.ajaxurl,
			type: 'POST',
			data: {
				"action": "fca_cc_set_eligible_status",
				"nonce": adminData.nonce,
				"post_id": adminData.post_id,
				"id": $(this).data("entry-id"),
				"status": $(this).closest('td').siblings('.fca-cc-status').html() === "rejected" ? "eligible" : "rejected",
			}
		}).done( function( response ) {
			console.log ( response )
			$thisButton.prop("disabled", false)
			if ( response.success ) {
				var altText = $thisButton.data("alttext")
				$thisButton.data("alttext", originalHtml )
				$thisButton.html( altText )
				var newStatus = $thisButton.closest('td').siblings('.fca-cc-status').html() === "rejected" ? "eligible" : "rejected"
				$thisButton.closest('td').siblings('.fca-cc-status').html( newStatus )
			} else {
				$thisButton.html( originalHtml )
				alert ("An error occured - try reloading the page and trying again.")
			}
		})
	})
	
})

function renumber_winners() {
	var $ = jQuery
	$('.fca-cc-winner-count').each(function(i){
		$(this).html(i+1)
	})
}
