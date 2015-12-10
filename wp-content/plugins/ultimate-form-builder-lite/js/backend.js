(function ($) {
    /**
     * Function to capture field type html
     * @param {string} field_type
     * @returns {string}
     */
    function get_field_html(field_type) {
        var form_key_count = $('.ufbl-form-key-count').val();
        form_key_count++;
        var field_key = 'ufbl_field_' + form_key_count;
        $('.ufbl-form-key-count').val(form_key_count);
        var html = $('.ufbl-' + field_type + '-reference').html();
        $('.ufbl-form-temp-holder').html(html);
        $('.ufbl-form-temp-holder input').each(function () {
            var name_attr = $(this).attr('name');
            if (name_attr) {
                name_attr = name_attr.replace('ufbl_key', field_key);
                $(this).attr('name', name_attr);
                //alert(name_attr);
            }



        });
        $('.ufbl-form-temp-holder select').each(function () {
            var name_attr = $(this).attr('name');
            if (name_attr) {
                name_attr = name_attr.replace('ufbl_key', field_key);
                $(this).attr('name', name_attr);
                //alert(name_attr);
            }



        });
        var html = $('.ufbl-form-temp-holder').html();
        $('.ufbl-form-temp-holder').html('');
        return html;

    }

    $(function () {
        /**
         * Add Form Popup Hide Show
         */
        $('.ufbl-add-form-trigger').click(function () {
            $('.ufbl-popup-wrap').fadeIn(400);
            $('.ufbl-form-title').focus();
        });

        $('.ufbl-overlay').click(function () {
            $('.ufbl-popup-wrap').fadeOut(200);
        });

        /**
         * Form ajax add
         */
        $('.ufbl-form-add-btn').click(function () {
            var selector = $(this);
            $.ajax({
                type: 'post',
                url: ufbl_ajax_obj.ajax_url,
                data: {_wpnonce: ufbl_ajax_obj.ajax_nonce,
                    action: 'add_form_action',
                    form_title: $('.ufbl-form-title').val()
                },
                beforeSend: function () {
                    selector.parent().find('.ufbl-ajax-loader').show();
                },
                success: function (res) {
                    $('.ufbl-ajax-loader').hide();
                    res = $.parseJSON(res);
                    if (res.success == 1) {
                        $('.ufbl-msg').show();
                        window.location = res.redirect_url;
                        return false;
                    } else {
                        $('.ufbl-add-error').html(res.error_msg).show();
                    }

                }
            });
        });

        /**
         * Form on and off toggle
         */
        $('.onoffswitch-label').click(function () {
            var selector = $(this);
            var form_id = $(this).data('form-id');
            if ($(this).parent().find('.onoffswitch-checkbox').is(':checked')) {
                var status = 0;
            } else {
                var status = 1;
            }
            $.ajax({
                type: 'post',
                url: ufbl_ajax_obj.ajax_url,
                data: {
                    _wpnonce: ufbl_ajax_obj.ajax_nonce,
                    status: status,
                    form_id: form_id,
                    action: 'ufbl_form_status_action'
                },
                beforeSend: function () {
                    selector.closest('.shortcode').find('.ufbl-ajax-loader').show();
                },
                success: function (res) {
                    selector.closest('.shortcode').find('.ufbl-ajax-loader').hide();
                    selector.closest('.shortcode').find('.ufbl-status-message').html(res).show().fadeOut(3500);

                }

            });
        });

        /**
         * Tabs Trigger show hide
         */
        $('.ufbl-tab-trigger').click(function () {
            var attr_id = $(this).data('id');
            $('.ufbl-tab-trigger').removeClass('nav-tab-active');
            $(this).addClass('nav-tab-active');
            $('.ufbl-tab-content').hide();
            $('#ufbl-' + attr_id + '-tab').show();
        });

        /**
         * Form Title edit trigger
         */
        $('.ufbl-form-title').click(function () {
            if ($('.ufbl-form-title #ufbl-form-title').length == 0) {
                var form_title = $(this).html();
                $(this).html('<input type="text" id="ufbl-form-title" value="' + form_title + '"/>');
                $('.ufbl-form-title #ufbl-form-title').focus();
            }

        });

        $('body').on('blur', '.ufbl-form-title #ufbl-form-title', function () {
            var form_title = $(this).val();
            form_title = (form_title == '') ? 'Untitled Form' : form_title;
            $('.ufbl-form-title').html(form_title);
            $('.ufbl-form-title-field').val(form_title);
        });

        /**
         * Form Builder functionality
         */
        $('.ufbl-form-element').click(function () {
            var field_type = $(this).data('field-type');
            var field_html = get_field_html(field_type);
            //field_html = '<div class="ufbl-each-form-field">' + field_html + '<div class="ufbl-field-controls"><a href="javascript:void(0)" class="ufbl-field-settings-trigger button-primary">Settings</a><a href="javascript:void(0)" class="ufbl-field-delete-trigger" onclick="return confirm(\'If you delete this element then data related with this element will also be deleted. Are you sure you want to delete this element?\')">Delete</a></div></div>';
            $('.ufbl-form-field-holder').append(field_html);

        });

        /**
         * Form Element Delete
         */
        $('body').on('click', '.ufbl-field-delete-trigger', function () {
            if (confirm('If you delete this element then data related with this element will also be deleted. Are you sure you want to delete this element?')) {
                $(this).closest('.ufbl-each-form-field').fadeOut(500, function () {
                    $(this).remove();
                });
            }

        });

        $('.ufbl-form-field-holder').sortable({
            handle: '.ufbl-drag-arrow'
        });
        $('.ufbl-option-value-wrap').sortable({
            containment: "parent",
            handle: '.ufbl-option-drag-arrow'
        });

        $('body').on('click', '.ufbl-field-settings-trigger', function () {
            if ($(this).next('span').html() == '+') {
                var selector = $(this);
                $(this).closest('.ufbl-each-form-field').find('.ufbl-field-settings-wrap').slideDown(500, function () {

                    selector.next('span').html('-');
                });
            } else {
                var selector = $(this);
                $(this).closest('.ufbl-each-form-field').find('.ufbl-field-settings-wrap').slideUp(500, function () {
                    selector.next('span').html('+');

                });
            }

        });

        $('body').on('keyup', '.ufbl-field-label-field', function () {
            var label_text = $(this).val();
            label_text = (label_text != '') ? label_text : 'Untitled Field';
            $(this).closest('.ufbl-each-form-field').find('.ufbl-field-label-ref').html(label_text);
        });
        $('body').on('keyup', '.ufbl-submit-button', function () {
            var label_text = $(this).val();
            label_text = (label_text != '') ? label_text : 'Submit';
            $(this).closest('.ufbl-each-form-field').find('.ufbl-submit-reference').val(label_text);
        });

        $('body').on('click', '.ufbl-option-remover', function () {
            $(this).closest('.ufbl-each-option').fadeOut(500, function () {
                $(this).remove();
            });
        });

        /**
         * Add Option for radio button, checkbox, dropdown
         */
        $('body').on('click', '.ufbl-option-value-adder', function () {
            var html = $(this).closest('.ufbl-form-field').find('.ufbl-each-option').first().html();
            html = '<div class="ufbl-each-option" style="display:none;">' + html + '</div>';
            $(this).closest('.ufbl-form-field').find('.ufbl-option-value-wrap').append(html);
            $(this).closest('.ufbl-form-field').find('.ufbl-option-value-wrap').find('.ufbl-each-option').last().find('input[type="text"]').val('');
            $('.ufbl-each-option').show();
            $(this).closest('.ufbl-form-field').find('.ufbl-option-value-wrap').find('.ufbl-each-option').last().find('input[type="text"]').first().focus();
        });



        /**
         * Form Post
         */
        $('.ufbl-save-form').click(function () {
            $('.ufbl-form').submit();
        });

        $('.ufbl-message button').click(function () {
            $(this).parent().remove();
        });

        /**
         * Email Reciever add trigger
         */
        $('.ufbl-email-adder').click(function () {
            var html = '<div class="ufbl-email-fields"><input type="text" name="email_settings[email_reciever][]" placeholder="test@abc.com"/><span class="ufbl-email-remove">X</span></div>';
            $(this).parent().append(html);
            $('.ufbl-email-fields').last().find('input').focus();
        });

        $('body').on('click', '.ufbl-email-remove', function () {
            $(this).parent().fadeOut(300, function () {
                $(this).remove();
            });
        });

        $('.ufbl-delete').click(function () {
            $(this).parent().find('.ufbl-delete-confirmation').slideToggle(500);
        });
        $('.ufbl-delete-cancel').click(function () {
            $(this).parent().slideUp(500);
        });

        /* $('.row-actions').mouseleave(function () {
         $(this).find('.ufbl-delete-confirmation').slideUp(500);
         });
         */
        $('.ufbl-form-delete-yes').click(function () {
            var form_id = $(this).data('form-id');
            var selector = $(this);
            $.ajax({
                url: ufbl_ajax_obj.ajax_url,
                type: 'post',
                data: {
                    form_id: form_id,
                    _wpnonce: ufbl_ajax_obj.ajax_nonce,
                    action: 'ufbl_form_delete_action'
                },
                beforeSend: function () {
                    selector.parent().find('.ufbl-ajax-loader').show();
                },
                success: function (res) {
                    if (res == 'success') {
                        selector.closest('tr').fadeOut(500, function () {
                            $(this).remove();
                        });
                        console.log(res);
                    } else {
                        alert(res);
                    }



                }
            });
        });

        $('.ufbl-add-form-wrap .ufbl-form-title').keypress(function (e) {
            if (e.which == 13) {
                $(this).closest('.ufbl-add-form-wrap').find('.ufbl-form-add-btn').click();
            }
        });
        $('.ufbl-new-form-wrap .ufbl-form-title').keypress(function (e) {
            if (e.which == 13) {
                $(this).closest('.ufbl-new-form-wrap').find('.ufbl-form-add-btn').click();
            }
        });
        $('.ufbl-copy-popup-wrap .ufbl-form-title').keypress(function (e) {
            if (e.which == 13) {
                $(this).closest('.ufbl-add-form-wrap').find('.ufbl-form-copy-btn').click();
            }
        });


        /**
         * Delete Entry 
         */
        $('.ufbl-delete-entry-yes').click(function () {
            var entry_id = $(this).data('entry-id');
            var selector = $(this);
            $.ajax({
                url: ufbl_ajax_obj.ajax_url,
                type: 'post',
                data: {
                    entry_id: entry_id,
                    _wpnonce: ufbl_ajax_obj.ajax_nonce,
                    action: 'ufbl_entry_delete_action'
                },
                beforeSend: function () {
                    selector.parent().find('.ufbl-ajax-loader').show();
                },
                success: function (res) {
                    selector.parent().find('.ufbl-ajax-loader').hide();
                    if (res == 'success') {
                        selector.closest('tr').fadeOut(500, function () {
                            $(this).remove();
                        });
                        console.log(res);
                    } else {
                        alert(res);
                    }



                }
            });
        });

        /**
         * View Entry Popup
         */
        $('.ufbl-view-entry > a').click(function () {
            var entry_id = $(this).data('entry-id');
            $.ajax({
                url: ufbl_ajax_obj.ajax_url,
                data: {
                    entry_id: entry_id,
                    _wpnonce: ufbl_ajax_obj.ajax_nonce,
                    action: 'ufbl_get_entry_detail_action'
                },
                type: 'post',
                beforeSend: function () {
                    $('.ufbl-entry-overlay').fadeIn(300, function () {
                        $('.ufbl-entry-wrap').show();
                    });
                },
                success: function (res) {
                    height = $(window).height();
                    var entry_inner_wrap_height = 0.70 * height;
                    $('.ufbl-entry-wrap').html(res);
                    $('.ufbl-entry-wrap .ufbl-entry-inner-wrap').height(entry_inner_wrap_height);
                }
            });

        });

        /**
         * Entry Popup Close
         */
        $('body').on('click', '.ufbl-entry-overlay,.ufbl-entry-detail-close', function () {
            $('.ufbl-entry-overlay').fadeOut(300, function () {
                $('.ufbl-entry-wrap').html('<span class="ufbl-entry-ajax-loader"></span>');

            });
            $('.ufbl-entry-wrap').fadeOut(300);


        });

        /**
         * Entry Filter 
         */
        $('.ufbl-entry-filter-select').change(function () {
            var form_id = $(this).val();
            var admin_url = $(this).data('admin-url');
            var redirect_url = (form_id == '') ? admin_url + 'admin.php?page=ufbl-form-entries' : admin_url + 'admin.php?page=ufbl-form-entries&form_id=' + form_id;
            window.location = redirect_url;
            return false;
        });

        /**
         * Form copy popup open
         */
        $('body').on('click', '.ufbl-copy', function () {
            var form_id = $(this).data('form-id');
            $('.ufbl-copy-form-id option[value="' + form_id + '"]').attr('selected', 'selected');
            $('.ufbl-copy-popup-wrap').fadeIn(300);
            $('.ufbl-copy-popup-wrap .ufbl-form-title').focus();
        });

        $('.ufbl-overlay').click(function () {
            $('.ufbl-copy-popup-wrap').fadeOut(300);
        });

        /**
         * Form Copy 
         */
        $('.ufbl-form-copy-btn').click(function () {
            var selector = $(this);
            $.ajax({
                type: 'post',
                url: ufbl_ajax_obj.ajax_url,
                data: {_wpnonce: ufbl_ajax_obj.ajax_nonce,
                    action: 'copy_form_action',
                    form_title: selector.closest('.ufbl-copy-popup-wrap').find('.ufbl-form-title').val(),
                    form_id: selector.closest('.ufbl-copy-popup-wrap').find('.ufbl-copy-form-id').val()
                },
                beforeSend: function () {
                    selector.closest('.ufbl-add-form-wrap').find('.ufbl-ajax-loader').show();
                },
                success: function (res) {
                    $('.ufbl-ajax-loader').hide();
                    res = $.parseJSON(res);
                    if (res.success == 1) {
                        $('.ufbl-msg').show();
                        window.location = res.redirect_url;
                        return false;
                    } else {
                        $('.ufbl-add-error').html(res.error_msg).show();
                    }

                }
            });
        });

        /**
         * Captcha Type Dropdown on Change
         */
        $('body').on('change', '.ufbl-captcha-type-dropdown', function () {
            var captcha_type = $(this).val();
            if (captcha_type == 'google') {
                $(this).closest('.ufbl-field-settings-wrap').find('.ufbl-captcha-field-ref').show();
            } else {
                $(this).closest('.ufbl-field-settings-wrap').find('.ufbl-captcha-field-ref').hide();

            }
        });

        /**
         * Backend template change
         */
        $('.ufbl-form-template-dropdown').change(function () {
            var template_name = $(this).val();
            $('.ufbl-template-preview img').hide();
            $('.ufbl-template-preview #preview-' + template_name).show();
        });

        /**
         * Page Leave Message
         */
        $(".ufbl-form :input").change(function () {
            $('.ufbl-form').data("changed", true);
        });
       
        $(window).bind('beforeunload',function(){
            if ($('.ufbl-form').data('changed') == true) {

                return 'The changes you made will be lost if you navigate away from this page.';
            }
        })
        
        $(".ufbl-form").submit(function () {
            $(window).unbind("beforeunload");
        });


    });//document.ready close
}(jQuery));

