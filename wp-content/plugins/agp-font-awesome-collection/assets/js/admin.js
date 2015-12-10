(function($) {  
    $(document).ready(function() { 
        init();

        $('.widgets-sortables .fac-promotion-text-color').wpColorPicker();
        $('.widgets-sortables .fac-promotion-background-color').wpColorPicker();

        $(".fac-constructor-type-select").val(0);
        
        if( /Android|iPhone|iPad|iPod|webOS|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {   
            $("#fac-constructor-box").colorbox({inline:true, width:"96%"});
        } else {
            $("#fac-constructor-box").colorbox({inline:true, width:"50%"});
        }
        
        $(".fac-constructor-preview-button").click(function(event) {
            showPreview();
        });
        
        $(".fac-constructor-type-select").change(function (event) {
            clearPreview();
            var data = {};
            var container = $(this).closest('#cboxContent');
            
            data.action = 'getElementParameters';
            data.nonce = ajax_fac.ajax_nonce;
            data.key = $(this).val();
            
            showSpinner();
            
            $.ajax({
                url: ajax_fac.ajax_url,
                type: 'POST' ,
                data: data,
                dataType: 'json',
                cache: false,
                success: function(data) {
                    var content = '';
                    if (data['content']) {
                        content = $(data['content']).find('.fac-constructor-params').html();
                    }
                    $(container).find('.fac-constructor-params').html(content);   
                    
                    init();
                    hideSpinner();
                    $('.fac-constructor-preview-container').html(data['preview']);
                    resizeBox();
                    
                    $('a.fac-hint').hover(function() {
                            var el = $(this).parent().find('p.fac-note');
                            el.show();
                            var x = $(this).offset().left - el.outerWidth();
                            var y = $(this).offset().top + $(this).outerHeight();
                            el.offset({ top: y, left: x });                            
                        }, function() {
                            var el = $(this).parent().find('p.fac-note');
                            el.hide();
                        }
                    );       
                },
                error: function (request, status, error) {
                    hideSpinner();
                }
            });                   
        });
        
        function init() {
            $('.fac-agp-color-picker').wpColorPicker({
                defaultColor: false,
                change: function(event, ui) {
                    clearPreview();
                },
                clear: function() {
                    clearPreview();
                },
                //hide: false,
                palettes: true            
            });                    
            
            $('.fac-element').change(function(event) {
                clearPreview();
            });            
        }
        
        function showPreview() {
            clearPreview();
            var data = 'action=getPreview&nonce=' + ajax_fac.ajax_nonce;
            data = data + "&" + $('#fac-constructor-params').serialize();
            
            var container = $('.fac-constructor-preview-container');
            
            showSpinner();
            $.ajax({
                url: ajax_fac.ajax_url,
                type: 'POST' ,
                data: data,
                dataType: 'json',
                cache: false,
                success: function(data) {
                    $(container).html(data['preview']);
                    hideSpinner();
                    resizeBox();
                },
                error: function (request, status, error) {
                    hideSpinner();
                }
            });                               

        }
        
        function clearPreview() {
            $('.fac-constructor-preview-container').html('');
            resizeBox();
        }
        
        function showSpinner() {
            $('.fac-constructor-spinner').css('visibility', 'visible');
            resizeBox();
        }
        
        function hideSpinner() {
            $('.fac-constructor-spinner').css('visibility', 'hidden');
            resizeBox();
        }        
        
        function resizeBox() {
            $.colorbox.resize();
            $(".fac-constructor-field .iris-picker").css("position", "absolute");
            $("#colorbox, #cboxOverlay, #cboxWrapper").css("overflow", "visible");
        }
              
    });
})(jQuery);


