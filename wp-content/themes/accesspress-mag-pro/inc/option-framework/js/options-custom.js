/**
 * Custom scripts needed for the colorpicker, image button selectors,
 * and navigation tabs.
 */

jQuery(document).ready(function($) {

	// Loads the color pickers
	$('.of-color').wpColorPicker();

	// Image Options
	$('.of-radio-img-img').click(function(){
		$(this).parent().parent().find('.of-radio-img-img').removeClass('of-radio-img-selected');
		$(this).addClass('of-radio-img-selected');
	});

	$('.of-radio-img-label').hide();
	$('.of-radio-img-img').show();
	$('.of-radio-img-radio').hide();

	// Loads tabbed sections if they exist
	if ( $('.nav-tab-wrapper').length > 0 ) {
		options_framework_tabs();
	}

	//Switch option
    $('.switch_options').each(function() {

        //This object
        var obj = $(this);

        var enb = obj.children('.switch_enable'); //cache first element, this is equal to ON
        var dsb = obj.children('.switch_disable'); //cache first element, this is equal to OFF
        var input = obj.children('input'); //cache the element where we must set the value
        var input_val = obj.children('input').val(); //cache the element where we must set the value

        /* Check selected */
        if (0 == input_val) {
            dsb.addClass('selected');
        }
        else if (1 == input_val) {
            enb.addClass('selected');
        }

        //Action on user's click(ON)
        enb.on('click', function() {
            $(dsb).removeClass('selected'); //remove "selected" from other elements in this object class(OFF)
            $(this).addClass('selected'); //add "selected" to the element which was just clicked in this object class(ON) 
            $(input).val(1).change(); //Finally change the value to 1
        });

        //Action on user's click(OFF)
        dsb.on('click', function() {
            $(enb).removeClass('selected'); //remove "selected" from other elements in this object class(ON)
            $(this).addClass('selected'); //add "selected" to the element which was just clicked in this object class(OFF) 
            $(input).val(0).change(); // //Finally change the value to 0
        });

    });
    
    function options_framework_tabs() {

		var $group = $('.group'),
			$navtabs = $('.nav-tab-wrapper a'),
			active_tab = '';

		// Hides all the .group sections to start
		$group.hide();

		// If active tab is saved and exists, load it's .group
		if ( active_tab != '' && $(active_tab).length ) {
			$(active_tab).fadeIn();
			$(active_tab + '-tab').addClass('nav-tab-active');
		} else {
			$('.group:first').fadeIn();
			$('.nav-tab-wrapper a:first').addClass('nav-tab-active');
		}

		// Bind tabs clicks
		$navtabs.click(function(e) {

			e.preventDefault();

			// Remove active class from all tabs
			$navtabs.removeClass('nav-tab-active');

			$(this).addClass('nav-tab-active').blur();


			var selected = $(this).attr('href');

			$group.hide();
			$(selected).fadeIn();

		});
	}
    
    $('#page_background_option').change(function() {
        var page_background_option_val = $(this).val();
        if (page_background_option_val == 'image') {
            $('#section-page_background_image').fadeIn();
            $('#section-page_background_pattern, #section-page_background_color').hide()
        } else if (page_background_option_val == 'color') {
            $('#section-page_background_color').fadeIn();
            $('#section-page_background_pattern, #section-page_background_image').hide()
        } else if (page_background_option_val == 'pattern') {
            $('#section-page_background_pattern').fadeIn();
            $('#section-page_background_color, #section-page_background_image').hide()
        } else {
            $('#section-page_background_color, #section-page_background_image, #section-page_background_pattern').hide()
        }
    }).change();
    
    $('#section-skin_color .radio input').click(function() {
        var Color = $(this).val();
        $('#theme_color').attr('value', Color);
        $('#section-theme_color .wp-color-result').css('background', Color);
    });

    $('#section-skin_color .radio').each(function() {
        var Color = $(this).find('input').val();
        $(this).css('background', Color);
    });
    
     $('.of-typography-face').on('change',function() {
        var font_family = $(this).val();
        //alert(font_family);
        var dis = $(this).attr('id');
        var dis_split = dis.split('_');
        $.ajax({
            url: ajaxurl,
            data: ({
                'action': 'accesspress_get_google_font_variants',
                'font_family':font_family,
            }),
            success: function(response) {
                $('#'+dis_split[0]+'_typography_style').html(response);
                var first_option = $('#'+dis_split[0]+'_typography_style option:first').html();
                $('#'+dis).parent('.select-wrapper').next().find('span').text(first_option);
            }
        });
    });
    
    jQuery('.ap_sliderui').each(function() {

        var obj = jQuery(this);
        var sId = "#" + obj.data('id');
        var val = obj.data('val');
        var min = obj.data('min');
        var max = obj.data('max');
        var step = obj.data('step');

        //slider init
        obj.slider({
            value: val,
            min: min,
            max: max,
            step: step,
            range: "min",
            slide: function(event, ui) {
                jQuery(sId).val(ui.value);
            }
        });

    });
	

});