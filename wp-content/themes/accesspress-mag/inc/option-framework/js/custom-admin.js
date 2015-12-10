 function td_updateMetaboxes() {
        var cur_selection = jQuery('#reviewSelector option:selected').text();

        if(cur_selection.indexOf("No") !== -1) {
            //alert('ra');
            jQuery('.rating_type').hide();
            jQuery('.review_desc').hide();

        } else {
            jQuery('.rating_type').hide();
            jQuery('.rate_' + cur_selection).show();
            jQuery('.review_desc').show();
            //alert(cur_selection);
        }
    }
  
 jQuery(document).ready(function($) {
        td_updateMetaboxes();
        $('#reviewSelector').change(function() {
            td_updateMetaboxes();
        });
        
        
       /*-------------Toogle for slider posts option ----------------------*/
       if( $(".slider_type input[type='radio']:checked").val() !=='cat' ){
            $('#section-homepage_slider_category').hide();
       }
        $(".slider_type input[type='radio']").on('change',function(){
            $('#section-homepage_slider_category').fadeToggle('slow');
        });
        
        $('.radio-post-template-wrapper').click(function(event){
           var available = $(this).attr('available');
           if(available=='pro'){
                event.preventDefault();
           }
        });
        
        $('.radio-post-template-wrapper').hover(function(){
             var available = $(this).attr('available');
             if(available=='pro'){
                $('.pro-tmp-msg').show();
             }
        },function(){
            $('.pro-tmp-msg').hide();
        });
        
        $(".section h4.group-heading").click(function() {
				$(this).next('.group-content').toggle();
                var attr_arrow = $(this).find('.heading-arrow').hasClass('side');
                if(attr_arrow==true){
                    $(this).find('.heading-arrow').removeClass('side');
                    $(this).find('.heading-arrow').addClass('down');
                    $(this).find('.fa').removeClass('fa-angle-right');
                    $(this).find('.fa').addClass('fa-angle-down');
                }
                else if(attr_arrow==false)
                {
                    $(this).find('.heading-arrow').removeClass('down');
                    $(this).find('.heading-arrow').addClass('side');                    
                    $(this).find('.fa').removeClass('fa-angle-down');
                    $(this).find('.fa').addClass('fa-angle-right');
                }                
			});
        
        /*
        $('.controls > img').click(function(event){            
            var post_template = $(this).prevAll('.of-radio-img-label').html();            
            if(post_template==='post_template1' || post_template==='post_template2'){
                alert('true');
            }else{
                alert('false');
                event.preventDefault();
            }
        });
        */
        
        //Append product review for post
        var count = $('#post_review_count').val();
        $('.docopy-revirew-stars').click(function(){
            count++;
            $('.product_reivew_section').append('<div class="review_section_group">'+
                                                '<span class="apmag_custom_label">Featured Name: </span>'+
                                                '<input style="width: 200px;" type="text" name="product_ratings['+count+'][feature_name]" value="" />'+
                                                '<select name="product_ratings['+count+'][feature_star]">'+
                                                '<option value="">Select rating</option>'+
                                                '<option value="5">5 stars</option>'+
                                                '<option value="4.5">4.5 stars</option>'+
                                                '<option value="4">4 stars</option>'+
                                                '<option value="3.5">3.5 stars</option>'+
                                                '<option value="3">3 stars</option>'+
                                                '<option value="2.5">2.5 stars</option>'+
                                                '<option value="2">2 stars</option>'+
                                                '<option value="1.5">1.5 stars</option>'+
                                                '<option value="1">1 stars</option>'+
                                                '<option value="0.5">0.5 stars</option>'+
                                                '</select>'+
                                                '<a href="javascript:void(0)" class="delete-review-stars button">Delete</a>'+
                                                '</div></div>'
                                                );
        });

    $(document).on('click', '.delete-review-stars', function(){
        $(this).parent('.review_section_group').remove();
    });
});
