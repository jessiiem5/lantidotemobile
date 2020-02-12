/* jshint asi: true */
jQuery(document).ready(function($){
	
	//SET THE SLUG IF ITS EMPTY
	if ( $('#post_name').val() === '' ) {
		$('#post_name').val( $('#post_ID').val() )
	}

	//SET UP SAVE AND PREVIEW BUTTONS, THEN HIDE THE PUBLISHING METABOX
	var saveButton = '<button type="submit" class="button-primary" id="fca_cc_submit_button">Save</buttton>'
	var previewButton = '<button type="button" class="button-secondary" style="margin-left: 5px;" id="fca_cc_preview_button">Save & Preview</buttton>'
	$( '#normal-sortables' ).append( saveButton ).append( previewButton )
	$( '#submitdiv' ).hide()
	
	//MAKES CLICKING LABELS AUTO-SELECT THE NEXT ITEM
	
	
	//DO SOME STUFF ON SUBMIT	
	$('#fca_cc_submit_button').click(function(event) {
		$(window).unbind('beforeunload')
		event.preventDefault()
		
		// Add target
		var thisForm = $(this).closest('form')
		thisForm.removeAttr('target')

		// Remove preview url
		$('#fca_cc_preview_url').val('')
		
		// Submit form
		thisForm.submit()
		
		return false
	})
	 
	$('#fca_cc_preview_button').click(function(event) {
		event.preventDefault()
		// Add target
		var thisForm = $(this).closest('form')
		thisForm.prop('target', '_blank')
					
		// Submit form
		thisForm.submit()
		  
		return false
	})	
	
	
	//COLOR PICKER
	$('.fca-cc-input-color').wpColorPicker()
	
	//HIDE "ADD IMAGE" BUTTONS IF IMAGE HAS BEEN SET
	$('.fca_cc_image').each( function( index ){
		if ( $(this).attr('src') !== '' ) {
			$(this).siblings('.fca_cc_image_upload_btn').hide()
		}
	})
	
	//ACTIVATE DATEPICKER
	
	$('.fca-cc-input-datepicker').datetimepicker()
	// ACTIVATE TOOLTIPS

	////////////////
	// ON CLICK EVENT HANDLERS
	////////////////
	
	
	//MAKES SHORTCODE INPUT AUTO-SELECT THE TEXT WHEN YOU CLICK IT
	$('#fca-cc-shortcode').click(function(e) {
		this.select()
	})

	attach_image_upload_handlers()
	
})

//GLOBAL FUNCTIONS

////////////////
// MEDIA UPLOAD
////////////////
		
function attach_image_upload_handlers() {
	var $ = jQuery
	//ACTION WHEN CLICKING IMAGE UPLOAD
	$('.fca_cc_image_upload_btn, .fca_cc_image, .fca_cc_image_change_btn').unbind( 'click' )
	//HANDLER FOR RESULTS AND META IMAGES
	$('.fca_cc_image_upload_btn, .fca_cc_image, .fca_cc_image_change_btn').click(function(e) {
		
		e.preventDefault()
		var $this = $( this )
		
		//IF WE CLICK ON THE IMAGE VS THE BUTTON IT HAS TO WORK A LITTLE DIFFERENTLY
		if ( $(this).hasClass( 'fca_cc_image_change_btn' ) ) {
			$this = $( this.parentNode ).siblings('.fca_cc_image_upload_btn')
		} else if ( $(this).hasClass( 'fca_cc_image' ) ) {
			$this = $( this ).siblings('.fca_cc_image_upload_btn')
		}		
					
		var image = wp.media({ 
			title: adminData.selectImage_string,
			// mutiple: true if you want to upload multiple files at once
			multiple: false
		}).open()
		.on('select', function(){
			// This will return the selected image from the Media Uploader, the result is an object
			var uploaded_image = image.state().get('selection').first()

			var image_url = uploaded_image.toJSON().url
			// Assign the url value to the input field
			$this.siblings('.fca-cc-input-image').attr('value', image_url)
			$this.siblings('.fca_cc_image').attr('src',image_url)
							
			$this.hide()
			
			//UNHIDE THE REMOVE AND CHANGE IMAGE BUTTONS
			$this.siblings('.fca_cc_image_hover_controls').find('.fca_cc_image_change_btn').show()
			$this.siblings('.fca_cc_image_hover_controls').find('.fca_cc_image_revert_btn').show()

		})
	})
	
	//ACTION WHEN CLICKING REMOVE IMAGE
	$('.fca_cc_image_revert_btn').unbind( 'click' )
	$('.fca_cc_image_revert_btn').click( function(e) {
		$( this.parentNode ).siblings('.fca-cc-input-image').attr('value', '')
		$( this.parentNode ).siblings('.fca_cc_image').attr('src', '' )
		$( this.parentNode ).siblings('.fca_cc_image_upload_btn').show()
		$( this ).hide()
		$( this ).siblings( '.fca_cc_image_upload_btn' ).hide()
		
	})
}
