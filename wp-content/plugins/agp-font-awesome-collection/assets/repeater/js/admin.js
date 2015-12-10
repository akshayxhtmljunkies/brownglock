(function($) {  
    $(document).ready(function() { 
        $('.fac-slider-text-color').each(function() {
            var row = $(this).data('row');
            if (row != '_0_') {
                $(this).wpColorPicker();             
            }
        }); 
        
        $('.fac-slider-background-color').each(function() {
            var row = $(this).data('row');
            if (row != '_0_') {
                $(this).wpColorPicker();             
            }
        });         
        
        $(document).on('click', '#fac_sliders .agp-del-row', function(e) {
           $(this).closest('.agp-row').remove();
        });

        $(document).on('click', '#fac_sliders .agp-up-row', function(e) {            
            var el = $(this).closest('.agp-row');
            var prev = $(el).prev('.agp-row');
            $(el).insertBefore(prev);
        });            

        $(document).on('click', '#fac_sliders .agp-down-row', function(e) {                        
            var el = $(this).closest('.agp-row');
            var next = $(el).next('.agp-row');
            $(el).insertAfter(next);
        });                    
        
        $(document).on('click', '#fac_sliders .agp-add-row', function(e) {        
            var content = $(this).closest('.agp-repeater').find('.agp-row.agp-row-template').html();
            var index = 'xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {var r = Math.random()*16|0,v=c=='x'?r:r&0x3|0x8;return v.toString(16);});

            content = '<tr class="agp-row">' + content.replace(/\[0\]/g, '[' + index + ']').replace(/_0_/g, '_' + index + '_') + '</tr>';

            $(this).closest('.agp-repeater').find('tbody').append(content);
            
            $('.fac-slider-text-color').each(function() {
                var row = $(this).data('row');
                if (row != '_0_') {
                    $(this).wpColorPicker();             
                }
            }); 
            
            $('.fac-slider-background-color').each(function() {
                var row = $(this).data('row');
                if (row != '_0_') {
                    $(this).wpColorPicker();             
                }
            });             
        });
        
//        //make table rows sortable
//        $('#fac_sliders tbody').sortable({
//            helper: function (e, ui) {
//                ui.children().each(function () {
//                    $(this).width($(this).width());
//                });
//                return ui;
//            },
//            scroll: true,
//        });

    });
})(jQuery);
