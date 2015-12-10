SPUP_ADMIN = (function ( $ ) {

    var spu_editor = '';
	$(document).ready(function(){

        $('.spu_reset_stats').on('click', function(){
            return confirm( spuvar.l18n.reset_stats );
        })

		//Toogle trigger boxes on init
		checkTriggerMethodP( $("#spu_trigger").val() );
		
		//Toogle trigger boxes on change
		$("#spu_trigger").change(function(){
			checkTriggerMethodP( $(this).val() );
		})

        /**
         * Ajax to retrieve optin lists
         */
        $('#spu_optin').change(function(){
            var spinner = $('.optin-spinner'),
                _this   = $(this);

            if( _this.val() == '' ){
                $('.optin_opts').removeClass('visible');
                remove_optin_form();
            } else if ( _this.val() == 'custom')  {
                $('.optin_opts').removeClass('visible');
                $('.optin_theme').addClass('visible');
                remove_optin_form();
            } else {
                add_optin_form();
                $('.optin_opts').addClass('visible').fadeIn();
                spinner.fadeIn();
                _this.prop("disabled", true);
                $.ajax({
                    method: "POST",
                    url: ajaxurl,
                    data: {action: "spu_get_optin_lists", optin: _this.val()}
                }).done(function (data) {
                    spinner.fadeOut();
                    _this.prop('disabled', false);
                    if (data) {
                        $('#spu_optin_list').html(data);
                        $('.optin_list').fadeIn();
                    } else {
                        $('#spu_optin_list').html('');
                    }
                });
            }
        });

        /**
         * Function to retrieve list groups
         */
        $('#spu_optin_list').change(function(){
            var spinner = $('.optin-spinner'),
                _this   = $(this);
            spinner.fadeIn();
            _this.prop( "disabled", true );
            $.ajax({
                method: "POST",
                url: ajaxurl,
                data: { action: "spu_get_optin_list_segments", list: _this.val(), optin : $('#spu_optin').val() }
            }).done(function( data ) {
                spinner.fadeOut();
                _this.prop('disabled',false);
                if( data ) {
                    $('.optin_list_segments .result').html(data);
                    $('.optin_list_segments').fadeIn();
                } else {
                    $('.optin_list_segments .result').html('');
                    $('.optin_list_segments').fadeOut();
                }
            });

        });

        /**
         * Update optin name field
         */
        $("#spu_optin_display_name").change(function(){
            var $editor = SPUP_ADMIN.spu_editor,
                name_field = '<input type="text" class="spu-fields spu-name" placeholder="'+spup_js.opts.optin_name_placeholder+'" name="spu-name"/>';
            $editor.toggleClass('with-spu-name');
            if( $(this).val() == '1') {
                $editor.find('#spu-optin-form').prepend(name_field);
            } else {
                $editor.find('.spu-fields.spu-name').remove();
            }
        });

        /**
         * Update placeholders & texts
         */
        $("#spu_optin_placeholder").blur(function () {
            var $editor = SPUP_ADMIN.spu_editor,
                placeholder = $(this).val();

            $editor.find('.spu-email').prop('placeholder',placeholder);
        });
        $("#spu_optin_name_placeholder").blur(function () {
            var $editor = SPUP_ADMIN.spu_editor,
                placeholder = $(this).val();

            $editor.find('.spu-name').prop('placeholder',placeholder);
        });
        $("#spu_optin_submit").blur(function () {
            var $editor = SPUP_ADMIN.spu_editor,
                text = $(this).val();

            $editor.find('.spu-submit').text(text);
        });

        /**
         * Update optin theme
         */
        $('.optin_themes .theme').on('click', function(){
            var $editor =SPUP_ADMIN.spu_editor,
                new_theme = $(this).data('theme');
            $('.optin_themes .theme').removeClass('selected');
            $(this).addClass('selected');
            $('#spu_optin_theme').val(new_theme);

            //update editor
            $editor.alterClass('spu-theme-*', 'spu-theme-'+ new_theme )
        });

        /**
         * Integrations page
         */
        $('.toggle-provider').click(function(e){
            e.preventDefault();
            $(this).closest('.collapse-div').toggleClass('active');
        });
	});


	function checkTriggerMethodP( val ){
		if( val == 'trigger-click' || val == 'visible' ) {

			$(".spu-trigger-number").hide();
			$(".spu-trigger-value").fadeIn();

		} else if( val == 'exit-intent') {
			$(".spu-trigger-number").hide();
			$(".spu-trigger-value").hide();
            
            } else {

			$(".spu-trigger-value").hide();
			$(".spu-trigger-number").fadeIn();

		}
	}

    /**
     * When tinyMcr loads
     */
    function TinyMceOptin() {
        SPUP_ADMIN.spu_editor = $("#content_ifr").contents().find('html #tinymce');
        var spu_box_container = false;
        // If there is not content add some
        var content = SPUP_ADMIN.spu_editor.text();
        if( SPUP_ADMIN.spu_editor.find('.spu-box-container').length) {
            spu_box_container = true;
            content = SPUP_ADMIN.spu_editor.html();
        }

        if( content == '' ){
            SPUP_ADMIN.spu_editor.html('<h2 style="text-align:center">Support us!</h2><p style="text-align:center">Subscribe to get the latest offers and deals!</p>')
        }
        //Add popup class if not exist
        if( !spu_box_container ) {
            SPUP_ADMIN.spu_editor.find('*').wrapAll('<div class="spu-box-container"/>');
        }
        // If we are using optin we need to add email field to form
        if (spup_js.opts.optin && spup_js.opts.optin != 'custom') {
            add_optin_form();
        }
    }

    function add_optin_form(){
        var $editor = SPUP_ADMIN.spu_editor,
            email_field = '<input type="email" name="spu-email" class="spu-fields spu-email" placeholder="'+$("#spu_optin_placeholder").val()+'"/>',
            name_field = '<input type="text" name="spu-name" class="spu-fields spu-name" placeholder="'+$("#spu_optin_name_placeholder").val()+'"/>',
            submit_btn = '<button type="submit" class="spu-fields spu-submit">'+$("#spu_optin_submit").val()+'<i class="spu-icon-spinner spu-spinner"></i></button>',
            $html = '<div class="spu-fields-container">' +
                '<form id="spu-optin-form" class="spu-optin-form" action="" method="post">' +
                '<input type="text" name="email" class="spu-helper-fields"/>' +
                '<input type="text" name="web" class="spu-helper-fields"/>';


        $editor.addClass('spu-optin-editor spu-theme-' + spup_js.opts.optin_theme ).removeClass('wp-autoresize');
        $editor.find(".spu-fields-container").remove();

        $('.spu-appearance').hide();

        if (spup_js.opts.optin_display_name == '1') {
            $html += name_field;
            $editor.addClass('with-spu-name');
        }
        $html += email_field;
        $html += submit_btn;
        $html += '</form></div>';

        $($html).appendTo($editor.find('.spu-box-container'));
    }

    function remove_optin_form(){
        var $editor = $("#content_ifr").contents().find('html #tinymce');
        $editor.find(".spu-fields-container").remove();
        $editor.alterClass('spu-theme-*', '');
    }
    return {
        onTinyMceInit: function() {
            TinyMceOptin();
        }
    }
	
}(jQuery));
/**
 * jQuery alterClass plugin
 *
 * Remove element classes with wildcard matching. Optionally add classes:
 *   $( '#foo' ).alterClass( 'foo-* bar-*', 'foobar' )
 *
 * Copyright (c) 2011 Pete Boere (the-echoplex.net)
 * Free under terms of the MIT license: http://www.opensource.org/licenses/mit-license.php
 *
 */
(function ( $ ) {

    $.fn.alterClass = function ( removals, additions ) {

        var self = this;

        if ( removals.indexOf( '*' ) === -1 ) {
            // Use native jQuery methods if there is no wildcard matching
            self.removeClass( removals );
            return !additions ? self : self.addClass( additions );
        }

        var patt = new RegExp( '\\s' +
        removals.
            replace( /\*/g, '[A-Za-z0-9-_]+' ).
            split( ' ' ).
            join( '\\s|\\s' ) +
        '\\s', 'g' );

        self.each( function ( i, it ) {
            var cn = ' ' + it.className + ' ';
            while ( patt.test( cn ) ) {
                cn = cn.replace( patt, ' ' );
            }
            it.className = $.trim( cn );
        });

        return !additions ? self : self.addClass( additions );
    };

})( jQuery );
