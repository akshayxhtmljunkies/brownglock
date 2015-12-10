(function($) {  
    $(document).ready(function() { 
        $(document).on('click', '.agp-del-row', function(e) {
           $(this).closest('.agp-row').remove();
        });

        $(document).on('click', '.agp-up-row', function(e) {            
            var el = $(this).closest('.agp-row');
            var prev = $(el).prev('.agp-row');
            $(el).insertBefore(prev);
        });            

        $(document).on('click', '.agp-down-row', function(e) {                        
            var el = $(this).closest('.agp-row');
            var next = $(el).next('.agp-row');
            $(el).insertAfter(next);
        });                    
        
        $(document).on('click', '.agp-add-row', function(e) {        
            var content = $(this).closest('.agp-repeater').find('.agp-row.agp-row-template').html();
            var index = 'xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {var r = Math.random()*16|0,v=c=='x'?r:r&0x3|0x8;return v.toString(16);});
            
            content = '<tr class="agp-row">' + content.replace(/\[0\]/g, '[' + index + ']').replace(/_0_/g, '_' + index + '_') + '</tr>';

            $(this).closest('.agp-repeater').find('tbody').append(content);
        });
        
    });
})(jQuery);
