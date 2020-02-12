/* jshint asi: true */
jQuery( document ).ready( function($) {

	// //SHOW RESULTS PAGE FOR CONTESTS USER IS ENTERED IN
	// $('.fca_cc_contest').each( function(){
	// 	var contestID = $(this).data('postid')
	// 	if ( get_cookie( 'fca_cc_entry_' + contestID ) != '' ) {
	// 		$(this).find('.fca_cc_button').hide()
	// 		$(this).find('.fca_cc_description').hide()
	// 		$(this).find('.fca_cc_contest_entry').hide()
	//
	// 		$(this).find('.fca_cc_description_img').show()
	// 		$(this).find('.fca_cc_countdown').show()
	// 		$(this).find('.fca_cc_contest_results').show()
	// 	}
	//
	// })

	$('.fca_cc_button').click( function(e){
		var checkBox = document.getElementById("mycheck");

		var $div = $(this).closest('.fca_cc_contest')

		$div.find( '.fca_cc_optin_input' ).not('.tooltipstered').tooltipster( {trigger: 'custom', maxWidth: 240, arrow: false, theme: ['tooltipster-borderless', 'tooltipster-contest-cat'] } )

		var contest = eval( 'contestData_' + $div.data('postid') )

		//hide errors
		$div.find( '.fca_cc_optin_input' ).tooltipster('close')
		$div.find( '.fca_cc_optin_input' ).removeClass('fca_cc_invalid')

		if ( $(this).attr('id') === 'fca_cc_enter_button' ) {
			//LOAD USER BY DEFAULT
			if ( contest.hasOwnProperty('user') ) {
				$div.find( '#fca_cc_name_input' ).val( contest.user.name )
				$div.find( '#fca_cc_email_input' ).val( contest.user.email )
				$div.find( '#fca_cc_tel_input' ).val( contest.user.tel )
				$div.find( '#fca_cc_code_input' ).val( contest.user.code )
			}

			$(this).hide()
			$div.find('.fca_cc_description_img').hide()
			$div.find('.fca_cc_countdown').hide()

			$div.find('.fca_cc_contest_entry').show()
			$div.find('.fca_cc_optin_input').first().focus()

		} else if ( $(this).attr('id') === 'fca_cc_optin_button' ) {

			var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			var verif = /^\+?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
			var name = $div.find( '#fca_cc_name_input' ).val()
			var tel = $div.find( '#fca_cc_tel_input' ).val()
			var email = $div.find( '#fca_cc_email_input' ).val()
			var code = $div.find( '#fca_cc_code_input' ).val()
			var email_validated = regex.test( email )
			var tel_validated = verif.test( tel )
			var name_validated = name !== '' || $div.find( '#fca_cc_name_input' ).length === 0
			var code_validated = code !== '' || $div.find( '#fca_cc_code_input' ).length === 0

			if ( name_validated && email_validated && tel_validated && code_validated && checkBox.checked == true) {
				add_entry( name, email, tel, contest, code )

				$(this).hide()
				$div.find('.fca_cc_description').hide()
				$div.find('.fca_cc_contest_entry').hide()

				$div.find('.fca_cc_description_img').show()
				$div.find('.fca_cc_countdown').show()
				$div.find('.fca_cc_contest_results').show()

			} else {
				//show some error
				if ( !email_validated && !name_validated && !tel_validated && !code_validated) {
					$div.find( '#fca_cc_email_input, #fca_cc_name_input, #fca_cc_tel_input, #fca_cc_code_input' ).addClass('fca_cc_invalid').tooltipster('open').first().focus()
				}
				if ( !email_validated && !name_validated && !tel_validated && !code_validated) {
					$div.find( '#fca_cc_email_input, #fca_cc_name_input, #fca_cc_tel_input, #fca_cc_code_input' ).addClass('fca_cc_invalid').tooltipster('open').first().focus()
				}
				else if ( !email_validated  && !name_validated && !tel_validated) {
					$div.find( '#fca_cc_email_input, #fca_cc_name_input, #fca_cc_tel_input' ).addClass('fca_cc_invalid').tooltipster('open').focus()
				}

				else if ( !email_validated  && !name_validated && !code_validated) {
					$div.find( '#fca_cc_email_input, #fca_cc_name_input, #fca_cc_code_input' ).addClass('fca_cc_invalid').tooltipster('open').focus()
				}

				else if ( !email_validated  && !tel_validated && !code_validated) {
					$div.find( '#fca_cc_email_input, #fca_cc_tel_input, #fca_cc_code_input' ).addClass('fca_cc_invalid').tooltipster('open').focus()
				}

				else if ( !name_validated  && !tel_validated && !code_validated) {
					$div.find( '#fca_cc_name_input, #fca_cc_tel_input, #fca_cc_code_input' ).addClass('fca_cc_invalid').tooltipster('open').focus()
				}

				else if ( !email_validated  && !tel_validated) {
					$div.find( '#fca_cc_email_input, #fca_cc_tel_input' ).addClass('fca_cc_invalid').tooltipster('open').focus()
				}

				else if ( !name_validated && !tel_validated) {
				$div.find( '#fca_cc_name_input, #fca_cc_tel_input' ).addClass('fca_cc_invalid').tooltipster('open').focus()
				}

				else if ( !name_validated && !email_validated) {
				$div.find( '#fca_cc_name_input, #fca_cc_email_input' ).addClass('fca_cc_invalid').tooltipster('open').focus()
				}

				else if ( !email_validated && !code_validated) {
				$div.find( '#fca_cc_email_input, #fca_cc_code_input' ).addClass('fca_cc_invalid').tooltipster('open').focus()
				}

				else if ( !name_validated && !code_validated) {
				$div.find( '#fca_cc_name_input, #fca_cc_code_input' ).addClass('fca_cc_invalid').tooltipster('open').focus()
				}

				else if ( !tel_validated && !code_validated) {
				$div.find( '#fca_cc_tel_input, #fca_cc_code_input' ).addClass('fca_cc_invalid').tooltipster('open').focus()
				}

				else if ( !email_validated) {
				$div.find( '#fca_cc_email_input' ).addClass('fca_cc_invalid').tooltipster('open').focus()
				}

				else if (!name_validated) {
				$div.find( '#fca_cc_name_input' ).addClass('fca_cc_invalid').tooltipster('open').focus()
				}

				else if ( !tel_validated) {
				$div.find( '#fca_cc_tel_input' ).addClass('fca_cc_invalid').tooltipster('open').focus()
				}

				else if ( !code_validated) {
				$div.find( '#fca_cc_code_input' ).addClass('fca_cc_invalid').tooltipster('open').focus()
				}

				else if (checkBox.checked == false) {
				$("p.rouge").text("Vous devez accepter les conditions pour participer");
				}

			}
		}
	})

	$('.fca_cc_optin_input').keypress(function (e) {
        if ( e.which == 13 ) {
            $(this).siblings('#fca_cc_optin_button').click()
        }
    })

	$('.fca_cc_terms').click( function(e){
		e.preventDefault()
		var $div = $(this).closest('.fca_cc_contest')
		$div.find( '.fca_cc_terms' ).not('.tooltipstered').tooltipster( { contentAsHTML: true, trigger: 'click', maxWidth: 600, arrow: false, theme: ['tooltipster-borderless', 'tooltipster-contest-cat-terms'] } ).click()
	})


})

