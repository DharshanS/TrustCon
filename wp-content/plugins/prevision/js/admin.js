jQuery(function(){
    //jQuery(".wp3s_timepicker").datetimepicker();
	
	//jQuery( '.settings_page_wp3s_prevision_options > #ui-datepicker-div' ).css( 'height', 'auto' );
});

jQuery(document).ready(function($) {
	'use strict';
	
	var formfield;
	
	/**
	 * Initialize color picker
	 */
	if (typeof jQuery.wp === 'object' && typeof jQuery.wp.wpColorPicker === 'function') {		
		$('input:text.wp3s_colorpicker').wpColorPicker();
	} else {
		$('input:text.wp3s_colorpicker').each(function (i) {
			$(this).after('<div id="picker-' + i + '" style="z-index: 1000; background: #EEE; border: 1px solid #CCC; position: absolute; display: block;"></div>');
			$('#picker-' + i).hide().farbtastic($(this));
		})
		.focus(function () {
			$(this).next().show();
		})
		.blur(function () {
			$(this).next().hide();
		});
	}
	
	/**
	 * File and image upload handling
	 */
	$('.wp3s_upload_file').change(function () {
		formfield = $(this).attr('name');
		$('#' + formfield + '_id').val("");
	});

	$('.wp3s_upload_button').live('click', function () {
		var buttonLabel;
		formfield = $(this).prev('input').attr('name');
		buttonLabel = 'Use as ' + $('label[for=' + formfield + ']').text();
		tb_show('', 'media-upload.php?post_id=' + $('#post_ID').val() + '&type=file&cmb_force_send=true&cmb_send_label=' + buttonLabel + '&TB_iframe=true');
		return false;
	});

	$('.wp3s_remove_file_button').live('click', function () {
		formfield = $(this).attr('rel');
		$('input#' + formfield).val('');
		$('input#' + formfield + '_id').val('');
		$(this).parent().remove();
		return false;
	});

	window.original_send_to_editor = window.send_to_editor;
    window.send_to_editor = function (html) {
		var itemurl, itemclass, itemClassBits, itemid, htmlBits, itemtitle,
			image, uploadStatus = true;

		if (formfield) {

	        if ($(html).html(html).find('img').length > 0) {
				itemurl = $(html).html(html).find('img').attr('src'); // Use the URL to the size selected.
				itemclass = $(html).html(html).find('img').attr('class'); // Extract the ID from the returned class name.
				itemClassBits = itemclass.split(" ");
				itemid = itemClassBits[itemClassBits.length - 1];
				itemid = itemid.replace('wp-image-', '');
	        } else {
				// It's not an image. Get the URL to the file instead.
				htmlBits = html.split("'"); // jQuery seems to strip out XHTML when assigning the string to an object. Use alternate method.
				itemurl = htmlBits[1]; // Use the URL to the file.
				itemtitle = htmlBits[2];
				itemtitle = itemtitle.replace('>', '');
				itemtitle = itemtitle.replace('</a>', '');
				itemid = ""; // TO DO: Get ID for non-image attachments.
			}

			image = /(jpe?g|png|gif|ico)$/gi;

			if (itemurl.match(image)) {
				uploadStatus = '<div class="img_status"><img src="' + itemurl + '" alt="" /><a href="#" class="wp3s_remove_file_button" rel="' + formfield + '">Remove Image</a></div>';
			} else {
				// No output preview if it's not an image
				// Standard generic output if it's not an image.
				html = '<a href="' + itemurl + '" target="_blank" rel="external">View File</a>';
				uploadStatus = '<div class="no_image"><span class="file_link">' + html + '</span>&nbsp;&nbsp;&nbsp;<a href="#" class="wp3s_remove_file_button" rel="' + formfield + '">Remove</a></div>';
			}

			$('#' + formfield).val(itemurl);
			$('#' + formfield + '_id').val(itemid);
			$('#' + formfield).siblings('.wp3s_media_status').slideDown().html(uploadStatus);
			tb_remove();

		} else {
			window.original_send_to_editor(html);
		}

		formfield = '';
	};
});

jQuery(document).ready(function($) {
	// Live Font Preview
	$.each(['primary_font','secondary_font'], function(index, field) { 
	
		var section_element = $('#section-' + field + '.option');

		if ( section_element.length ) { // exists
			// Add preview text holder
			var font_preview_class = 'wp3s-prevision-font-preview-' + field;
			section_element.prepend('<p class="wp3s-prevision-font-preview ' + font_preview_class + '">The quick brown fox jumps over the lazy dog</p>');

			// On page load - show fonts already selected
			wp3s_prevision_options_font_preview_update(field);

			// On change or keydown...
			$('select[name="wp3s_prevision_' + field + '"]').bind('change keydown', function() {
				wp3s_prevision_options_font_preview_update(field);
			});

		}
			
	});
});

// Update font preview for specific field
function wp3s_prevision_options_font_preview_update( field ) {

	var font_select_element = jQuery('select[name="wp3s_prevision_' + field + '"]');
	var font_name = jQuery(':selected', font_select_element).val().split(',')[0];
	//jQuery(font_name).get(0).replace('"','');
	font_name = font_name.replace('"','').replace('"','');
		
	if (font_name) {
		
		// Make text invisible until font loads
		jQuery('.wp3s-prevision-font-preview-' + field).css('visibility', 'hidden');
		
		// Google WebFont Loader
		WebFont.load({
			google: {
				families: [font_name]
			},
			active: function() {
			
				// Set font on element after loaded, and show it
				jQuery('.wp3s-prevision-font-preview-' + field).css('font-family', "'" + font_name + "'").hide().css('visibility', 'visible').fadeIn('fast');

			},
		});

	}

}