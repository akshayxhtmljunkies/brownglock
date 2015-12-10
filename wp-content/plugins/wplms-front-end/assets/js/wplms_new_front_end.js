
jQuery(document).ready(function($){
    
    $('.wplms-taxonomy select').change(function(event){
        var new_tax = $(this).parent().parent().find('.wplms-new-taxonomy');
        if($(this).val() === 'new'){
            new_tax.addClass('animate cssanim fadeIn load');
        }else{
            new_tax.removeClass('animate cssanim fadeIn load');
        }
    });


    $('.select2').each(function(){
        if($(this).hasClass('select2-hidden-accessible'))
            return;
         if(!$(this).hasClass('selectcpt'))
            $(this).select2();
    });

    $('.vibe_vibe_group h3>span,.vibe_vibe_forum h3>span').click(function(){
        $(this).parent().next().toggle(200);
    });
    $('.toggle_vibe_post_content').click(function(){
        $('.vibe_post_content').toggle(200);
    });
    $('.vibe_vibe_group .more').click(function(){
        $('.select_group_form,.new_group_form').hide();
        $(this).next().toggle(200);
    });
    $('.vibe_vibe_forum .more').click(function(){
        $('.select_forum_form,.new_forum_form').hide();
        $(this).next().toggle(200);
    });
    
    $('.vibe_vibe_product h3>span').click(function(){
        var pclass = $(this).attr('class');
        $('#edit_product,#change_product').hide(100);
        $('#'+pclass).toggle(200);
    });

    $('.vibe_vibe_product .more').click(function(){
        $('.select_product_form,.new_product_form').hide();
        $(this).next().toggle(200);
    });

    $('.clear_input').on('click',function(){
        var val = $(this).attr('data-id');
        if($('#'+val).length){
            $('#'+val).val('');
            $(this).next().html($(this).find('.hide').html());
            $('.course_components').trigger('active');
            $('.course_pricing').trigger('reactive');
        }
    });
    $('.course_components').on('active',function(){ 
        $('.vibe_vibe_group h3>span,.vibe_vibe_forum h3>span').unbind('click');
        $('.vibe_vibe_group h3>span,.vibe_vibe_forum h3>span').click(function(){
            $(this).parent().next().toggle(200);
        });

        $('.vibe_vibe_group .more').unbind('click');
        $('.vibe_vibe_group .more').click(function(){
            $('.select_group_form,.new_group_form').hide();
            $(this).next().toggle(200);
        });
        $('.vibe_vibe_forum .more').unbind('click');
        $('.vibe_vibe_forum .more').click(function(){
            $('.select_forum_form,.new_forum_form').hide();
            $(this).next().toggle(200);
        });
        $('.clear_input').on('click',function(){
            var val = $(this).attr('data-id');
            if($('#'+val).length){
                $('#'+val).val('');
                $(this).next().html($(this).find('.hide').html());
                $('.course_components').trigger('active');
            }
        });
    });
    $('.course_pricing').on('active',function(){
        $.ajax({
                type: "POST",
                url: ajaxurl,
                data: { action: 'get_product', 
                        security: $('#security').val(),
                        course_id:$('#course_id').val(),
                      },
                cache: false,
                success: function (html) {
                    $('#edit_product').html(html);
                }
        });

        $('.vibe_vibe_product h3>span').unbind('click');
        $('.vibe_vibe_product h3>span').click(function(){
            var pclass = $(this).attr('class');
            $('#edit_product,#change_product').hide(100);
            $('#'+pclass).toggle(200);
        });
        $('.vibe_vibe_product .more').unbind('click');
        $('.vibe_vibe_product .more').click(function(){
            $('.select_product_form,.new_product_form').hide();
            $(this).next().toggle(200);
        });
        $('.clear_input').on('click',function(){
            var val = $(this).attr('data-id');
            if($('#'+val).length){
                $('#'+val).val('');
                $(this).next().html($(this).find('.hide').html());
                $('.course_pricing').trigger('reactive');
            }
        });
    });
    $('.course_pricing').on('reactive',function(){
         $('.vibe_vibe_product h3>span').unbind('click');
        $('.vibe_vibe_product h3>span').click(function(){ 
            var pclass = $(this).attr('class');
            $('#edit_product,#change_product').hide(100);
            $('#'+pclass).toggle(200);
        });
        $('.vibe_vibe_product .more').unbind('click');
        $('.vibe_vibe_product .more').click(function(){
            $('.select_product_form,.new_product_form').hide();
            $(this).next().toggle(200);
        });
    });
    $('#course_creation_tabs').on('increment',function(){
        var active = $(this).find('li.active');
        active.removeClass('active');
        active.removeClass('done');
        var id = active.attr('class');
        active.addClass('done');
        $('#'+id).removeClass('active');
        var nextid = active.next().attr('class');
        $('#'+nextid).addClass('active');
        $('#'+nextid).trigger('active');
        active.next().addClass('active');
        $('body,html').animate({
            scrollTop: 0
          }, 1200);
        $('#'+nextid).find( '.wp-editor-area' ).each(function() {
            var id = jQuery( this ).attr( 'id' ),
                sel = '#wp-' + id + '-wrap',
                container = jQuery( sel ),
                editor = tinyMCE.get( id );
            if ( editor && container.hasClass( 'tmce-active' ) ) {
                editor.save();
            }
        });
    });


    $('input[data-id="post_title"]').on('blur',function(){
        $('#create_course_button').removeClass('disabled');
    });
    /* === Create Course Ajax === */
    $('#create_course_button').on('click',function(){

        var $this = $(this);
        var defaulttxt = $this.html();

        var $title = $('input[data-id="post_title"]').val();
        if($title.length < 1){
            $this.addClass('disabled');
        }
        if($this.hasClass('disabled'))
            return;

        $this.addClass('disabled');

        tinyMCE.triggerSave();

        $.confirm({
          text: wplms_front_end_messages.save_course_confirm,
          confirm: function() {
            var settings = [];

            $('#create_course').each(function() {
                $(this).find('.post_field').each(function(){
                    if($(this).is(':checkbox')){
                        $(this).is(':checked').each(function(){
                            var data = {id:$(this).attr('data-id'),type: $(this).attr('data-type'),value: $(this).val()};
                        });
                    }
                    if($(this).is(':radio')){
                        var radio_class = $(this).attr('class');
                        $('.'+radio_class+':checked').each(function(){
                            var data = {id:$(this).attr('data-id'),type: $(this).attr('data-type'),value: $(this).val()};
                        });
                    }
                    if($(this).is('select')){
                        var data = {id:$(this).attr('data-id'),type: $(this).attr('data-type'),value: $(this).val()};
                    }
                    if($(this).is('input')){
                        var data = {id:$(this).attr('data-id'),type: $(this).attr('data-type'),value: $(this).val()};
                    }
                    if($(this).is('textarea')){
                       var data = {id:$(this).attr('id'),type: $(this).attr('data-type'),value: $(this).val()};   
                    }
                    settings.push(data);
                });
            });
  
             $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: { action: 'new_create_course', 
                            security: $('#security').val(),
                            settings:JSON.stringify(settings)    
                          },
                    cache: false,
                    success: function (html) {
                   
                        $this.removeClass('disabled');
                        if($.isNumeric(html)){
                            $('#course_id').val(html);
                            $('#course_creation_tabs>ul').addClass('islive');
                            $('#course_creation_tabs').trigger('increment');
                        }else{
                            $this.html(html);
                            setTimeout(function(){$this.html(defaulttxt);}, 5000);
                        }
                    }
            });
          },
          cancel: function() {
              $this.removeClass('disabled');
          },
          confirmButton: wplms_front_end_messages.save_course_confirm_button,
          cancelButton: vibe_course_module_strings.cancel
      });

    });
    /* === Save Course Ajax ===*/
    $('#save_course_button').on('click',function(){

        var $this = $(this);
        var defaulttxt = $this.html();
        $this.addClass('disabled');

        tinyMCE.triggerSave();

        $.confirm({
          text: wplms_front_end_messages.save_course_confirm,
          confirm: function() {
            var settings = [];

            $('#create_course').each(function() {
                $(this).find('.post_field').each(function(){
                    if($(this).is(':checkbox')){
                        $(this).is(':checked').each(function(){
                            var data = {id:$(this).attr('data-id'),type: $(this).attr('data-type'),value: $(this).val()};
                        });
                    }
                    if($(this).is(':radio')){
                        var radio_class = $(this).attr('class');
                        $('.'+radio_class+':checked').each(function(){
                            var data = {id:$(this).attr('data-id'),type: $(this).attr('data-type'),value: $(this).val()};
                        });
                    }
                    if($(this).is('select')){
                        var data = {id:$(this).attr('data-id'),type: $(this).attr('data-type'),value: $(this).val()};
                    }
                    if($(this).is('input')){
                        var data = {id:$(this).attr('data-id'),type: $(this).attr('data-type'),value: $(this).val()};
                    }
                    if($(this).is('textarea')){
                        if($(this).hasClass('wp-editor-area')){
                            var data = {id:$(this).attr('id'),type: $(this).attr('name'),value: $(this).val()};  
                        }else{
                            var data = {id:$(this).attr('id'),type: $(this).attr('data-type'),value: $(this).val()};        
                        }
                    }
                    settings.push(data);
                });
            });
  
             $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: { action: 'new_save_course', 
                            security: $('#security').val(),
                            course_id:$('#course_id').val(),
                            settings:JSON.stringify(settings)    
                          },
                    cache: false,
                    success: function (html) {
                   
                        $this.removeClass('disabled');
                        if($.isNumeric(html)){
                            $('#course_creation_tabs').trigger('increment');
                        }else{
                            $this.html(html);
                            setTimeout(function(){$this.html(defaulttxt);}, 5000);
                        }
                    }
            });
          },
          cancel: function() {
              $this.removeClass('disabled');
          },
          confirmButton: wplms_front_end_messages.save_course_confirm_button,
          cancelButton: vibe_course_module_strings.cancel
      });

    });
    
    /* === Save Course Settings Ajax ====*/
    $('#save_course_settings_button').on('click',function(){

        var $this = $(this);
        var defaulttxt = $this.html();
        $this.addClass('disabled');

        var settings = [];

        $('#course_settings').find('.post_field').each(function() {
                
                if($(this).is(':checkbox:checked')){
                    var data = {id:$(this).attr('data-id'),type: $(this).attr('data-type'),value: $(this).val()};
                }
                if($(this).is(':radio:checked')){ 
                    var data = {id:$(this).attr('data-id'),type: $(this).attr('data-type'),value: $(this).val()};
                }
                if($(this).is('select')){
                    var data = {id:$(this).attr('data-id'),type: $(this).attr('data-type'),value: $(this).val()};
                }
                if($(this).is('input[type="text"]')){
                    var data = {id:$(this).attr('data-id'),type: $(this).attr('data-type'),value: $(this).val()};
                }
                if($(this).is('input[type="hidden"]')){
                    var data = {id:$(this).attr('data-id'),type: $(this).attr('data-type'),value: $(this).val()};
                }
                if($(this).is('input[type="number"]')){
                    var data = {id:$(this).attr('data-id'),type: $(this).attr('data-type'),value: $(this).val()};
                }
                if($(this).is('textarea')){
                    if($(this).hasClass('wp-editor-area')){
                        tinyMCE.triggerSave();
                        var id = $(this).attr('id'); 
                        var data = {id:$(this).attr('id'),type: $(this).attr('name'),value: $(this).val()};      
                    }else{
                        var data = {id:$(this).attr('data-id'),type: $(this).attr('name'),value: $(this).val()};   
                    }  
                }
                settings.push(data);
        });

        $.confirm({
          text: wplms_front_end_messages.save_course_confirm,
          confirm: function() {
           
             $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: { action: 'new_save_course_settings', 
                            security: $('#security').val(),
                            course_id:$('#course_id').val(),
                            settings:JSON.stringify(settings)    
                          },
                    cache: false,
                    success: function (html) {
                   
                        $this.removeClass('disabled');
                        if($.isNumeric(html)){
                            $('#course_creation_tabs').trigger('increment');
                        }else{
                            $this.html(html);
                            setTimeout(function(){$this.html(defaulttxt);}, 5000);
                        }
                    }
            });
          },
          cancel: function() {
              $this.removeClass('disabled');
          },
          confirmButton: wplms_front_end_messages.save_course_confirm_button,
          cancelButton: vibe_course_module_strings.cancel
      });

    });
    /* === Save Course Components Ajax ====*/
    $('#save_course_components_button').on('click',function(){

        var $this = $(this);
        var defaulttxt = $this.html();
        $this.addClass('disabled');

        var components = [];

        $('#course_components').find('.post_field').each(function() {
                
                if($(this).is(':checkbox:checked')){
                    var data = {id:$(this).attr('data-id'),type: $(this).attr('data-type'),value: $(this).val()};
                }
                if($(this).is(':radio:checked')){ 
                    var data = {id:$(this).attr('data-id'),type: $(this).attr('data-type'),value: $(this).val()};
                }
                if($(this).is('select')){
                    var data = {id:$(this).attr('data-id'),type: $(this).attr('data-type'),value: $(this).val()};
                }
                if($(this).is('input[type="text"]')){
                    var data = {id:$(this).attr('data-id'),type: $(this).attr('data-type'),value: $(this).val()};
                }
                if($(this).is('input[type="number"]')){
                    var data = {id:$(this).attr('data-id'),type: $(this).attr('data-type'),value: $(this).val()};
                }
                if($(this).is('input[type="hidden"]')){
                    var data = {id:$(this).attr('data-id'),type: $(this).attr('data-type'),value: $(this).val()};
                }
                if($(this).is('textarea')){
                   var data = {id:$(this).attr('data-id'),type: $(this).attr('data-type'),value: $(this).val()};   
                }
                components.push(data);
        });

        $.confirm({
          text: wplms_front_end_messages.save_course_confirm,
          confirm: function() {
           
             $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: { action: 'new_save_course_components', 
                            security: $('#security').val(),
                            course_id:$('#course_id').val(),
                            settings:JSON.stringify(components)    
                          },
                    cache: false,
                    success: function (html) {
                   
                        $this.removeClass('disabled');
                        if($.isNumeric(html)){
                            $('#course_creation_tabs').trigger('increment');
                        }else{
                            $this.html(html);
                            setTimeout(function(){$this.html(defaulttxt);}, 5000);
                        }
                    }
            });
          },
          cancel: function() {
              $this.removeClass('disabled');
          },
          confirmButton: wplms_front_end_messages.save_course_confirm_button,
          cancelButton: vibe_course_module_strings.cancel
      });

    });

    /*===== Curriculum ====*/
    $('.data_links .edit').on('click',function(){
        var $this = $(this);
        var defaulttxt = $this.html();
        $.ajax({
                type: "POST",
                url: ajaxurl,
                data: { action: 'get_element', 
                        security: $('#security').val(),
                        course_id:$('#course_id').val(),
                        element_id: $this.parent().parent().parent().find('.title').attr('data-id'),
                      },
                cache: false,
                success: function (html) {
                    $('#course_curriculum').append(html);

                    var height = $('#course_curriculum > .element_overlay').outerHeight()+60;

                    $('#course_curriculum').css('height',height+'px');
                    $('#course_curriculum').css('overflow-y','scroll');
                    $('#course_curriculum').trigger('active');
                    $('.element_overlay .tip').tooltip();
                    $('.element_overlay .wp-editor-area').each(function(){
                        var editor_id = $(this).attr('id');

                    });
                    $('.element_overlay .close-pop').click(function(){
                        $(this).parent().remove();
                    });
                    $('.add_cpt .more').click(function(event){
                        $('.select_existing_cpt,.new_cpt').hide();
                        $(this).next().toggle(200);
                    });
                    $('.accordion_trigger').on('click',function(){
                        $(this).parent().toggleClass('open');
                    });
                }
        });
    });

    $('.data_links .preview').on('click',function(){
        var $this = $(this);
        var defaulttxt = $this.html();
        $.ajax({
                type: "POST",
                url: ajaxurl,
                data: { action: 'preview_element', 
                        security: $('#security').val(),
                        course_id:$('#course_id').val(),
                        element_id: $this.parent().parent().parent().find('.title').attr('data-id'),
                      },
                cache: false,
                success: function (html) {
                    $('#course_curriculum').append(html);

                    var height = $('#course_curriculum > .element_overlay').outerHeight()+60;

                    $('#course_curriculum').css('height',height+'px');
                    $('#course_curriculum').css('overflow-y','scroll');
                    $('#course_curriculum').trigger('active');

                    $('.element_overlay .close-pop').click(function(){
                        $(this).parent().remove();
                    });
                    $('.accordion_trigger').on('click',function(){
                        $(this).parent().toggleClass('open');
                    });
                    
                }
        });
    });

    $('.data_links .remove').on('click',function(){
        $(this).closest('.data_links').closest('li').remove();
    });

    $('.data_links .delete').on('click',function(){
        var $this = $(this);

        if($this.hasClass('disabled'))
            return;

        $this.addClass('disabled');
        var post_id = $(this).closest('.data_links').parent().find('.title').attr('data-id');
        $.confirm({
              text: wplms_front_end_messages.delete_confirm,
              confirm: function() {
               
                 $.ajax({
                        type: "POST",
                        url: ajaxurl,
                        data: { action: 'delete_element', 
                                security: $('#security').val(),
                                id:post_id,  
                              },
                        cache: false,
                        success: function (html) {
                            $this.removeClass('disabled');
                            if($.isNumeric(html)){
                                $this.closest('.data_links').parent('li').remove();
                            }
                        }
                });
              },
              cancel: function() {
                  $this.removeClass('disabled');
              },
              confirmButton: wplms_front_end_messages.delete_confirm_button,
              cancelButton: vibe_course_module_strings.cancel
          });
    });
    /* ===== END ==== */

    /* === Save Course Components Ajax ====*/
        $('#save_pricing_button').on('click',function(){

            var $this = $(this);
            var defaulttxt = $this.html();
            $this.addClass('disabled');

            var pricing = [];

            $('#course_pricing').find('.post_field').each(function() {
                    if(!$(this).closest('.select_product_form').length && !$(this).closest('.new_product_form').length){

                        if($(this).is(':checkbox:checked')){
                            var data = {id:$(this).attr('data-id'),type: $(this).attr('data-type'),value: $(this).val()};
                        }
                        if($(this).is(':radio:checked')){ 
                            var data = {id:$(this).attr('data-id'),type: $(this).attr('data-type'),value: $(this).val()};
                        }
                        if($(this).is('select')){
                            if($(this).is("select[multiple]")){
                                var values = {};

                                $(this).find('option:selected').each(function(i,selected){
                                    values[i] = $(selected).val();
                                });
                                var data = {id:$(this).attr('data-id'),value: values};
                            }else{
                                var data = {id:$(this).attr('data-id'),value: $(this).val()};
                            }
                        }
                        if($(this).is('input[type="text"]')){
                            var data = {id:$(this).attr('data-id'),type: $(this).attr('data-type'),value: $(this).val()};
                        }
                        if($(this).is('input[type="number"]')){
                            var data = {id:$(this).attr('data-id'),type: $(this).attr('data-type'),value: $(this).val()};
                        }
                        if($(this).is('input[type="hidden"]')){
                            var data = {id:$(this).attr('data-id'),type: $(this).attr('data-type'),value: $(this).val()};
                        }
                        if($(this).is('textarea')){
                           var data = {id:$(this).attr('data-id'),type: $(this).attr('data-type'),value: $(this).val()};   
                        }
                        pricing.push(data);
                    }
            });

            $.confirm({
              text: wplms_front_end_messages.save_course_confirm,
              confirm: function() {
               
                 $.ajax({
                        type: "POST",
                        url: ajaxurl,
                        data: { action: 'new_save_pricing', 
                                security: $('#security').val(),
                                course_id:$('#course_id').val(),
                                settings:JSON.stringify(pricing)    
                              },
                        cache: false,
                        success: function (html) {
                       
                            $this.removeClass('disabled');
                            if($.isNumeric(html)){
                                $('#course_creation_tabs').trigger('increment');
                            }else{
                                $this.html(html);
                                setTimeout(function(){$this.html(defaulttxt);}, 5000);
                            }
                        }
                });
              },
              cancel: function() {
                  $this.removeClass('disabled');
              },
              confirmButton: wplms_front_end_messages.save_course_confirm_button,
              cancelButton: vibe_course_module_strings.cancel
          });

        });

    $('.selectgroup').select2({
        minimumInputLength: 4,
        placeholder: $(this).attr('data-placeholder'),
        closeOnSelect: true,
        ajax: {
            url: ajaxurl,
            type: "POST",
            dataType: 'json',
            delay: 250,
            data: function(term){ 
                    return  {   action: 'get_groups', 
                                security: $('#security').val(),
                                q: term,
                            }
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },       
            cache:true  
        },
    });
    $('.selectforum').select2({
        minimumInputLength: 4,
        placeholder: $(this).attr('data-placeholder'),
        closeOnSelect: true,
        ajax: {
            url: ajaxurl,
            type: "POST",
            dataType: 'json',
            delay: 250,
            data: function(term){ 
                    return  {   action: 'get_forums', 
                                security: $('#security').val(),
                                q: term,
                            }
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },       
            cache:true  
        },
    });
    $('.use_selected').on('click',function(){
        var val = $(this).parent().find('select').val();
        var label = $(this).parent().find('select option').text();
        var parent = $(this).parent().parent().parent().parent();
        parent.find('input[type="hidden"]').val(val);
        var type = $(this).parent().attr('class');
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: { action: 'get_permalink', 
                    security: $('#security').val(),
                    type:type,
                    id: val,
                  },
            cache: false,
            success: function (html) {
                parent.find('h3').html('');
                parent.find('h3').html(html);
                $('.course_components').trigger('active');
            }
        });        
        parent.find('h3>span').trigger('click');
    })

    $('.selectcpt.select2').each(function(){
        var cpt = $(this).attr('data-cpt');
        var post_status = $(this).attr('data-status');
        var placeholder = $(this).attr('data-placeholder');
        $(this).select2({
            minimumInputLength: 4,
            placeholder: placeholder,
            closeOnSelect: true,
            ajax: {
                url: ajaxurl,
                type: "POST",
                dataType: 'json',
                delay: 250,
                data: function(term){ 
                        return  {   action: 'get_select_cpt', 
                                    security: $('#security').val(),
                                    cpt: cpt,
                                    stats: post_status,
                                    q: term,
                                }
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },       
                cache:true  
            },
        });
    });
    $('.use_selected_product').on('click',function(){
        var $this = $(this);
        if($this.hasClass('disabled'))
            return;

        $this.addClass('disabled');
        var product_id = $(this).parent().find('select').val();
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: { action: 'set_product', 
                    security: $('#security').val(),
                    course_id:$('#course_id').val(),
                    product_id: product_id,
                  },
            cache: false,
            success: function (html) {
                $this.removeClass('disabled');
                $('#course_pricing .vibe_vibe_product>h3').html(html);
                $('#change_product,#edit_product').hide();
                $('.course_pricing').trigger('active');
            }
        });
    });
    $('#create_new_product').on('click',function(e){
        var $this = $(this);
        
        if($this.hasClass('disabled'))
            return;

        $this.addClass('disabled');
        var parent = $(this).parent();
        var defaulttxt = $(this).text();
        var settings = [];

        $('.new_product_form').find('.post_field').each(function() {
                
                if($(this).is(':checkbox:checked')){
                    var data = {id:$(this).attr('data-id'),type: $(this).attr('data-type'),value: $(this).val()};
                }
                if($(this).is(':radio:checked')){ 
                    var data = {id:$(this).attr('data-id'),type: $(this).attr('data-type'),value: $(this).val()};
                }
                if($(this).is('select')){
                    var data = {id:$(this).attr('data-id'),type: $(this).attr('data-type'),value: $(this).val()};
                }
                if($(this).is('input[type="text"]')){
                    var data = {id:$(this).attr('data-id'),type: $(this).attr('data-type'),value: $(this).val()};
                }
                if($(this).is('input[type="number"]')){
                    var data = {id:$(this).attr('data-id'),type: $(this).attr('data-type'),value: $(this).val()};
                }
                if($(this).is('input[type="hidden"]')){
                    var data = {id:$(this).attr('data-id'),type: $(this).attr('data-type'),value: $(this).val()};
                }
                if($(this).is('textarea')){
                   var data = {id:$(this).attr('data-id'),type: $(this).attr('data-type'),value: $(this).val()};   
                }
                settings.push(data);
        });


        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: { action: 'create_new_product', 
                    security: $('#security').val(),
                    course_id:$('#course_id').val(),
                    settings: JSON.stringify(settings),
                  },
            cache: false,
            success: function (html) {
                $this.removeClass('disabled');
                $('#course_pricing .vibe_vibe_product>h3').html(html);
                $('#change_product,#edit_product').hide();
                $('.course_pricing').trigger('active');
            }
        });
    });
    
    $('#edit_course_product').on('click',function(e){
        var $this = $(this);
        
        if($this.hasClass('disabled'))
            return;

        $this.addClass('disabled');
        var parent = $(this).parent();
        var defaulttxt = $(this).text();
        var settings = [];

        $('#edit_product').find('.post_field').each(function() {
                
                if($(this).is(':checkbox:checked')){
                    var data = {id:$(this).attr('data-id'),type: $(this).attr('data-type'),value: $(this).val()};
                }
                if($(this).is(':radio:checked')){ 
                    var data = {id:$(this).attr('data-id'),type: $(this).attr('data-type'),value: $(this).val()};
                }
                if($(this).is('select')){
                    var data = {id:$(this).attr('data-id'),type: $(this).attr('data-type'),value: $(this).val()};
                }
                if($(this).is('input[type="text"]')){
                    var data = {id:$(this).attr('data-id'),type: $(this).attr('data-type'),value: $(this).val()};
                }
                if($(this).is('input[type="number"]')){
                    var data = {id:$(this).attr('data-id'),type: $(this).attr('data-type'),value: $(this).val()};
                }
                if($(this).is('input[type="hidden"]')){
                    var data = {id:$(this).attr('data-id'),type: $(this).attr('data-type'),value: $(this).val()};
                }
                if($(this).is('textarea')){
                    if($(this).hasClass('wp-editor-area')){
                        var data = {id:$(this).attr('id'),type: $(this).attr('name'),value: $(this).val()};   
                    }else{
                        var data = {id:$(this).attr('data-id'),type: $(this).attr('name'),value: $(this).val()};   
                    } 
                }
                settings.push(data);
        });


        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: { action: 'create_new_product', 
                    security: $('#security').val(),
                    course_id:$('#course_id').val(),
                    settings: JSON.stringify(settings),
                  },
            cache: false,
            success: function (html) {
                $this.removeClass('disabled');
                $('#course_pricing .vibe_vibe_product>h3').html(html);
                $('#change_product,#edit_product').hide();
                $('.course_pricing').trigger('active');
            }
        });
    });

    $('#create_new_group').on('click',function(){
        var $this = $(this);
        var defaulttxt = $this.text();
        if($this.hasClass('disabled'))
            return;

        $this.addClass('disabled');
        $.confirm({
          text: wplms_front_end_messages.create_group_confirm,
          confirm: function() {
             $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: { action: 'create_group', 
                            security: $('#security').val(),
                            course_id: $('#course_id').val(),
                            title: $('#vibe_group_name').val(),
                            privacy:$('#vibe_group_privacy').val(),
                            description : $('vibe_group_description').val(),
                          },
                    cache: false,
                    success: function (html) {
                        $this.removeClass('disabled');
                        if($.isNumeric(html)){
                            $('#vibe_group').val(html);
                            var span = $('.vibe_vibe_group>.field_wrapper>h3>span').html();
                            var nhtml = html;
                            $.ajax({
                                type: "POST",
                                url: ajaxurl,
                                data: { action: 'get_permalink', 
                                        security: $('#security').val(),
                                        type:'group',
                                        id: nhtml,
                                      },
                                cache: false,
                                success: function (html) {
                                    $('.vibe_vibe_group>.field_wrapper>h3').html(html);
                                    $('.course_components').trigger('active');
                                }
                            });
                            
                            $('.vibe_vibe_group>.field_wrapper>h3>span').trigger('click');
                        }else{
                            $this.html(html);
                            setTimeout(function(){$this.html(defaulttxt);}, 2000);
                        }
                    }
            });
          },
          cancel: function() {
              $this.removeClass('disabled');
          },
          confirmButton: wplms_front_end_messages.create_group_confirm_button,
          cancelButton: vibe_course_module_strings.cancel
      });
    });
    $('#create_new_forum').on('click',function(){
        var $this = $(this);
        var defaulttxt = $this.text();
        if($this.hasClass('disabled'))
            return;

        $this.addClass('disabled');
        $.confirm({
          text: wplms_front_end_messages.create_forum_confirm,
          confirm: function() {
            $.ajax({
                type: "POST",
                url: ajaxurl,
                data: { action: 'create_forum', 
                        security: $('#security').val(),
                        course_id: $('#course_id').val(),
                        title: $('#vibe_forum_name').val(),
                        privacy:$('#vibe_forum_privacy').val(),
                        description : $('vibe_forum_description').val(),
                      },
                cache: false,
                success: function (html) {
                    
                    $this.removeClass('disabled');
                    if($.isNumeric(html)){
                        $('#vibe_forum').val(html);
                        var span = $('.vibe_vibe_forum>.field_wrapper>h3>span').html();
                        var nhtml = html;
                        $.ajax({
                                type: "POST",
                                url: ajaxurl,
                                data: { action: 'get_permalink', 
                                        security: $('#security').val(),
                                        type:'forum',
                                        id: nhtml,
                                      },
                                cache: false,
                                success: function (html) {
                                    $('.vibe_vibe_forum>.field_wrapper>h3').html(html);
                                    $('.course_components').trigger('active');
                                }
                            });
                        $('.vibe_vibe_forum>.field_wrapper>h3>span').trigger('click');
                    }else{
                        $this.html(html);
                        setTimeout(function(){$this.html(defaulttxt);}, 2000);
                    }
                }
            });
          },
          cancel: function() {
              $this.find('i').remove();
              $this.removeClass('disabled');
          },
          confirmButton: wplms_front_end_messages.create_group_confirm_button,
          cancelButton: vibe_course_module_strings.cancel
      });
    });
    
    $('#save_course_curriculum_button').on('click',function(){
        var course_id=$('#course_id').val();
        var $this = $(this);
        var defaulttxt = $this.html();
        var curriculum = [];
        if($(this).hasClass('disabled'))
            return;

        $('ul.curriculum li').each(function() {

            if($(this).hasClass('new_section')){

                if($(this).find('input.section').length){
                    var val = $(this).find('input.section').val();
                }else{
                    var val = $(this).find('strong').text();
                }
                
            }else{
               var val =  $(this).find('strong.title').attr('data-id');
            }
            if(typeof val != 'undefined'){
                var data = { id: val };  
                curriculum.push(data);                 
            } 
        });

        $this.addClass('disabled');

        $.confirm({
          text: wplms_front_end_messages.save_course_confirm,
          confirm: function() {
             $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: { action: 'save_course_curriculum', 
                            security: $('#security').val(),
                            course_id: course_id,
                            curriculum: JSON.stringify(curriculum),
                          },
                    cache: false,
                    success: function (html) {
                        $this.removeClass('disabled');
                        if($.isNumeric(html)){
                            $('#course_creation_tabs').trigger('increment');
                        }else{
                            $this.html(html);
                            setTimeout(function(){$this.html(defaulttxt);}, 2000);
                        }
                    }
            });
          },
          cancel: function(){
              $this.removeClass('disabled');
          },
          confirmButton: wplms_front_end_messages.save_course_confirm_button,
          cancelButton: vibe_course_module_strings.cancel
          });
    });

    $('#course_curriculum').on('active',function(){

        $('.trigger_new_product').unbind('click');
        $('.trigger_new_product').on('click',function(){
            $('.new_product').toggle(200);
        });
        $('#save_course_curriculum_button').removeClass('disabled');

        $('.select_existing').unbind('click');
        $('.select_existing').on('click',function(){
            $(this).parent().find('.existing').toggle(200);
        }); 

        $('.select_new').unbind('click');
        $('.select_new').on('click',function(){
            $(this).parent().find('.new_actions').toggle(200);
        });
        $('.tip').tooltip();
        $('.add_cpt .more').unbind('click');
        $('.add_cpt .more').click(function(event){
            $('.select_existing_cpt,.new_cpt').hide();
            $(this).next().toggle(200);
        });
        $('.wplms-taxonomy select').change(function(event){
            var new_tax = $(this).parent().parent().find('.wplms-new-taxonomy');
            if($(this).val() === 'new'){
                new_tax.addClass('animate cssanim fadeIn load');
            }else{
                new_tax.removeClass('animate cssanim fadeIn load');
            }
        });
        $('.chosen').chosen();
        
        $('ul.curriculum').sortable({
          revert: true,
          cursor: 'move',
          refreshPositions: true, 
          opacity: 0.6,
          scroll:true,
          containment: 'parent',
          placeholder: 'placeholder',
          tolerance: 'pointer',
        }).disableSelection();

        $('.select2').each(function(){
            if($(this).hasClass('select2-hidden-accessible'))
                return;
             if(!$(this).hasClass('selectcpt'))
                $(this).select2();
        });

        $('.selectcpt.select2').each(function(){

            if($(this).hasClass('select2-hidden-accessible'))
                return;
            var cpt = $(this).attr('data-cpt');
            var placeholder = $(this).attr('data-placeholder');
            var post_status = $(this).attr('data-status');
            $(this).select2({
                minimumInputLength: 4,
                placeholder: placeholder,
                closeOnSelect: true,
                ajax: {
                    url: ajaxurl,
                    type: "POST",
                    dataType: 'json',
                    delay: 250,
                    data: function(term){ 
                            return  {   action: 'get_select_cpt', 
                                        security: $('#security').val(),
                                        cpt: cpt,
                                        status:post_status,
                                        q: term,
                                    }
                    },
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    },       
                    cache:true  
                },
            });
        });        
        
         /* ===== Save Unit/Quiz ==== */
        $('#save_element_button').unbind('click');
        $('#save_element_button').on('click',function(event){
            var $this = $(this);
                var defaulttxt = $this.html();
                $this.addClass('disabled');

                var settings = [];
                var main;
                if($this.parent().hasClass('question_edit_settings_content')){
                    main = '.question_edit_settings_content';
                }else if($this.parent().hasClass('wplms-assignment_edit_settings_content')){
                    main = '.wplms-assignment_edit_settings_content';
                }else{
                    main = '.element_overlay';
                }
                
                $(main).find('.post_field').each(function() {
                        

                        if($(this).is(':radio:checked')){ 
                            var data = {id:$(this).attr('name'),value: $(this).val()};
                        }
                        if($(this).is('select')){
                            if($(this).is("select[multiple]")){
                                var values = {};

                                $(this).find('option:selected').each(function(i,selected){
                                    values[i] = $(selected).val();
                                });
                                var data = {id:$(this).attr('data-id'),value: values};
                            }else{
                                var data = {id:$(this).attr('data-id'),value: $(this).val()};
                            }
                        }
                        if($(this).hasClass('repeatable')){
                            var values = {};
                            $(this).find('li').each(function(i,selected){
                                values[i] = $(this).find('input').val();
                            });
                            var data = {id:$(this).attr('data-id'),value: values};
                        }

                        if($(this).hasClass('list-group-questions')){
                            var values = {};
                            var marks = {};
                            var val = {};
                            $(this).find('.question_block').each(function(i,selected){
                                values[i] = $(this).find('.question_id').val();
                                marks[i] = $(this).find('.question_marks').val();
                            });
                            val ={ques:values,marks:marks};
                            var data = {id:$(this).attr('data-id'),value: val};
                        }

                        if($(this).hasClass('list-group-assignments')){
                            var values = {};
                            $(this).find('.assignment_block').each(function(i,selected){
                                values[i] = $(this).find('.assignment_id').val();
                            });
                            var data = {id:$(this).attr('data-id'),value: values};
                        }

                        if($(this).is('input[type="text"]')){
                            var data = {id:$(this).attr('data-id'),type: $(this).attr('data-type'),value: $(this).val()};
                        }
                        if($(this).is('input[type="number"]')){
                            var data = {id:$(this).attr('data-id'),type: $(this).attr('name'),value: $(this).val()};
                        }
                        if($(this).is('input[type="hidden"]')){
                            var data = {id:$(this).attr('data-id'),type: $(this).attr('data-type'),value: $(this).val()};
                        }
                        if($(this).is('textarea')){
                            if($(this).hasClass('wp-editor-area')){
                                tinyMCE.triggerSave();
                                var data = {id:$(this).attr('id'),type: $(this).attr('name'),value: $(this).val()};    
                            }else{
                                var data = {id:$(this).attr('data-id'),type: $(this).attr('name'),value: $(this).val()};   
                            }
                        }
                        settings.push(data);
                });

                $.confirm({
                  text: wplms_front_end_messages.save_confirm,
                  confirm: function() {
                   
                     $.ajax({
                            type: "POST",
                            url: ajaxurl,
                            data: { action: 'save_element', 
                                    security: $('#security').val(),
                                    id:$this.attr('data-id'),
                                    course_id:$('#course_id').val(),
                                    settings:JSON.stringify(settings)    
                                  },
                            cache: false,
                            success: function (html) {
                                $this.removeClass('disabled');
                                $this.html(html);
                                setTimeout(function(){$this.html(defaulttxt);}, 5000);
                            }
                    });
                  },
                  cancel: function() {
                      $this.removeClass('disabled');
                  },
                  confirmButton: wplms_front_end_messages.save_confirm_button,
                  cancelButton: vibe_course_module_strings.cancel
              });
        });
        
        /* === Questions List in Quizes === */
        $('.edit_sub').unbind('click');
        $('.edit_sub').on('click',function(event){
            event.preventDefault();
            var $this = $(this);
            var parent = $this.parent().parent().parent();
            if(parent.hasClass('loaded')){
                parent.toggle('collapse');
            }
            if($this.hasClass('disabled'))
                return;

            $this.addClass('disabled');
            $.ajax({
                type: "POST",
                url: ajaxurl,
                data: { action: 'get_sub_element', 
                        security: $('#security').val(),
                        id:parent.find('input[type="hidden"]').val(),
                      },
                cache: false,
                success: function (html) {
                    $this.removeClass('disabled');
                    parent.hasClass('loaded');
                    parent.append(html);
                    $('#course_curriculum').trigger('active');
                }
            });
        });
        $('.preview_sub').unbind('click');
        $('.preview_sub').on('click',function(event){
            event.preventDefault();
            var $this = $(this);
            var parent = $this.parent().parent().parent();
            if(parent.hasClass('loaded')){
                parent.toggle('collapse');
            }
            if($this.hasClass('disabled'))
                return;

            $this.addClass('disabled');
            $.ajax({
                type: "POST",
                url: ajaxurl,
                data: { action: 'preview_sub_element', 
                        security: $('#security').val(),
                        id:parent.find('input[type="hidden"]').val(),
                      },
                cache: false,
                success: function (html) {
                    $this.removeClass('disabled');
                    parent.hasClass('loaded');
                    parent.append(html);
                    $('#course_curriculum').trigger('question_loaded');
                }
            });
        });

        $('.remove_sub').unbind('click');
        $('.remove_sub').on('click',function(){
            $(this).parent().parent().parent().remove();
        });
        $('.delete_sub').unbind('click');
        $('.delete_sub').on('click',function(){
            var $this = $(this);

            if($this.hasClass('disabled'))
                return;

            $this.addClass('disabled');
            var post_id = $(this).closest('.data_links').parent().find('.title').attr('data-id');
            $.confirm({
                  text: wplms_front_end_messages.delete_confirm,
                  confirm: function() {
                   
                     $.ajax({
                            type: "POST",
                            url: ajaxurl,
                            data: { action: 'delete_element', 
                                    security: $('#security').val(),
                                    id:post_id,  
                                  },
                            cache: false,
                            success: function (html) {
                                $this.removeClass('disabled');
                                if($.isNumeric(html)){
                                    $this.closest('.data_links').parent('li').remove();
                                }
                            }
                    });
                  },
                  cancel: function() {
                      $this.removeClass('disabled');
                  },
                  confirmButton: wplms_front_end_messages.delete_confirm_button,
                  cancelButton: vibe_course_module_strings.cancel
              });
        }); 
        $('#close_element_button').unbind('click');
        $('#close_element_button').click(function(){
            $(this).parent().hide(200).remove();
        });

        $('ul.repeatable').sortable({
            revert: true,
            cursor: 'move',
            refreshPositions: true, 
            opacity: 0.6,
            scroll:true,
            containment: 'parent',
            placeholder: 'placeholder',
            tolerance: 'pointer',
            update: function(event, ui) {
                $(this).trigger('update');
            }
        }).disableSelection();
        $('ul.repeatable').on('update',function(){
            var index=0;
            $(this).find('li').each(function(){
                index= $(this).index();
                $(this).find('span').text((index+1));
            });
        });

        $('.add_repeatable_count_option').on('click',function(){
            var clone = $('ul.hidden >li').clone();
            var count = $(this).next().find('li').length;
            clone = '<li><span>'+(count+1)+'</span>'+clone.html()+'</li>';
            $(this).next().append(clone);
            $('#course_curriculum').trigger('question_loaded');
        });

        $('.list-group-questions').sortable({
            item: '.question_block',
            handle: '.dashicons-sort',
            revert: true,
            cursor: 'move',
            refreshPositions: true, 
            opacity: 0.6,
            scroll:true,
            containment: 'parent',
            placeholder: 'placeholder',
            tolerance: 'pointer',
        }).disableSelection();

        $('.use_selected_question').unbind('click');
        $('.use_selected_question').on('click',function(){
            var id = $(this).parent().find('.selectcpt option:selected').val();
            var name = $(this).parent().find('.selectcpt option:selected').text();
            var clone = $('.hidden_block').clone();
          
            clone.find('.title').text(name).attr('data-id',id);
            clone.find('.question_id').val(id);
            clone.find('.question_marks').val('0');
            clone.removeClass('hide').removeClass('hidden_block').addClass('question_block');
            clone.insertBefore('.list-group-questions .hidden_block');
            $('#course_curriculum').trigger('active');
            $('.select_existing_cpt,.new_cpt').hide();
        });
        $(document).on('click','#create_new_question',function(e){
            var $this = $(this);
            
            if($this.hasClass('disabled'))
                return;

            $this.addClass('disabled');
            var parent = $(this).parent();
            var defaulttxt = $(this).text();
            var title = parent.find('#vibe_question_title').val();
            $.ajax({
                type: "POST",
                url: ajaxurl,
                data: { action: 'create_new_question', 
                        security: $('#security').val(),
                        title: title,
                        question_tag:$('#question-tag-select').val(),
                        new_question_tag:$('#new_question_tag').val(),
                        template: parent.find('#vibe_question_template').val()
                      },
                cache: false,
                success: function (html) {
                    $this.removeClass('disabled');
                    if($.isNumeric(html)){
                        var clone = $('.hidden_block').clone();
                        clone.find('.title').text(title).attr('data-id',html);
                        clone.find('.question_id').val(html);
                        clone.find('.question_marks').val('0');
                        clone.removeClass('hide').removeClass('hidden_block').addClass('question_block');
                        clone.insertBefore('.list-group-questions .hidden_block');
                        parent.find('#vibe_question_title').val('');
                        $('#new_question_tag').val('');
                        $('.select_existing_cpt,.new_cpt').hide();
                        $('#course_curriculum').trigger('active');
                    }else{
                        $this.html(html);
                        setTimeout(function(){$this.html(defaulttxt);}, 5000);
                    }
                }
            });
        });
        /*==== End question List ===*/
        /* === Assignment List in Units === */
        $('.list-group-assignments').sortable({
            item: '.assignment_block',
            handle: '.dashicons-sort',
            revert: true,
            cursor: 'move',
            refreshPositions: true, 
            opacity: 0.6,
            scroll:true,
            containment: 'parent',
            placeholder: 'placeholder',
            tolerance: 'pointer',
        }).disableSelection();
        $('.use_selected_assignment').unbind('click');
        $('.use_selected_assignment').on('click',function(){
            var id = $(this).parent().find('.selectcpt option:selected').val();
            var name = $(this).parent().find('.selectcpt option:selected').text();
            var clone = $('.hidden_block').clone();
            clone.find('.title').text(name).attr('data-id',id);
            clone.find('.assignment_id').val(id);
            clone.removeClass('hide').removeClass('hidden_block').addClass('assignment_block');
            clone.insertBefore('.list-group-assignments .hidden_block');
            $('.remove_sub').on('click',function(){
                $(this).parent().parent().parent().remove();
            });
            $('#course_curriculum').trigger('active');
        });

        $(document).on('click','#create_new_assignment',function(e){
            var $this = $(this);
            
            if($this.hasClass('disabled'))
                return;

            $this.addClass('disabled');
            var parent = $(this).parent();
            var defaulttxt = $(this).text();
            var title = parent.find('#vibe_assignment_title').val();
            $.ajax({
                type: "POST",
                url: ajaxurl,
                data: { action: 'create_new_assignment', 
                        security: $('#security').val(),
                        cpt:'wplms-assignment',
                        title: title,
                      },
                cache: false,
                success: function (html) {
                    $this.removeClass('disabled');
                    if($.isNumeric(html)){
                        var clone = $('.hidden_block').clone();
                        clone.find('.title').text(title).attr('data-id',html);
                        clone.find('.assignment_id').val(html);
                        clone.removeClass('hide').removeClass('hidden_block').addClass('assignment_block');
                        clone.insertBefore('.list-group-assignments .hidden_block');
                        parent.find('#vibe_assignment_title').val('');
                        $('.remove_sub').on('click',function(){
                            $(this).parent().parent().parent().remove();
                        });
                        $('#course_curriculum').trigger('active');
                    }else{
                        $this.html(html);
                        setTimeout(function(){$this.html(defaulttxt);}, 5000);
                    }
                }
            });
        });
        $('.data_links .edit').unbind('click');
        $('.data_links .edit').on('click',function(){
            var $this = $(this);
            var defaulttxt = $this.html();
            $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: { action: 'get_element', 
                            security: $('#security').val(),
                            course_id:$('#course_id').val(),
                            element_id: $this.parent().parent().parent().find('.title').attr('data-id'),
                          },
                    cache: false,
                    success: function (html) {
                        $('#course_curriculum').append(html);

                        var height = $('#course_curriculum > .element_overlay').outerHeight()+60;

                        $('#course_curriculum').css('height',height+'px');
                        $('#course_curriculum').css('overflow-y','scroll');
                        $('#course_curriculum').trigger('active');

                        $('.element_overlay .close-pop').click(function(){
                            $(this).parent().remove();
                        });
                        $('.add_cpt .more').click(function(event){
                            $('.select_existing_cpt,.new_cpt').hide();
                            $(this).next().toggle(200);
                        });
                        $('.accordion_trigger').on('click',function(){
                            $(this).parent().toggleClass('open');
                        });
                        
                    }
            });
        });
        $('.data_links .preview').unbind('click');
        $('.data_links .preview').on('click',function(){
            var $this = $(this);
            var defaulttxt = $this.html();
            $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: { action: 'preview_element', 
                            security: $('#security').val(),
                            course_id:$('#course_id').val(),
                            element_id: $this.parent().parent().parent().find('.title').attr('data-id'),
                          },
                    cache: false,
                    success: function (html) {
                        $('#course_curriculum').append(html);

                        var height = $('#course_curriculum > .element_overlay').outerHeight()+60;

                        $('#course_curriculum').css('height',height+'px');
                        $('#course_curriculum').css('overflow-y','scroll');
                        $('#course_curriculum').trigger('active');

                        $('.element_overlay .close-pop').click(function(){
                            $(this).parent().remove();
                        });
                        $('.accordion_trigger').on('click',function(){
                            $(this).parent().toggleClass('open');
                        });
                        
                    }
            });
        });
        $('.data_links .remove').unbind('click');
        $('.data_links .remove').on('click',function(){
            $(this).closest('.data_links').closest('li').remove();
        });
        $('.data_links .delete').unbind('click');
        $('.data_links .delete').on('click',function(){
            var $this = $(this);

            if($this.hasClass('disabled'))
                return;

            $this.addClass('disabled');
            var post_id = $(this).closest('.data_links').parent().find('.title').attr('data-id');
            $.confirm({
                  text: wplms_front_end_messages.delete_confirm,
                  confirm: function() {
                   
                     $.ajax({
                            type: "POST",
                            url: ajaxurl,
                            data: { action: 'delete_element', 
                                    security: $('#security').val(),
                                    id:post_id,  
                                  },
                            cache: false,
                            success: function (html) {
                                $this.removeClass('disabled');
                                if($.isNumeric(html)){
                                    $this.closest('.data_links').parent('li').remove();
                                }
                            }
                    });
                  },
                  cancel: function() {
                      $this.removeClass('disabled');
                  },
                  confirmButton: wplms_front_end_messages.delete_confirm_button,
                  cancelButton: vibe_course_module_strings.cancel
              });
        });
    });
    /*==== End Assignment List ===*/
    $('#course_curriculum').on('question_loaded',function(){
        $('#close_element_button').click(function(){
            $(this).parent().hide(200).remove();
        });

    });
    $('body').delegate('#add_course_section','click',function(event){
        var clone = $('#hidden_base .new_section').clone();
        $('ul.curriculum').append(clone);
        $('#course_curriculum').trigger('add_section');
    });

    $('#add_course_unit').on('click',function(event){
        
        var clone = $('#hidden_base .new_unit').clone();
        $('#save_course_curriculum_button').addClass('disabled');
        clone.find('.select_existing_cpt select').addClass('selectcurriculumcpt');
        $('ul.curriculum').append(clone);
        $('#course_curriculum').trigger('add_section');
        return false;
    });

    $('#add_course_quiz').on('click',function(event){

        var clone = $('#hidden_base .new_quiz').clone();
        clone.find('.select_existing_cpt select').addClass('selectcurriculumcpt');
        $('ul.curriculum').append(clone);
        $('#course_curriculum').trigger('add_section');
        return false;
    });
    $('#course_curriculum').on('add_section',function(){

        $('.add_cpt .more').click(function(event){
            $('.select_existing_cpt,.new_cpt').hide();
            $(this).next().toggle(200);
        });
        $('.selectcurriculumcpt').each(function(){
            if($(this).hasClass('select2-hidden-accessible'))
                return;

            var cpt = $(this).attr('data-cpt');
            var placeholder = $(this).attr('data-placeholder');
            $(this).select2({
                minimumInputLength: 4,
                placeholder: placeholder,
                closeOnSelect: true,
                ajax: {
                    url: ajaxurl,
                    type: "POST",
                    dataType: 'json',
                    delay: 250,
                    data: function(term){ 
                            return  {   action: 'get_select_cpt', 
                                        security: $('#security').val(),
                                        cpt: cpt,
                                        q: term,
                                    }
                    },
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    },       
                    cache:true  
                },
            });
        });
        $('.use_selected_curriculum').on('click',function(){
            var $this = $(this);
            var clone = $('#hidden_base').prev().clone();
            var id = $(this).parent().find('.selectcurriculumcpt').val();
            var title = $(this).parent().find('.selectcurriculumcpt option:selected').text();
            $(clone).find('.title > span').text(title);
            $(clone).find('.title').attr('data-id',id);
            var html = clone.html();
            $('.vibe_vibe_course_curriculum ul.curriculum').append(html);
            $this.closest('.new_unit').remove();
            $this.closest('.new_quiz').remove();
            $('#course_curriculum').trigger('active');
        });

        $('.create_new_curriculum').on('click',function(){
            var $this = $(this);
            
            if($this.hasClass('disabled')){
                return;
            }
            var defaulttxt = $this.text();
            var parent = $(this).parent();
            var title = parent.find('.vibe_curriculum_title').val();
            
            $this.addClass('disabled');
            $.ajax({
                type: "POST",
                url: ajaxurl,
                data: { action: 'create_new_curriculum', 
                        security: $('#security').val(),
                        title: title,
                        cpt:$this.parent().find('.vibe_cpt').val()
                      },
                cache: false,
                success: function (html) {
                    $this.removeClass('disabled');
                    if($.isNumeric(html)){
                        var clone = $('#hidden_base').prev().clone();
                        $(clone).find('.title > span').text(title);
                        $(clone).find('.title').attr('data-id',html);
                        var html =clone.html();
                        $('.vibe_vibe_course_curriculum ul.curriculum').append(html);
                        $this.closest('.new_unit').remove();
                        $this.closest('.new_quiz').remove();
                        $('#course_curriculum').trigger('active');
                        $('#close_element_button').click(function(){
                            $(this).parent().hide(200).remove();
                        });
                    }else{
                        $this.html(html);
                        setTimeout(function(){$this.html(defaulttxt);}, 5000);
                    }
                }
            });

        });
        

    });
});