function contest_countdown() {
	var $ = jQuery
	$('.fca_cc_countdown').each( function(){

		var secondsRemaining = $(this).data( 'seconds' )

		if ( secondsRemaining - 1 < 0 ) {
			secondsRemaining = 0
			$(this).siblings('#fca_cc_enter_button').hide()
			$(this).siblings('.fca_cc_contest_ended').show()
		} else {
			secondsRemaining = secondsRemaining - 1
		}

		$(this).data( 'seconds', secondsRemaining )

		var newTime = parse_time ( secondsRemaining )

		$(this).find('.fca_cc_time_days').html( newTime.days )
		$(this).find('.fca_cc_time_hours').html( newTime.hours )
		$(this).find('.fca_cc_time_minutes').html( newTime.minutes )
		$(this).find('.fca_cc_time_seconds').html( newTime.seconds )

	})
}
var updateFn = window.setInterval(contest_countdown, 1000)

function parse_time ( inputSeconds ) {
	inputSeconds = parseInt( inputSeconds )
	var days = Math.floor( inputSeconds / 86400 )
	var hours = Math.floor( inputSeconds / 3600) % 24
	var minutes = Math.floor( inputSeconds / 60 ) % 60
	var seconds = inputSeconds % 60

	return {
		days: days,
		hours: hours,
		minutes: minutes,
		seconds: seconds
	}
}

function add_entry( name, email, tel, contest, code ) {

	jQuery.ajax({
		url: contest.ajaxurl,
		type: 'POST',
		data: {
			"action": "fca_cc_entry",
			"nonce": contest.nonce,
			"post_id": contest.post_id,
			"name": name,
			"tel": tel,
			"email": email,
			"code": code,
		}
	}).done( function( response ) {
		if ( response.success || response.data == 'exists' ) {
			set_cookie( 'fca_cc_entry_' + contest.post_id, 1, 365 )
		}
		console.log ( response )
	})
}

function set_cookie( name, value, exdays ) {
    var d = new Date()
    d.setTime( d.getTime() + ( exdays*24*60*60*1000 ) )
    document.cookie = name + "=" + value + "" + "; expires=" + d.toUTCString()
}

function get_cookie( name ) {
	var value = "; " + document.cookie
	var parts = value.split( "; " + name + "=" )

	if ( parts.length === 2 ) {
		return parts.pop().split(";").shift()
	} else {
		return false
	}
}
