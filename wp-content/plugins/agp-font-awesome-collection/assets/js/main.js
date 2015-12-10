(function($) {  

    $( window ).resize(function() {
        resize();
    });    
    
    $( document ).ready(function() { 
        resize();

        if( /Android|iPhone|iPad|iPod|webOS|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {  
            $('.fac-promotion-main-section').each(function () {
                $(this).find('a').on('click', function(e) {
                    e.preventDefault();
                    return false;
                });               
                
                $(this).find('.fac-promotion-preview').on('click', function(e) { 
                    if ( $(this).closest('.fac-promotion-main-section').find('.fac-promotion-content').length > 0 ) {
                        e.preventDefault();
                        $(this).closest('.fac-promotion-main-section').find('.fac-promotion-content').show();
                        $(this).closest('.fac-promotion-main-section').find('.fac-promotion-preview').hide();                
                        return false;                        
                    } else {
                        e.preventDefault();                                    
                        var link = $(this).closest('.fac-promotion-main-section').find('a').attr('href');
                        if (typeof(link) !== "undefined" && link != '' ) {
                            window.location = link;
                        }
                        return false;                                        
                    }
                });           

                $(this).find('.fac-promotion-content').on('tap', function(e) { 
                    e.preventDefault();                                    
                    var link = $(this).closest('.fac-promotion-main-section').find('a').attr('href');
                    if (typeof(link) !== "undefined" && link != '' ) {
                        window.location = link;
                    } else {
                        if ($(this).closest('.fac-promotion-main-section').find('.fac-promotion-preview').length > 0 ) {
                            $(this).closest('.fac-promotion-main-section').find('.fac-promotion-content').hide();
                            $(this).closest('.fac-promotion-main-section').find('.fac-promotion-preview').show();                                        
                        }
                    }
                    return false;                
                });                 

                
                $(this).mouseout(function(e){
                    if ( $(this).find('.fac-promotion-preview').length > 0 ) {
                        $(this).find('.fac-promotion-content').hide();
                        $(this).find('.fac-promotion-preview').show();            
                    }
                    return true;
                });                            
            });
        } else {
            $('.fac-promotion-main-section').each(function () {
                if ( $(this).find('.fac-promotion-content').length > 0 && $(this).find('.fac-promotion-preview').length > 0 ) {
                    $(this).hover(
                        function() {
                            $(this).find('.fac-promotion-content').show();
                            $(this).find('.fac-promotion-preview').hide();
                        },
                        function() {
                            $(this).find('.fac-promotion-content').hide();
                            $(this).find('.fac-promotion-preview').show();
                        }                
                    );                                                                
                }   
            });
        }
  
        $(".fac-slider.fac-slider-default .fac-promotion-slider").responsiveSlides({
            auto: true,
            pager: true,
            nav: false,
            pause: true,
            speed: 300,
            timeout: 8000
        });  

        $(".fac-slider.fac-slider-default ul.rslides_tabs").each(function(){
            $(this).wrapAll('<div class="fac-container"></div>');
        });        
        
    });

    function resize() {
        $('.widget_fac_promotion .fac-promotion-main-section').each(function() {
            var height;            
            var content = $(this).find('.fac-promotion-content');
            var preview = $(this).find('.fac-promotion-preview');
            var inner = $(this).find('.fpp-inner');
            
            $(content).css("height", "auto");
            $(preview).css("height", "auto");
            $(inner).css("height", "auto");                
            $(inner).width($(this).outerWidth());            
            
            if ($(content).height() > $(preview).height() ) {
                height = $(content).height();
            } else {
                height = $(preview).height();
            }
            
            $(this).find('.fpp-inner').height(height);     
            $(this).find('.fac-promotion-content').height(height);
            $(this).find('.fac-promotion-preview').height(height);
        });
        
        $('.fac-slider-default').each(function() {
            var o_height = 0;
            var o_width = $(this).width();            
            
            $(this).find('.fac-promotion-main-section').each(function () {
                var height;                            
                var content = $(this).find('.fac-promotion-content');
                var preview = $(this).find('.fac-promotion-preview');
                var inner = $(this).find('.fpp-inner');      
                
                $(content).css("height", "auto");
                $(preview).css("height", "auto");
                $(inner).css("height", "auto");                
                $(inner).width(o_width);            
            
                if ($(content).height() > $(preview).height() ) {
                    height = $(content).height();
                } else {
                    height = $(preview).height();
                }
                
                if ( height > o_height ) {
                    o_height = height;
                }
            });
            
            $(this).find('.fpp-inner').height(o_height);     
            $(this).find('.fac-promotion-content').height(o_height);
            $(this).find('.fac-promotion-preview').height(o_height);
        });        
    }    
    
})(jQuery);


