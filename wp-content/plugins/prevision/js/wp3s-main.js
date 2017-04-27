var wp3s_prevision_trigger = false;

function initializePrevision() {	
	wp3s_prevision_trigger = true;	

	//jQuery(".ccounter").ccountdown(2014,03,27,'14:40:37');
	//alert(wp3s_prevision.date_year + "," + wp3s_prevision.date_month + "," + wp3s_prevision.date_day +", '" + wp3s_prevision.date_hour + ":" + wp3s_prevision.date_minute + ":" + wp3s_prevision.date_second + "'");
	jQuery(".ccounter").ccountdown(wp3s_prevision.date_year, wp3s_prevision.date_month, wp3s_prevision.date_day, wp3s_prevision.date_hour + ":" + wp3s_prevision.date_minute + ":" + wp3s_prevision.date_second);

	if (jQuery('.wp3s_prevision #slides').length != 0) {
		jQuery('.wp3s_prevision .flexslider').flexslider({
			animation: "fade",
			smoothHeight: false,
			useCSS: true,
			touch: false,
			controlNav: false,
			directionNav: false,
			slideshowSpeed: 5000,
			animationSpeed: 800
		});
	}
	
	// MAIN DIMENSION SET WIDTH
	(function() {
		function mainInit() {			
			var main = jQuery('.wp3s_prevision #main'),
			ww = jQuery(window).width(),
			mainWidth = ww-250;
			
			if (ww > 900) {
				main.css({
					width: mainWidth+"px"
				});
			}
			
			// center content
			var mainContent = jQuery('.wp3s_prevision .content .content-inner'),
				contentHeight = mainContent.height(),
				parentHeight = main.height(),
				topMargin = (parentHeight - contentHeight) / 2;
				
			mainContent.css({
				"padding-top" : topMargin+"px"
			});
		}
		
		jQuery(window).on("resize", mainInit);
		jQuery(document).on("ready", mainInit);
	})();
	
	// QUOTES
	//jQuery('.wp3s_prevision .bx-prev a, .wp3s_prevision .bx-next a').preventDefault();
	if (jQuery('.wp3s_prevision .bxslider li').length < 2) {
	} else {
		jQuery('.wp3s_prevision .bxslider').bxSlider({
			auto: false,	
			touchEnabled: true,
			oneToOneTouch: true,
			nextSelector: '#bx-next',
			prevSelector: '#bx-prev',
			nextText: 'next',
			prevText: 'prev',
			pager: false,
			speed: 650,
			pause: 8500
		});
	}
		
	var formShowing = false;
	
	jQuery('.wp3s_prevision a.contact-us-trigger, .wp3s_prevision a.menu-close').click(function(e) {
		e.preventDefault();
		if (formShowing == false) {
			jQuery('.wp3s_prevision #contact-us').animate({
				marginLeft: 250
			}, 300);
			formShowing = true;
		} else {
			jQuery('.wp3s_prevision #contact-us').animate({
				marginLeft: -690
			}, 300);
			formShowing = false;
		}
	});
	
	jQuery('.wp3s_prevision #contactform').validate({
		rules: {
			name: {
				required: true,
				minlength: 2
			},
			email: {
				required: true,
				email: true
			},
			message: {
				required: true,
				minlength: 20
			}
		},
		messages: {
			author: "Please enter your name.",
			email: "Please enter a valid email address.",
			comment: "Message box cannot be empty!"
		},
		invalidHandler: function(event, validator) {
			var errors = validator.numberOfInvalids();
			if (errors) {
				jQuery('#contactFormError').show();
			} else {
				jQuery('#contactFormError').hide();
			}
		},
		errorElement: "div",
		errorPlacement: function(error,element) {},
		submitHandler: function(form) {
			jQuery.post(wp3s_prevision.plugin_uri + '/inc/sendmail.php', jQuery('.wp3s_prevision #contactform').serialize(), function(response) {
				if (response.indexOf("thanks") >= 0) {
					jQuery('.wp3s_prevision #contactFormSent').show();
					jQuery('.wp3s_prevision #contactFormError').hide();
					jQuery('.wp3s_prevision #contactFormError2').hide();
				} else if (response.indexOf("senderror") >= 0) {
					jQuery('.wp3s_prevision #contactFormError2').show();
					jQuery('.wp3s_prevision #contactFormError').hide();
				} else {
					jQuery('.wp3s_prevision #contactFormError').show();
				}
			});
			return false;
			}
	});
}

// Document Ready
jQuery(document).ready(function($){
	wp3s_prevision_trigger = true;
	initializePrevision();	
	jQuery(window).on("resize", initializePrevision());
	jQuery('.wp3s_prevision #countdown_container input').show();
});

if (wp3s_prevision_trigger == false) {
	//setTimeout(initializePrevision, 800);
}