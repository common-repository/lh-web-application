jQuery(document).ready(function(){
    
    
    	// File uploader
	var file_frame;
	jQuery('.lh_web_application-menu_icon-upload_button').on('click', function( event ){


		// If the media frame already exists, reopen it.
		if ( file_frame ) {
		  file_frame.open();
		  return;
		}
		// Create the media frame.
		file_frame = wp.media.frames.file_frame = wp.media({
		  title: 'Select an Image',
		  button: {
			text: 'Use this Image',
		  },
		  multiple: false  // only allow the one file to be selected
		});
		// When an image is selected, run a callback.
		file_frame.on( 'select', function() {
		  // We set multiple to false so only get one image from the uploader
		  attachment = file_frame.state().get('selection').first().toJSON();
		  jQuery('.lh_web_application-menu_icon-upload_button').parent().children('.lh_web_application-menu_icon-attachment_id').first().val(attachment.id);
		  
		   jQuery('.lh_web_application-menu_icon-upload_button').parent().children('.lh_web_application-menu_icon-attachment_url').first().val(attachment.url);

		});
		// Finally, open the modal
		file_frame.open();

	});

	
	

	jQuery('#lh_web_application-manifest_icon-upload_button').on('click', function( event ){
 
		event.preventDefault();
	 
		// If the media frame already exists, reopen it.
		if ( file_frame ) {
		  file_frame.open();
		  return;
		}
		// Create the media frame.
		file_frame = wp.media.frames.file_frame = wp.media({
		  title: 'Select an Image',
		  button: {
			text: 'Use this Image',
		  },
		  multiple: false  // only allow the one file to be selected
		});
		// When an image is selected, run a callback.
		file_frame.on( 'select', function() {
		  // We set multiple to false so only get one image from the uploader
		  attachment = file_frame.state().get('selection').first().toJSON();
		  jQuery('#lh_web_application-manifest_icon_attachment_id').val(attachment.id);
		  jQuery('#lh_web_application-manifest_icon-attachment_url').val(attachment.url);
		});
		// Finally, open the modal
		file_frame.open();
	});
	

	jQuery('#lh_web_application-ios_icon-upload_button').on('click', function( event ){
 
		event.preventDefault();
	 
		// If the media frame already exists, reopen it.
		if ( file_frame ) {
		  file_frame.open();
		  return;
		}
		// Create the media frame.
		file_frame = wp.media.frames.file_frame = wp.media({
		  title: 'Select an Image',
		  button: {
			text: 'Use this Image',
		  },
		  multiple: false  // only allow the one file to be selected
		});
		// When an image is selected, run a callback.
		file_frame.on( 'select', function() {
		  // We set multiple to false so only get one image from the uploader
		  attachment = file_frame.state().get('selection').first().toJSON();
		  jQuery('#lh_web_application-ios_icon_attachment_id').val(attachment.id);
		  jQuery('#lh_web_application-ios_icon-attachment_url').val(attachment.url);
		});
		// Finally, open the modal
		file_frame.open();
	});
	
		jQuery('#lh_web_application-ios_startup-upload_button').on('click', function( event ){
 
		event.preventDefault();
	 
		// If the media frame already exists, reopen it.
		if ( file_frame ) {
		  file_frame.open();
		  return;
		}
		// Create the media frame.
		file_frame = wp.media.frames.file_frame = wp.media({
		  title: 'Select an Image',
		  button: {
			text: 'Use this Image',
		  },
		  multiple: false  // only allow the one file to be selected
		});
		// When an image is selected, run a callback.
		file_frame.on( 'select', function() {
		  // We set multiple to false so only get one image from the uploader
		  attachment = file_frame.state().get('selection').first().toJSON();
		  jQuery('#lh_web_application-ios_startup_attachment_id').val(attachment.id);
		  jQuery('#lh_web_application-ios_startup-attachment_url').val(attachment.url);
		});
		// Finally, open the modal
		file_frame.open();
	});
	
});