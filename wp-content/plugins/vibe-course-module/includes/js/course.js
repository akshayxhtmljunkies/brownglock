;(function($) {
$.fn.timer = function( useroptions ){ 
    var $this = $(this), opt,newVal, count = 0; 

    opt = $.extend( { 
        // Config 
        'timer' : 300, // 300 second default
        'width' : 24 ,
        'height' : 24 ,
        'fgColor' : "#ED7A53" ,
        'bgColor' : "#232323" 
        }, useroptions 
    ); 
    $this.knob({ 
        'min':0, 
        'max': opt.timer, 
        'readOnly': true, 
        'width': opt.width, 
        'height': opt.height, 
        'fgColor': opt.fgColor, 
        'bgColor': opt.bgColor,                 
        'displayInput' : false, 
        'dynamicDraw': false, 
        'ticks': 0, 
        'thickness': 0.1 
    }); 
    setInterval(function(){ 
        newVal = ++count; 
        $this.val(newVal).trigger('change'); 
    }, 1000); 
};



$.urlParam = function(name){
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    if (results==null){
       return null;
    }
    else{
       return results[1] || 0;
    }
}
// Necessary functions
function runnecessaryfunctions(){
  jQuery('.fitvids').fitVids();
  jQuery('.tip').tooltip();
  jQuery('.nav-tabs li:first a').tab('show');
  jQuery('.nav-tabs li a').click(function(event){
    event.preventDefault();
    $(this).tab('show');
  });
  $('audio,video').mediaelementplayer();
  jQuery('.gallery').magnificPopup({
  delegate: 'a',
  type: 'image',
  tLoading: 'Loading image #%curr%...',
  mainClass: 'mfp-img-mobile',
  gallery: {
    enabled: true,
    navigateByImgClick: true,
    preload: [0,1] // Will preload 0 - before current, and 1 after the current image
  },
  image: {
    tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
    titleSrc: function(item) {
      return item.el.attr('title');
    }
  }
});
$('.open_popup_link').magnificPopup({
  type:'inline',
  midClick: true 
});
$('.ajax-popup-link').magnificPopup({
    type: 'ajax',
    alignTop: true,
    fixedContentPos: true,
    fixedBgPos: true,
    overflowY: 'auto',
    closeBtnInside: true,
    preloader: false,
    midClick: true,
    removalDelay: 300,
    mainClass: 'my-mfp-zoom-in'
});
$('.quiz_results_popup').magnificPopup({
    type: 'ajax',
    alignTop: true,
    fixedContentPos: true,
    fixedBgPos: true,
    overflowY: 'auto',
    closeBtnInside: true,
    preloader: false,
    midClick: true,
    removalDelay: 300,
    mainClass: 'my-mfp-zoom-in',
    callbacks: {
             parseAjax: function( mfpResponse ) {
              mfpResponse.data = $(mfpResponse.data).find('#item-body');
            },
            ajaxContentAdded: function(){
              $('.show_explaination').on('click',function(event){
                  event.preventDefault();
                  var $this = $(this);
                  $this.toggleClass('active');
                  $this.closest('li').find('.explaination').toggle();
              });
              $('#prev_results a').on('click',function(event){
                    event.preventDefault();
                    $(this).toggleClass('show');
                    $('.prev_quiz_results').toggleClass('show');
              });
              $('.print_results').click(function(event){
                  event.preventDefault();
                  $('.quiz_result').print();
              });
              $('.quiz_retake_form.start_quiz').on('click',function(e){
                  e.preventDefault();
                  var qid=$('#unit.quiz_title').attr('data-unit');
                  $.ajax({
                      type: "POST",
                      url: ajaxurl,
                      data: { action: 'retake_inquiz', 
                              security: $('#hash').val(),
                              quiz_id:qid,
                            },
                      cache: false,
                      success: function (html) {
                         $('a.unit[data-unit="'+qid+'"]').trigger('click');
                         $.magnificPopup.close();
                         $('#unit'+qid).removeClass('done');
                         var i = localStorage.length;
                          while(i--) {
                            var key = localStorage.key(i);
                            if($.isNumeric(key)) {
                              localStorage.removeItem(key);
                            }  
                          }
                         $('body').find('.course_progressbar').removeClass('increment_complete');
                         $('body').find('.course_progressbar').trigger('decrement');
                      }
                    });
                  
              });
            }
          }
});
$(".live-edit").liveEdit({
      afterSaveAll: function(params) {
        return false;
      }
  });
if ( typeof vc_js == 'function' ) { 
    window.vc_js();
  }

}

//AJAX Comments
function ajaxsubmit_comments(){
  $('#question').each(function(){

   var $this=$(this);
  $('#submit').click(function(event){
    event.preventDefault();
    var value = '';

    $('#ajaxloader').removeClass('disabled');
    $('#question').css('opacity',0.2);

    if($this.find('input[type="radio"]:checked').length)
    $this.find('input[type="radio"]:checked').each(function(){
      value = $(this).val();
    });
    if($this.find('input[type="checkbox"]:checked').length)
    $this.find('input[type="checkbox"]:checked').each(function(){
      value= $(this).val()+','+value;
    });

    if($this.find('.vibe_fillblank').length)
    $this.find('.vibe_fillblank').each(function(){
      value += escape($(this).text());  
    });
    if($this.find('#vibe_select_dropdown').length)
    value = $this.find('#vibe_select_dropdown').val();

    if($this.find('.matchgrid_options li.match_option').length){
        $('.matchgrid_options li.match_option').each(function(){
        var id = $(this).attr('id');
        if( jQuery.isNumeric(id))
          value +=id+',';
      });  
    }

    if($('#comment').hasClass('option_value'))
      $('#comment.option_value').val(value);

    $('#commentform').submit();
  });
    
  var commentform=$('#commentform'); // find the comment form
  var statusdiv=$('#comment-status'); // define the infopanel
  var qid = statusdiv.attr('data-quesid');
  
  commentform.submit(function(){

    var formdata=commentform.serialize();

    statusdiv.html('<p>'+vibe_course_module_strings.processing+'</p>');

    var formurl=commentform.attr('action');

    $.ajax({
      type: 'post',
      url: formurl,
      data: formdata,
      error: function(XMLHttpRequest, textStatus, errorThrown){
        $('#ajaxloader').addClass('disabled');
        $('#question').css('opacity',1);
        statusdiv.html('<p class="wdpajax-error">'+vibe_course_module_strings.too_fast_answer+'</p>');
        setTimeout(function(){statusdiv.hide(300).html('').show();}, 2000);
      },
      success: function(data, textStatus){
        $('#question').css('opacity',1);
        $('#ajaxloader').addClass('disabled');
        if(data=="success"){
          statusdiv.html('<p class="ajax-success" >'+vibe_course_module_strings.answer_saved+'</p>');
          setTimeout(function(){statusdiv.hide(300).html('').show();}, 2000);
          $('#ques'+qid).addClass('done');
          $('.reset_answer').removeClass('hide');
        }
        else{
          statusdiv.html('<p class="ajax-error" >'+vibe_course_module_strings.saving_answer+'</p>'); 
          setTimeout(function(){statusdiv.hide(300).html('').show();}, 2000);
        }
      }
    });
    return false;
    });
  }); 
} // END Function

//Cookie evaluation
jQuery(document).ready( function($) {
  $('.open_popup_link').magnificPopup({
    type:'inline',
    midClick: true 
  });
  $('.item-list').each(function(){
    var cookie_name = 'bp-'+$('.item-list').attr('id');
    var cookieValue = $.cookie(cookie_name);
    if ((cookieValue !== null) && cookieValue == 'grid') {      
      $('.item-list').addClass('grid');
      $('#list_view').removeClass('active');
      $('#grid_view').addClass('active');
    }
  });
  
  $('.datepicker').datepicker({
      dateFormat: 'yy-mm-dd'
  });

function bp_course_extras_cookies(){
  $('.category_filter .bp-course-category-filter,.type_filter .bp-course-free-filter,.level_filter .bp-course-level-filter,.location_filter .bp-course-location-filter,.instructor_filter .bp-course-instructor-filter,.date_filter .bp-course-date-filter').on('change',function(){
     var category_filter=[];
     $('.bp-course-category-filter:checked').each(function(){
        var category={'type':'course-cat','value':$(this).val()};
        category_filter.push(category);
     });
     $('.bp-course-date-filter').each(function(){
        if($(this).val().length){
          var date={'type':$(this).attr('data-type'),'value':$(this).val()};    
        }
        console.log(date);
        category_filter.push(date);
     });
     $('.bp-course-free-filter:checked').each(function(){
      var free={'type':'free','value':$(this).val()};
        category_filter.push(free);
     });
     $('.bp-course-level-filter:checked').each(function(){
      var level={'type':'level','value':$(this).val()};
        category_filter.push(level);
     });
     $('.bp-course-location-filter:checked').each(function(){
      var location={'type':'location','value':$(this).val()};
        category_filter.push(location);
     });
     $('.bp-course-instructor-filter:checked').each(function(){
      var level={'type':'instructor','value':$(this).val()};
        category_filter.push(level);
     });
     $.cookie('bp-course-extras', JSON.stringify(category_filter), { expires: 1 ,path: '/'});
  });
}

function bp_course_category_filter_cookie(){
    var category_filter_cookie =  $.cookie("bp-course-extras");

    if (typeof category_filter_cookie !== "undefined" && (category_filter_cookie !== null) ) { 
        var category_filter = JSON.parse(category_filter_cookie);
        if(typeof category_filter != 'object'){
          return;
        }
        if($('#active_filters').length){
          $('#active_filters').fadeIn(200);
        }else{
          $('#course-dir-list').before('<ul id="active_filters"><li>'+vibe_course_module_strings.active_filters+'</li></ul>');
        }

        //Detect and activate specific filters
        jQuery.each(category_filter, function(index, item) {
          if(item !== null){
            if($('input[data-type="'+item['type']+'"]').attr('type') == 'text'){
             $('input[data-type="'+item['type']+'"]').val(item['value']);
              var id = $('input[data-type="'+item['type']+'"]').attr('data-type');
              var text = $('input[data-type="'+item['type']+'"]').attr('placeholder')+' : '+item['value'];
            }else{
              $('input[value="'+item['value']+'"]').prop('checked', true);
              var id = $('input[value="'+item['value']+'"]').attr('id');
              var text = $('label[for="'+id+'"]').text();
            }
            
            if(!$('#active_filters span[data-id="'+id+'"]').length)
              $('#active_filters').append('<li><span data-id="'+id+'">'+text+'</span></li>');

          }
        });
        // Delete a specific filter
        $('#active_filters li span').on('click',function(){
           var id = $(this).attr('data-id');
           $(this).parent().fadeOut(200,function(){
            $(this).remove();
            if($('#active_filters li').length < 3)
              $('#active_filters').fadeOut(200);
            else    
              $('#active_filters').fadeIn(200);
          });
           if($('#'+id).length){
              if($('#'+id).attr('type') == 'checkbox'){
                $('#'+id).prop('checked',false);     
              }
              if($('#'+id).attr('type') == 'radio'){
                $('#'+id).prop('checked',false);     
              }
              if($('#'+id).attr('type') == 'text'){
                $('#'+id).val('');
              }
           }
           /*===== */ 
           var category_filter=[];
           $('.bp-course-category-filter:checked').each(function(){
              var category={'type':'course-cat','value':$(this).val()};
              category_filter.push(category);
           });
           $('.bp-course-free-filter:checked').each(function(){
            var free={'type':'free','value':$(this).val()};
              category_filter.push(free);
           });
           $('.bp-course-level-filter:checked').each(function(){
            var level={'type':'level','value':$(this).val()};
              category_filter.push(level);
           });
           $('.bp-course-location-filter:checked').each(function(){
            var location={'type':'location','value':$(this).val()};
              category_filter.push(location);
           });
           $('.bp-course-instructor-filter:checked').each(function(){
            var level={'type':'instructor','value':$(this).val()};
              category_filter.push(level);
           });
           $.cookie('bp-course-extras', JSON.stringify(category_filter), { expires: 1 ,path: '/'});
           $('#submit_filters').trigger('click');
           /* ==== */
        });

        if(!$('#active_filters .all-filter-clear').length)
            $('#active_filters').append('<li class="all-filter-clear">'+vibe_course_module_strings.clear_filters+'</li>');

        // Clear all Filters link
        $('#active_filters li.all-filter-clear').click(function(){
            $('#active_filters li').each(function(){
              var span = $(this).find('span');
               var id = span.attr('data-id');
               span.parent().fadeOut(200,function(){
                  $(this).remove(); });
               if($('#'+id).attr('type') == 'text'){
                 $('#'+id).val('');
               }else{
                  $('#'+id).prop('checked',false);
               }
              $('#active_filters').fadeOut(200,function(){
                $(this).remove();
              });   
              $.removeCookie('bp-course-extras', { path: '/' });
              $('#submit_filters').trigger('click');
            });
        });
        // End Clear All
           // Hide is no filter active
        if($('#active_filters li').length < 3)
          $('#active_filters').fadeOut(200);
        else    
          $('#active_filters').fadeIn(200);
    }
}


bp_course_category_filter_cookie();
bp_course_extras_cookies();

/*=========================================================================*/

  $('.category_filter li > span,.category_filter li > label').click(function(event){
    var parent= $(this).parent();
    $(this).parent().find('span').toggleClass('active');
    parent.find('ul.sub_categories').toggle(300);
  });
  
  $('#submit_filters').on('click',function(){ 
      if ( jq('.item-list-tabs li.selected').length )
      var el = jq('.item-list-tabs li.selected');
      else
        var el = jq(this);

      var css_id = el.attr('id').split('-');
      var object = css_id[0];
      var scope = css_id[1];
      var filter = jq(this).val();
      var search_terms = false;

      if ( jq('.dir-search input').length )
        search_terms = jq('.dir-search input').val();

      if ( 'friends' == object )
        object = 'members';

      bp_course_extras_cookies();
      bp_filter_request( object, filter, scope, 'div.' + object, search_terms, 1, jq.cookie('bp-' + object + '-extras') );
      bp_course_category_filter_cookie();
      return false;
  });

  $('.quiz_results_popup').magnificPopup({
      type: 'ajax',
      alignTop: true,
      fixedContentPos: true,
      fixedBgPos: true,
      overflowY: 'auto',
      closeBtnInside: true,
      preloader: false,
      midClick: true,
      removalDelay: 300,
      mainClass: 'my-mfp-zoom-in',
      callbacks: {
          parseAjax: function( mfpResponse ) {
                mfpResponse.data = $(mfpResponse.data).find('#item-body');
              },
          ajaxContentAdded: function() {        
                $('#prev_results a').on('click',function(event){
                    event.preventDefault();
                    $(this).toggleClass('show');
                    $('.prev_quiz_results').toggleClass('show');
                });
                $('.print_results').click(function(event){
                    event.preventDefault();
                    $('.quiz_result').print();
                });
                $('.quiz_retake_form.start_quiz').on('click',function(e){
                    e.preventDefault();
                    var qid=$('#unit.quiz_title').attr('data-unit');
                    $.ajax({
                        type: "POST",
                        url: ajaxurl,
                        data: { action: 'retake_inquiz', 
                                security: $('#hash').val(),
                                quiz_id:qid,
                              },
                        cache: false,
                        success: function (html) {
                           $('a.unit[data-unit="'+qid+'"]').trigger('click');
                           $.magnificPopup.close();
                           $('#unit'+qid).removeClass('done');
                           $('body').find('.course_progressbar').removeClass('increment_complete');
                           $('body').find('.course_progressbar').trigger('decrement');
                        }
                      });
                    
                });
            }
      }      
  });    
  $('#grid_view').click(function(){
    if(!$('.item-list').hasClass('grid')){
      $('.item-list').addClass('grid');
    }
    var cookie_name = 'bp-'+$('.item-list').attr('id');
    $.cookie(cookie_name, 'grid', { expires: 2 ,path: '/'});
    $('#list_view').removeClass('active');
    $(this).addClass('active');
  });
  $('#list_view').click(function(){
    $('.item-list').removeClass('grid');
    var cookie_name = 'bp-'+$('.item-list').attr('id');
    $.cookie(cookie_name, 'list', { expires: 2 ,path: '/'});
    $('#grid_view').removeClass('active');
    $(this).addClass('active');
  });

  $("#average .dial").knob({
      'readOnly': true, 
      'width': 120, 
      'height': 120, 
      'fgColor': vibe_course_module_strings.theme_color, 
      'bgColor': '#f6f6f6',   
      'thickness': 0.1
  });
  $("#pass .dial").knob({
      'readOnly': true, 
      'width': 120, 
      'height': 120, 
      'fgColor': vibe_course_module_strings.theme_color, 
      'bgColor': '#f6f6f6',   
      'thickness': 0.1
  });
  $("#badge .dial").knob({
      'readOnly': true, 
      'width': 120, 
      'height': 120, 
      'fgColor': vibe_course_module_strings.theme_color, 
      'bgColor': '#f6f6f6',   
      'thickness': 0.1
  });

  $(".course_quiz .dial").knob({
      'readOnly': true, 
      'width': 120, 
      'height': 120, 
      'fgColor': vibe_course_module_strings.theme_color, 
      'bgColor': '#f6f6f6',   
      'thickness': 0.1 
  });

  //RESET Ajx
$( 'body' ).delegate( '.remove_user_course','click',function(event){
      event.preventDefault();
      var course_id=$(this).attr('data-course');
      var user_id=$(this).attr('data-user');
      $(this).addClass('animated spin');
      var $this = $(this);
      $.confirm({
          text: vibe_course_module_strings.remove_user_text,
          confirm: function() {
             $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: { action: 'remove_user_course',
                            security: $('#security').val(),
                            id: course_id,
                            user: user_id
                          },
                    cache: false,
                    success: function (html) {
                        $(this).removeClass('animated');
                        $(this).removeClass('spin');
                        runnecessaryfunctions();
                        $('#message').html(html);
                        $('#s'+user_id).fadeOut('fast');
                    }
            });
          },
          cancel: function() {
              $this.removeClass('animated');
              $this.removeClass('spin');
          },
          confirmButton: vibe_course_module_strings.remove_user_button,
          cancelButton: vibe_course_module_strings.cancel
      });
  });

$( 'body' ).delegate( '.reset_course_user','click',function(event){
      event.preventDefault();
      var course_id=$(this).attr('data-course');
      var user_id=$(this).attr('data-user');
      $(this).addClass('animated spin');
      var $this = $(this);
      $.confirm({
        text: vibe_course_module_strings.reset_user_text,
          confirm: function() {
          $.ajax({
                  type: "POST",
                  url: ajaxurl,
                  data: { action: 'reset_course_user', 
                          security: $('#security').val(),
                          id: course_id,
                          user: user_id
                        },
                  cache: false,
                  success: function (html) {
                      $this.removeClass('animated');
                      $this.removeClass('spin');

                      var cookie_id = 'course_progress'+course_id;
                      $.removeCookie(cookie_id,{ path: '/' });

                      $('#message').html(html);
                  }
          });
         }, 
         cancel: function() {
              $this.removeClass('animated');
              $this.removeClass('spin');
          },
          confirmButton: vibe_course_module_strings.reset_user_button,
          cancelButton: vibe_course_module_strings.cancel
        });
  });

  
$( 'body' ).delegate( '.tip.course_stats_user', 'click', function(event){
      event.preventDefault();
      var $this=$(this);
      var course_id=$this.attr('data-course');
      var user_id=$this.attr('data-user');
      
      if($this.hasClass('already')){
        $('#s'+user_id).find('.course_stats_user').fadeIn('fast');
      }else{
          $this.addClass('animated spin');    
        $.ajax({
                type: "POST",
                url: ajaxurl,
                data: { action: 'course_stats_user', 
                        security: $('#security').val(),
                        id: course_id,
                        user: user_id
                      },
                cache: false,
                success: function (html) {
                    $this.removeClass('animated');
                    $this.removeClass('spin');
                    $this.addClass('already');
                    $('#s'+user_id).append(html);
                    $('.course_students').trigger('load_quiz_results');
                    $(".dial").knob({
                      'readOnly': true, 
                  'width': 160, 
                  'height': 160, 
                  'fgColor': vibe_course_module_strings.theme_color, 
                  'bgColor': '#f6f6f6',   
                  'thickness': 0.3 
                    });
                }
        });
      }
  });
  
$('.course_students').on('load_quiz_results',function(){
    $('.check_user_quiz_results').click(function(){
        $.ajax({
                type: "POST",
                url: ajaxurl,
                data: { action : 'check_user_quiz_results',
                        quiz : $(this).attr('data-quiz'),
                        user     :$(this).attr('data-user'),
                        security : $('#security').val()
                      },
                cache: false,
                success: function (html) {
                    //$('.check_user_quiz_results').append('<div class="quiz_results_wrapper hide">'+html+'</div>');
                    $.magnificPopup.open({
                        items: {
                            src: $('<div id="item-body">'+html+'</div>'),
                            type: 'inline'
                        }
                    });
                }
        });
    });
});
  
  $('.data_stats li').click(function(event){
    event.preventDefault();
    var defaultxt = $(this).html();
    var content = $('.content');
    var $this = $(this);
    var id = $(this).attr('id');

    if(id == 'desc'){
      $('.main_content').show();
      $('.stats_content').hide();
    }else{
      if($(this).hasClass('loaded')){
        $('.main_content').hide();
        $('.stats_content').show();
      }else{
         $this.addClass('loaded');  
         $('.main_content').hide();
         $(this).html('<i class="icon-sun-stroke"></i>');
         var quiz_id = $this.parent().attr('data-id');
         var cpttype = $this.parent().attr('data-type');
         $.ajax({
                type: "POST",
                url: ajaxurl,
                data: { action: 'load_stats', 
                        cpttype: cpttype,
                        id: quiz_id
                      },
                cache: false,
                success: function (html) {
                    $('.main_content').after(html);
                    setTimeout(function(){$this.html(defaultxt); }, 1000);
                }
        });
      }
    }
    $this.parent().find('.active').removeClass('active');
    $this.addClass('active');
  });

  $('#calculate_avg_course').click(function(event){
      event.preventDefault();
      var course_id=$(this).attr('data-courseid');
      $(this).addClass('animated spin');

      $.ajax({
              type: "POST",
              url: ajaxurl,
              data: { action: 'calculate_stats_course', 
                      security: $('#security').val(),
                      id: course_id
                    },
              cache: false,
              success: function (html) {
                  $(this).removeClass('animated');
                  $(this).removeClass('spin');
                  $('#message').html(html);
                   setTimeout(function(){location.reload();}, 3000);
              }
      });

  });

  $('.reset_quiz_user').click(function(event){
      event.preventDefault();
      var course_id=$(this).attr('data-quiz');
      var user_id=$(this).attr('data-user');
      $(this).addClass('animated spin');
      var $this = $(this);
      $.confirm({
          text: vibe_course_module_strings.quiz_rest,
          confirm: function() {

      $.ajax({
              type: "POST",
              url: ajaxurl,
              data: { action: 'reset_quiz', 
                      security: $('#qsecurity').val(),
                      id: course_id,
                      user: user_id
                    },
              cache: false,
              success: function (html) {
                  $(this).removeClass('animated');
                  $(this).removeClass('spin');
                  $('#message').html(html);
                  $('#qs'+user_id).fadeOut('fast');
              }
      });
      }, 
       cancel: function() {
            $this.removeClass('animated');
            $this.removeClass('spin');
        },
        confirmButton: vibe_course_module_strings.quiz_rest_button,
        cancelButton: vibe_course_module_strings.cancel
      });
  });

  $('.evaluate_quiz_user').click(function(event){
      event.preventDefault();
      var quiz_id=$(this).attr('data-quiz');
      var user_id=$(this).attr('data-user');
      $(this).addClass('animated spin');

      $.ajax({
              type: "POST",
              url: ajaxurl,
              data: { action: 'evaluate_quiz', 
                      security: $('#qsecurity').val(),
                      id: quiz_id,
                      user: user_id
                    },
              cache: false,
              success: function (html) {
                  $(this).removeClass('animated');
                  $(this).removeClass('spin');
                  $('.quiz_students').html(html);
                  calculate_total_marks();
              }
      });
  });


 $('.evaluate_course_user').click(function(event){
      event.preventDefault();
      var course_id=$(this).attr('data-course');
      var user_id=$(this).attr('data-user');
      $(this).addClass('animated spin');

      $.ajax({
              type: "POST",
              url: ajaxurl,
              data: { action: 'evaluate_course', 
                      security: $('#security').val(),
                      id: course_id,
                      user: user_id
                    },
              cache: false,
              success: function (html) {
                  $(this).removeClass('animated');
                  $(this).removeClass('spin');
                  $('.course_students').html(html);
                  calculate_total_marks();
              }
      });
  });

$( 'body' ).delegate( '.reset_answer', 'click', function(event){
       event.preventDefault();
      var ques_id=$('#comment-status').attr('data-quesid');
      var $this = $(this);
      var qid = $('#comment-status').attr('data-quesid');
      $this.prepend('<i class="icon-sun-stroke animated spin"></i>');
      $.ajax({
              type: "POST",
              url: ajaxurl,
              data: { action: 'reset_question_answer', 
                      security: $this.attr('data-security'),
                      ques_id: ques_id,
                    },
              cache: false,
              success: function (html) {
                  $this.find('i').remove();
                   $('#comment-status').html(html);
                   $('#ques'+qid).removeClass('done');
                   setTimeout(function(){ $this.addClass('hide');}, 500);
              }
      });
});

$( 'body' ).delegate( '#course_complete', 'click', function(event){
      event.preventDefault();
      var $this=$(this);
      var user_id=$this.attr('data-user');
      var course = $this.attr('data-course');
      var marks = parseInt($('#course_marks_field').val());
      if(marks <= 0){
        alert('Enter Marks for User');
        return;
      }

      $this.prepend('<i class="icon-sun-stroke animated spin"></i>');
      $.ajax({
              type: "POST",
              url: ajaxurl,
              data: { action: 'complete_course_marks', 
                      security: $('#security').val(),
                      course: course,
                      user: user_id,
                      marks:marks
                    },
              cache: false,
              success: function (html) {
                  $this.find('i').remove();
                  $this.html(html);
              }
      });
});

  // Registeration BuddyPress
  $('.register-section h4').click(function(){
      $(this).toggleClass('show');
      $(this).parent().find('.editfield').toggle('fast');
  });

});

$( 'body' ).delegate( '.hide_parent', 'click', function(event){
  $(this).parent().fadeOut('fast');
});


$( 'body' ).delegate( '.give_marks', 'click', function(event){
      event.preventDefault();
      var $this=$(this);
      var ansid=$this.attr('data-ans-id');
      var aval = $('#'+ansid).val();
      $this.prepend('<i class="icon-sun-stroke animated spin"></i>');
      $.ajax({
              type: "POST",
              url: ajaxurl,
              data: { action: 'give_marks', 
                      aid: ansid,
                      aval: aval
                    },
              cache: false,
              success: function (html) {
                  $this.find('i').remove();
                  $this.html(vibe_course_module_strings.marks_saved);
              }
      });
});

$( 'body' ).delegate( '#mark_complete', 'click', function(event){
    event.preventDefault();
    var $this=$(this);
    var quiz_id=$this.attr('data-quiz');
    var user_id = $this.attr('data-user');
    var marks = parseInt($('#total_marks strong > span').text());
    $this.prepend('<i class="icon-sun-stroke animated spin"></i>');
    $.ajax({
            type: "POST",
            url: ajaxurl,
            data: { action: 'save_quiz_marks', 
                    quiz_id: quiz_id,
                    user_id: user_id,
                    marks: marks,
                  },
            cache: false,
            success: function (html) {
                $this.find('i').remove();
                $this.html(vibe_course_module_strings.quiz_marks_saved);
            }
    });
});

function calculate_total_marks(){
  $('.question_marks').on('keyup',function(){
      var marks=parseInt(0);
      var $this = $('#total_marks strong > span');
      $('.question_marks').each(function(){
          if($(this).val())
            marks = marks + parseInt($(this).val());
        });
      $this.html(marks);
  });
}


$( 'body' ).delegate( '.submit_quiz', 'click', function(event){
    event.preventDefault();
    $('#question').css('opacity',0.2);
    $('#ajaxloader').removeClass('disabled');
    if($(this).hasClass('disabled')){
      return false;
    }
  var $this = $(this);

  $this.prepend('<i class="icon-sun-stroke animated spin"></i>');
  $('#question').addClass('quiz_submitted_fade');

  var quiz_id=$(this).attr('data-quiz');  
  var answers=[];
  var unanswered_flag=0;
  $.each(all_questions_json, function(key, val) {
      var ans = localStorage.getItem(val);
      if(ans){
        var answer={'id':val,'value':ans};
        answers.push(answer); 
        localStorage.removeItem(val);
      }else{
        unanswered_flag++;
      }
  });

  $.ajax({
    type: "POST",
    url: ajaxurl,
    async: true,
    data: { action: 'submit_quiz', 
            security: $('#start_quiz').val(),
            quiz_id:quiz_id,
            answers:JSON.stringify(answers)
          },
    cache: false,
    success: function (html) {
      $('#content').append(html);
        window.location.assign(document.URL);
    }
  });
    
});


// QUIZ RELATED FUCNTIONS
// START QUIZ AJAX
jQuery(document).ready( function($) {
  $('.begin_quiz').click(function(event){
      var $this = $(this);
      if(!$this.hasClass('begin_quiz'))
        return;

      event.preventDefault();
      var quiz_id=$(this).attr('data-quiz');
      $this.prepend('<i class="icon-sun-stroke animated spin"></i>');
      $.ajax({
              type: "POST",
              url: ajaxurl,
              data: { action: 'begin_quiz', 
                      start_quiz: $('#start_quiz').val(),
                      id: quiz_id
                    },
              cache: false,
              success: function (html) {
                  $this.find('i').remove();
                  $('.content').fadeOut("fast");
                  $('.content').html(html);
                  $('.content').fadeIn("fast");
                  ajaxsubmit_comments();
                  var ques=$($.parseHTML(html)).filter("#question");
                  var q='#ques'+ques.attr('data-ques');
                  var checkquestions = [];
                  $('.quiz_question').each(function(){
                      var qid = $(this).attr('data-qid');
                      var value = localStorage.getItem(qid);
                      if(value !=null){
                        $('#ques'+qid).addClass('done');
                      }else{
                        var question_id={'id':qid};
                        checkquestions.push(question_id);
                      }
                  });
                  if(checkquestions.length){
                    $.ajax({
                        type: "POST",
                        url: ajaxurl,
                        data: { action: 'check_unanswered_questions', 
                                id: quiz_id,
                                questions:JSON.stringify(checkquestions),
                              },
                        cache: false,
                        success: function(json){ 
                          json = jQuery.parseJSON(json);
                          $.each(json,function(i,item){
                              $('#ques'+item.question_id).addClass('done');
                              localStorage.setItem(item.question_id,item.value);
                          });
                        }
                    });                    
                  }

                  $('.quiz_timeline').find('.active').removeClass('active');
                  $(q).addClass('active');
                  $('#question').trigger('question_loaded');
                  if(ques != 'undefined'){
                    $('.quiz_timer').trigger('activate');
                  }
                  runnecessaryfunctions();
                  $('.begin_quiz').each(function(){
                      $(this).removeClass('begin_quiz');
                      $(this).addClass('submit_quiz');
                      $(this).text(vibe_course_module_strings.submit_quiz);
                  });
            }
        });
  });
});

$( 'body' ).delegate( '.show_hint', 'click', function(event){
  event.preventDefault();
  $(this).toggleClass('active');
  $(this).parent().find('.hint').toggle(400);
});

$('.show_explaination').click(function(event){
    event.preventDefault();
    var $this = $(this);
    $this.toggleClass('active');
    $this.closest('li').find('.explaination').toggle();
});

$( 'body' ).delegate( '.quiz_question', 'click', function(event){
    event.preventDefault();
    var $this = $(this);
    var quiz_id=$(this).attr('data-quiz');
    var ques_id=$(this).attr('data-qid');
    $this.prepend('<i class="icon-sun-stroke animated spin"></i>');
    $('#ajaxloader').removeClass('disabled');
    $('#question').css('opacity',0.2);
    $.ajax({
            type: "POST",
            url: ajaxurl,
            data: { action: 'quiz_question', 
                    start_quiz: $('#start_quiz').val(),
                    quiz_id: quiz_id,
                    ques_id: ques_id
                  },
            cache: false,
            success: function (html) {
                $this.find('i').remove();
                $('.content').html(html);
                $('#ajaxloader').addClass('disabled');
                $('#question').css('opacity',1);
                ajaxsubmit_comments();
                var ques=$($.parseHTML(html)).filter("#question");
                var q='#ques'+ques.attr('data-ques');
                $('.quiz_timeline').find('.active').removeClass('active');
                $(q).addClass('active');
                $('#question').trigger('question_loaded');
                $('.tip').tooltip();
                //if(ques != 'undefined')
                  //$('.quiz_timer').trigger('activate'); 
                $('audio,video').mediaelementplayer();
                //END Match question type
                //
                if($('.timeline_wrapper').height() > $('.quiz_timeline').height()){
                     $('.quiz_timeline').animate({scrollTop: $(q).position().top}, 'slow');
                }
            }
      });
});

$( 'body' ).delegate( '#question', 'question_loaded',function(){
    runnecessaryfunctions();
    var $this = $(this);
    var question_id = $this.attr('data-ques');
    var marked_value = $this.find('#question_marked_answer'+question_id).val();
    var value = localStorage.getItem(question_id);
    if(value == null && marked_value.length){
      value = marked_value;
      localStorage.setItem(question_id,marked_value);
      if(!$('#ques'+question_id).hasClass('done'))
          $('#ques'+question_id).addClass('done');
    }

    if($(this).find('.question_options.truefalse').length){
      
      if(value!= null){
        $(this).find('input[value="'+value+'"]').attr('checked','checked');
      }

      $('.question_options.truefalse').click(function(){
        $this = $('.question_options.truefalse');
        if($this.find('input[type="radio"]:checked').length)
        $this.find('input[type="radio"]:checked').each(function(){
          value = $(this).val();
          localStorage.setItem(question_id,value);
          $('#ques'+question_id).addClass('done');
        });
      });
    }

    if($(this).find('.question_options.single').length){
      
      if(value!= null){
        $(this).find('input[value="'+value+'"]').attr('checked','checked');
      }

      $('.question_options.single').click(function(){
        $this = $('.question_options.single');
        if($this.find('input[type="radio"]:checked').length)
        $this.find('input[type="radio"]:checked').each(function(){
          value = $(this).val();
          localStorage.setItem(question_id,value);
          $('#ques'+question_id).addClass('done');
        });
      });
    }
    
     if($(this).find('.question_options.multiple').length){

      
      if(value!=null){
        var new_vals = value.split(',');
        $.each(new_vals,function(k,vals){
          $this.find('input[value="'+vals+'"]').prop( "checked", true );
        });
      }

        $('.question_options.multiple').click(function(){
          $this = $('.question_options.multiple');
          value = '';
          if($this.find('input[type="checkbox"]:checked').length)
          $this.find('input[type="checkbox"]:checked').each(function(){
            value= $(this).val()+','+value;
          });
          localStorage.setItem(question_id,value);
          $('#ques'+question_id).addClass('done');
          });
      }
    
    if($this.find('.single_text').length){

      if(value != null){
        $this.find('.single_text input[type="text"]').val(value);
      }
    }
      $('.single_text input[type="text"]').on('keyup',function(){
        var value = $(this).val();
        localStorage.setItem(question_id,value);
        $('#ques'+question_id).addClass('done');
      });
    
    if($this.find('.vibe_fillblank').length){
      if(value != null ){
        $(this).find('.vibe_fillblank').text(value);
      }
    }

      $('.vibe_fillblank').on('keyup',function(){
        $(this).each(function(){
          value = escape($(this).text().trim()); 
        });
         localStorage.setItem(question_id,value);
        $('#ques'+question_id).addClass('done');
      });

 
    if($this.find('.essay_text textarea').length){
      if(value != null ){
        $this.find('.essay_text textarea').val(value);
      }
    } 

    $('.essay_text textarea').on('keyup',function(){
        var value = $(this).val();
        localStorage.setItem(question_id,value);
        $('#ques'+question_id).addClass('done');
     });
     
    
    if($(this).find('#vibe_select_dropdown').length){
       if(value != null){
          $('#vibe_select_dropdown').val(value);
       }else{
          $('#vibe_select_dropdown').val('');
       }

       $('#vibe_select_dropdown').change(function(){
          var value = $('#vibe_select_dropdown').val();
          localStorage.setItem(question_id,value);
          $('#ques'+question_id).addClass('done');
        });
    }
   

    if($(this).find('.matchgrid_options li.match_option').length){

        $('.matchgrid_options li.match_option').each(function(){
          var id = $(this).attr('id');
          if( jQuery.isNumeric(id))
            value +=id+',';
        });  
        localStorage.setItem(question_id,value);
    }

  jQuery('.question_options.sort').each(function(){

    if(value != null){
      var new_vals = value.split(',');
      var $ul = $(".question_options"),
          $items = $(".question_options").children();
      for (var i = new_vals[new_vals.length - 1]; i >= 0; i--) {
          $ul.prepend( $items.get(new_vals[i] - 1));
      }
    }else{
        var defaultanswer='1';
        var lastindex = $('ul.question_options li').size();
        if(lastindex>1)
        for(var i=2;i<=lastindex;i++){
          defaultanswer = defaultanswer+','+i;
        }
        localStorage.setItem(question_id,defaultanswer);
    }
    $('#comment').val(defaultanswer);
    $('#comment').trigger('change');
    jQuery('.question_options.sort').sortable({
      revert: true,
      cursor: 'move',
      refreshPositions: true, 
      opacity: 0.6,
      scroll:true,
      containment: 'parent',
      placeholder: 'placeholder',
      tolerance: 'pointer',
      update: function( event, ui ) {
          var order = $('.question_options.sort').sortable('toArray').toString();
          localStorage.setItem(question_id,order);
          $('#ques'+question_id).addClass('done');
      }
    }).disableSelection();

  });
  //Fill in the Blank Live EDIT

  $(".live-edit").liveEdit({
      afterSaveAll: function(params) {
        return false;
      }
  });

  if($('.question_options.match').length){

    //Match question type
    $('.question_options.match').droppable({
      drop: function( event, ui ){
        $(ui.draggable).removeAttr('style');
        $( this )
              .addClass( "ui-state-highlight" )
              .append($(ui.draggable))
      }
    });
    $('.question_options.match li').draggable({
      revert: "invalid",
      containment:'#question'
    });
    $( ".matchgrid_options li" ).droppable({
        activeClass: "ui-state-default",
        hoverClass: "ui-state-hover",
        drop: function( event, ui ){
          childCount = $(this).find('li').length;
          $('#ques'+question_id).addClass('done');
          $(ui.draggable).removeAttr('style');
          if (childCount !=0){
              return;
          }  
          
           $( this )
              .addClass( "ui-state-highlight" )
              .append($(ui.draggable))
         var value='';
         var a = [];
          $(this).parent().find('li.match_option').each(function(){
              var id = $(this).attr('id');
              if( jQuery.isNumeric(id))
                a.push(id);
          });  
          value = a.join(',');
          localStorage.setItem(question_id,value);     
        }

      });
  
      var id;
      $('.matchgrid_options li').each(function(index,value){
          id = $('.matchgrid_options').attr('data-match'+index);
          $(this).append($('#'+id));
          $('#ques'+question_id).addClass('done');
      });

      if(value != null){
        var new_vals = value.split(',');

        var $ul = $(".question_options.match"),
            $items = $(".question_options.match").children();
        for (var i = (new_vals.length - 1); i >= 0; i--) { 
           $('.matchgrid_options ul li').eq(i).append($items.get(new_vals[i]-1));
        }
      }
    }
});



jQuery(document).ready( function($) {
 

  $('.quiz_timer').one('activate',function(){

    var qtime = parseInt($(this).attr('data-time'));

    var $timer =$(this).find('.timer');
    var $this=$(this);
    
    $timer.timer({
      'timer': qtime,
      'width' : 200 ,
      'height' : 200 ,
      'fgColor' : vibe_course_module_strings.theme_color ,
      'bgColor' : vibe_course_module_strings.single_dark_color 
    });

    var $timer =$(this).find('.timer');

    $timer.on('change',function(){
        var countdown= $this.find('.countdown');
        var val = parseInt($timer.attr('data-timer'));
        if(val > 0){
          val--;
          $timer.attr('data-timer',val);
          var $text='';
          if(val > 60){
            $text = Math.floor(val/60) + ':' + ((parseInt(val%60) < 10)?'0'+parseInt(val%60):parseInt(val%60)) + '';
          }else{
            $text = '00:'+ ((val < 10)?'0'+val:val);
          }

          countdown.html($text);
        }else{
            countdown.html(vibe_course_module_strings.theme_color);
            if(!$('.submit_quiz').hasClass('triggerred')){
                $('.submit_quiz').trigger('click');
                $('.submit_quiz').addClass('triggerred');
            } 

            $('.quiz_timer').trigger('end');
        }  
    });
    
  });

  $('.quiz_timer').one('deactivate',function(){
    var qtime = parseInt($(this).attr('data-time'));
    var $timer =$(this).find('.timer');
    var $this=$(this);
    
    $timer.knob({
        'readonly':true,
        'max': qtime,
        'width' : 200 ,
        'height' : 200 ,
        'fgColor' : vibe_course_module_strings.theme_color ,
        'bgColor' : vibe_course_module_strings.single_dark_color,
        'thickness': 0.2 ,
        'readonly':true 
      });
    event.stopPropagation();
  });

  $('.quiz_timer').one('end',function(event){
    var qtime = parseInt($(this).attr('data-time'));
    var $timer =$(this).find('.timer');
    var $this=$(this);
    
    $timer.knob({
        'readonly':true,
        'max': qtime,
        'width' : 200 ,
        'height' : 200 ,
        'fgColor' : vibe_course_module_strings.theme_color ,
        'bgColor' : vibe_course_module_strings.single_dark_color,
        'thickness': 0.2 ,
        'readonly':true 
      });
    event.stopPropagation();
  });
// Timer function runs after Trigger event definition
$('.quiz_timer').each(function(){
    var qtime = parseInt($(this).attr('data-time'));
    var $timer =$(this).find('.timer');
    $timer.knob({
      'readonly':true,
      'max': qtime,
      'width' : 200 ,
      'height' : 200 ,
      'fgColor' : vibe_course_module_strings.theme_color ,
      'bgColor' : vibe_course_module_strings.single_dark_color,
      'thickness': 0.2 ,
      'readonly':true 
    });
    if($(this).hasClass('start')){
      $('.quiz_timer').trigger('activate');
    }
});

jQuery('.question_options.sort').each(function(){
    var defaultanswer='1';
    var lastindex = $('ul.question_options li').size();
    if(lastindex>1)
    for(var i=2;i<=lastindex;i++){
      defaultanswer = defaultanswer+','+i;
    }
    $('#comment').val(defaultanswer);
    $('#comment').trigger('change');
    jQuery('.question_options.sort').sortable({
      revert: true,
      cursor: 'move',
      refreshPositions: true, 
      opacity: 0.6,
      scroll:true,
      containment: 'parent',
      placeholder: 'placeholder',
      tolerance: 'pointer',
      update: function( event, ui ) {
          var order = $('.question_options.sort').sortable('toArray').toString();
          $('#comment').val(order);
          $('#comment').trigger('change');
      }
    }).disableSelection();
  });
}); 

$( 'body' ).on( 'click','.expand_message',function(event){
  event.preventDefault();
  $('.bulk_message').toggle('slow');
});

$('body').on('click','.expand_change_status',function(event){
  event.preventDefault();
  $('.bulk_change_status').toggle('slow');
  $('#status_action').on('change',function(){
      if($(this).val() === 'finish_course' ){
          $('#finish_marks').removeClass('hide');
      }else{
        $('#finish_marks').addClass('hide');
      }
  });
});

$( 'body' ).on( 'click','.expand_add_students',function(event){
  event.preventDefault();
  $('.bulk_add_students').toggle('slow');
});

$( 'body' ).on( 'click','.expand_assign_students', function(event){
  event.preventDefault();
  $('.bulk_assign_students').toggle('slow');
});

$( 'body' ).on( 'click','.extend_subscription_students', function(event){
  event.preventDefault();
  $('.bulk_extend_subscription_students').toggle('slow');
});


$( 'body' ).delegate( '#send_course_message', 'click', function(event){
  event.preventDefault();
  var members=[];

  var $this = $(this);
  var defaultxt=$this.html();
  $this.html('<i class="icon-sun-stroke animated spin"></i> '+vibe_course_module_strings.sending_messages);
  var i=0;
  $('.member').each(function(){
    if($(this).is(':checked')){
      members[i]=$(this).val();
      i++;
    }
  });
  $.ajax({
        type: "POST",
        url: ajaxurl,
        data: { action: 'send_bulk_message', 
                security: $('#bulk_action').val(),
                course:$this.attr('data-course'),
                sender: $('#sender').val(),
                members: JSON.stringify(members),
                subject: $('#bulk_subject').val(),
                message: $('#bulk_message').val(),
              },
        cache: false,
        success: function (html) {
            $('#send_course_message').html(html);
            setTimeout(function(){$this.html(defaultxt);}, 5000);
        }
    });    
});

$( 'body' ).delegate( '#add_student_to_course', 'click', function(event){
  event.preventDefault();
  var $this = $(this);
  var defaultxt=$this.html();
  var students = $('#student_usernames').val();

  if(students.length <= 0){ 
    $('#add_student_to_course').html(vibe_course_module_strings.unable_add_students);
    setTimeout(function(){$this.html(defaultxt);}, 2000);
    return;
  }

  $this.html('<i class="icon-sun-stroke animated spin"></i>'+vibe_course_module_strings.adding_students);
  var i=0;
  $.ajax({
        type: "POST",
        url: ajaxurl,
        data: { action: 'add_bulk_students', 
                security: $('#bulk_action').val(),
                course:$this.attr('data-course'),
                members: students,
              },
        cache: false,
        success: function (html) {
          if(html.length && html !== '0'){
            $('#add_student_to_course').html(vibe_course_module_strings.successfuly_added_students);
            $('ul.course_students').append(html);
          }else{
            $('#add_student_to_course').html(vibe_course_module_strings.unable_add_students);
          }
            
            setTimeout(function(){$this.html(defaultxt);}, 3000);
        }
    });    
});

$( 'body' ).delegate( '#download_stats', 'click', function(event){
  event.preventDefault();
  var $this = $(this);
  var defaultxt=$this.html();
  var i=0;
  var fields=[]; 
  $('.field:checked').each(function(){
      fields[i]=$(this).attr('id');//$(this).val();
      i++;
  });
  
  if(i==0){
    $this.html(vibe_course_module_strings.select_fields);
    setTimeout(function(){$this.html(defaultxt);}, 13000);
    return false;
  }else{
    $this.html('<i class="icon-sun-stroke animated spin"></i> '+vibe_course_module_strings.processing);
    $.ajax({
        type: "POST",
        url: ajaxurl,
        data: { action: 'download_stats', 
                security: $('#stats_security').val(),
                course:$this.attr('data-course'),
                fields: JSON.stringify(fields),
                type:$('#stats_students').val()
              },
        cache: false,
        success: function (html) {
            $this.attr('href',html);
            $this.attr('id','download');
            $this.html(vibe_course_module_strings.download)
            //setTimeout(function(){$this.html(defaultxt);}, 5000);
        }
    });  
  }
});

$('body').delegate('#download_mod_stats','click',function(event){
  event.preventDefault();
  var $this = $(this);
  var defaultxt=$this.html();
  var i=0;
  var fields=[]; 
  $('.field:checked').each(function(){
      fields[i]=$(this).attr('id');//$(this).val();
      i++;
  });
  
  if(i==0){
    $this.html(vibe_course_module_strings.select_fields);
    setTimeout(function(){$this.html(defaultxt);}, 13000);
    return false;
  }else{
    $this.html('<i class="icon-sun-stroke animated spin"></i> '+vibe_course_module_strings.processing);
    $.ajax({
        type: "POST",
        url: ajaxurl,
        data: { action: 'download_mod_stats', 
                security: $('#stats_security').val(),
                type:$this.attr('data-type'),
                id:$this.attr('data-id'),
                fields: JSON.stringify(fields),
                select:$('#stats_students').val()
              },
        cache: false,
        success: function (html) {
            $this.attr('href',html);
            $this.attr('id','download');
            $this.html(vibe_course_module_strings.download)
            //setTimeout(function(){$this.html(defaultxt);}, 5000);
        }
    });  
  }
});

$( 'body' ).delegate( '#assign_course_badge_certificate', 'click', function(event){
  event.preventDefault();
  var members=[]; 

  var $this = $(this);
  var defaultxt=$this.html();
  $this.html('<i class="icon-sun-stroke animated spin"></i> '+vibe_course_module_strings.processing);
  var i=0;
  $('.member').each(function(){
    if($(this).is(':checked')){
      members[i]=$(this).val();
      i++;
    }
  });

  $.ajax({
        type: "POST",
        url: ajaxurl,
        data: { action: 'assign_badge_certificates', 
                security: $('#bulk_action').val(),
                course: $this.attr('data-course'),
                members: JSON.stringify(members),
                assign_action: $('#assign_action').val(),
              },
        cache: false,
        success: function (html) {
            $this.html(html);
            setTimeout(function(){$this.html(defaultxt);}, 5000);
        }
    });    
});

$( 'body' ).delegate( '#change_course_status', 'click', function(event){
  event.preventDefault();
  var members=[]; 

  var $this = $(this);
  var defaultxt=$this.html();
  $this.html('<i class="icon-sun-stroke animated spin"></i> '+vibe_course_module_strings.processing);
  var i=0;
  $('.member').each(function(){
    if($(this).is(':checked')){
      members[i]=$(this).val();
      i++;
    }
  });

  $.ajax({
        type: "POST",
        url: ajaxurl,
        data: { action: 'change_course_status', 
                security: $('#bulk_action').val(),
                course: $this.attr('data-course'),
                members: JSON.stringify(members),
                status_action: $('#status_action').val(),
                data: $('#finish_marks').val()
              },
        cache: false,
        success: function (html) {
            $this.html(html);
            setTimeout(function(){$this.html(defaultxt);}, 5000);
        }
    });    
});


$( 'body' ).delegate( '#extend_course_subscription', 'click', function(event){
  event.preventDefault();
  var members=[];

  var $this = $(this);
  var defaultxt=$this.html();
  $this.html('<i class="icon-sun-stroke animated spin"></i> '+vibe_course_module_strings.processing);
  var i=0;
  $('.member').each(function(){
    if($(this).is(':checked')){
      members[i]=$(this).val();
      i++;
    }
  });

  $.ajax({
        type: "POST",
        url: ajaxurl,
        data: { action: 'extend_course_subscription', 
                security: $('#bulk_action').val(),
                course: $this.attr('data-course'),
                members: JSON.stringify(members),
                extend_amount: $('#extend_amount').val(),
              },
        cache: false,
        success: function (html) {
            $this.html(html);
            setTimeout(function(){$this.html(defaultxt);}, 5000);
        }
    });    
});



$( 'body' ).delegate( '#mark-complete', 'media_loaded', function(event){
  event.preventDefault(); 
  if($(this).hasClass('tip')){
      $(this).addClass('disabled');
  }
});

$( 'body' ).delegate( '#mark-complete', 'media_complete', function(event){ 
  event.preventDefault();
  if($(this).hasClass('tip')){
    $(this).removeClass('disabled');
    $(this).removeClass('tip');
    $(this).tooltip('destroy');
    jQuery('.tip').tooltip();
  }  
});


$( 'body' ).delegate( '#mark-complete', 'click', function(event){
    event.preventDefault();
    if($(this).hasClass('disabled')){
      return false;
    }

    var $this = $(this);
    var unit_id=$(this).attr('data-unit');
    $this.prepend('<i class="icon-sun-stroke animated spin"></i>');
    $('body').find('.course_progressbar').removeClass('increment_complete');
    $.ajax({
            type: "POST",
            url: ajaxurl,
            data: { action: 'complete_unit', 
                    security: $('#hash').val(),
                    course_id: $('#course_id').val(),
                    id: unit_id
                  },
            cache: false,
            success: function (html) {
                $this.find('i').remove();
                $this.html('<i class="icon-check"></i>');
                $('.course_timeline').find('.active').addClass('done');
                $('body').find('.course_progressbar').trigger('increment');
                $('#mark-complete').addClass('disabled');
                if(html.length > 0 && jQuery.isNumeric(html)){
                    $('#next_unit').removeClass('hide');
                    $('#next_unit').attr('data-unit',html);  
                    $('#next_quiz').removeClass('hide');
                    $('#next_quiz').attr('data-unit',html); 
                    $('#unit'+html).find('a').addClass('unit');
                    $('#unit'+html).find('a').attr('data-unit',html);
                }
                if(typeof unit != 'undefined')
                  $('.unit_timer').trigger('finish');
            }
    });
});


$('.course_progressbar').on('increment',function(event){

  if($(this).hasClass('increment_complete')){
    event.stopPropagation();
    return false;
  }else{
    var iunit = parseFloat($(this).attr('data-increase-unit'));
    var per = parseFloat($(this).attr('data-value'));
    newper = iunit + per;
    newper = newper.toFixed(2);
    var amountunits = 100/iunit;
    amountunits = amountunits.toFixed(2);
    var maxper = iunit*amountunits;
    maxper = maxper.toFixed(2);
    if (newper == maxper) {
     newper = 100;
    }

    //Boundary Conditions
    if(newper>100)
      newper=100;

    if(newper<0)
      newper=0;
    
    $('.course_timeline').each(function(){
        if($(this).find('.unit_line').length == $(this).find('.done').length){
           newper = 100;
        }
        if($(this).find('.done').length == 0){
          newper = 0;
        }
    });
    /*== Boundary Conditions check ==*/

    $(this).find('.bar').css('width',newper+'%');
    $(this).find('.bar span').html(newper + '%');
    $(this).addClass('increment_complete');
    $(this).attr('data-value',newper);
    $.ajax({
            type: "POST",
            url: ajaxurl,
            async:true,
            data: { action: 'record_course_progress', 
                    security: $('#hash').val(),
                    course_id: $('#course_id').val(),
                    progress: newper
                  },
            cache: false,
            cache: false,
            success: function (html) {
              /* var cookie_id ='course_progress'+$('#course_id').val();
              $.cookie(cookie_id,newper, { path: '/' }); */
            }
          });
  }
  event.stopPropagation();
  return false;
  
});

$('.course_progressbar').on('decrement',function(event){

  if($(this).hasClass('increment_complete')){
    event.stopPropagation();
    return false;
  }else{
    var iunit = parseFloat($(this).attr('data-increase-unit'));
    var per = parseFloat($(this).attr('data-value'));
    newper =  per-iunit;
    newper = newper.toFixed(2);
    if(newper<0)
      newper=0;
    $(this).find('.bar').css('width',newper+'%');
    $(this).find('.bar span').html(newper + '%');
    $(this).addClass('increment_complete');
    $(this).attr('data-value',newper);
    $.ajax({
            type: "POST",
            url: ajaxurl,
            async:true,
            data: { action: 'record_course_progress', 
                    security: $('#hash').val(),
                    course_id: $('#course_id').val(),
                    progress: newper
                  },
            cache: false,
            success: function (html) {
              /*var cookie_id ='course_progress'+$('#course_id').val();
              $.cookie(cookie_id,newper, { path: '/' }); */
            }
          });
  }
  event.stopPropagation();
  return false;
  
});



jQuery(document).ready(function($){
  $('.showhide_indetails').click(function(event){
    event.preventDefault();
    $(this).find('i').toggleClass('icon-minus');
    $(this).parent().find('.in_details').toggle();
  });


$('.ajax-certificate').each(function(){
    $(this).magnificPopup({
          type: 'ajax',
          fixedContentPos: true,
          alignTop:true,
          preloader: false,
          midClick: true,
          removalDelay: 300,
          showCloseBtn:false,
          mainClass: 'mfp-with-zoom',
          callbacks: {
             parseAjax: function( mfpResponse ) {
              mfpResponse.data = $(mfpResponse.data).find('#certificate');
            },
            ajaxContentAdded: function() {
              html2canvas($('#certificate'), {
                  backgrounnd:'#ffffff',
                  onrendered: function(canvas) {
                      var data = canvas.toDataURL("image/jpeg");
                      $('#certificate .certificate_content').html('<img src="'+data+'" width="'+$('#certificate .certificate_content').attr('data-height')+'" height="'+$('#certificate .certificate_content').attr('data-width')+'" />');
                      $('#certificate').trigger('generate_certificate');
                      var doc = new jsPDF();
                      var width = 210;
                      var height = 80;
                      if($('#certificate .certificate_content').attr('data-width').length){
                        height = Math.round(210*parseInt($('#certificate .certificate_content').attr('data-height'))/parseInt($('#certificate .certificate_content').attr('data-width')));
                      }
                      doc.addImage(data, 'JPEG',0,0, 210,height);
                      $('.certificate_pdf').click(function(){
                        doc.output('dataurlnewwindow');
                      });
                  }
              });
            },
          }
      });
});

$('.ajax-badge').each(function(){
  var $this=$(this);
  var img=$this.find('img');
  $(this).magnificPopup({
        items: {
            src: '<div class="badge-popup"><img src="'+img.attr('src')+'" /><h3>'+$this.attr('title')+'</h3><strong>'+vibe_course_module_strings.for_course+' '+$this.attr('data-course')+'</strong></div>',
            type: 'inline'
        },
        fixedContentPos: false,
        alignTop:false,
        preloader: false,
        midClick: true,
        removalDelay: 300,
        showCloseBtn:false,
        mainClass: 'mfp-with-zoom center-aligned'
    });
});

$( 'body' ).delegate( '.print_unit', 'click', function(event){
    $('.unit_content').print();
});

$( 'body' ).delegate( '.printthis', 'click', function(event){
    $(this).parent().print();
});

$( 'body' ).delegate( '#certificate', 'generate_certificate', function(event){
    $(this).addClass('certificate_generated');
});

$( 'body' ).delegate( '.certificate_print', 'click', function(event){
    event.preventDefault();
    $(this).parent().parent().print();
});

$('.widget_carousel').each(function(){
    var $this = $(this);
    var auto = false;
    if($this.hasClass('auto')){
      auto = true;
    }
    $this.flexslider({
      animation: "slide",
      controlNav: false,
      directionNav: true,
      animationLoop: true,
      slideshow: auto,
      prevText: "<i class='icon-arrow-1-left'></i>",
      nextText: "<i class='icon-arrow-1-right'></i>",
    });
});

  /*=== Quick tags ===*/
  $( 'body' ).delegate( '.unit-page-links a', 'click', function(event){
        if($('body').hasClass('single-unit'))
          return;

        event.preventDefault();
        
        var $this=$(this);
        $this.prepend('<i class="icon-sun-stroke animated spin"></i>');
        $( ".main_unit_content" ).load( $this.attr('href') +" .single_unit_content" ,function(){
          $('.unit_content').trigger('unit_traverse');
          $('body').trigger('unit_loaded');
        });
        $this.find('i').remove();
        $( ".main_unit_content" ).trigger('unit_reload');
  });
  

 

   $('body').delegate('.pricing_course .drop label','click',function(){
        var labelText = $(this).find('.font-text').html();
         var value = $(this).attr('data-value');
         var parent = $(this).parent().parent();
         $(parent).find('.result').html(labelText);
        if($('.course_button').length){
          $('.course_button').attr('href',value);
        }
    });

    $('body').delegate('.pricing_course .result','click',function() {
      var parent = $(this).parent();
        $(parent).find('.drop').slideToggle('fast');
    });

    $('body').delegate('.pricing_course .drop','click',function() {
        var parent = $(this).parent();
        $(parent).find('.drop').slideUp('fast');
    });
}); 



$('.unit_content').on('unit_traverse',function(){
  runnecessaryfunctions();
  $('body,html').animate({
    scrollTop: 0
  }, 1200);
  
    
});

// Course Unit Traverse

$( 'body' ).delegate( '.unit', 'click', function(event){
    event.preventDefault();
    if($(this).hasClass('disabled')){
      return false;
    }
    
    var $this = $(this);
    var unit_id=$(this).attr('data-unit');
    if($this.prev().is('span')){
      $this.prev().addClass('loading');
    }else{
      $this.prepend('<i class="icon-sun-stroke animated spin"></i>');  
    }
    
    $('#ajaxloader').removeClass('disabled');
    $('.unit_content').addClass("loading");


    $.ajax({
            type: "POST",
            url: ajaxurl,
            data: { action: 'unit_traverse', 
                    security: $('#hash').val(),
                    course_id: $('#course_id').val(),
                    id: unit_id
                  },
            cache: false,
            success: function (html) {

                if($this.prev().is('span')){
                  $this.prev().removeClass('loading');
                }else{
                  $this.find('i').remove();
                } 
                $('#ajaxloader').addClass('disabled');
                $('.unit_content').removeClass("loading");
                $('.unit_content').html(html);

                var unit=$($.parseHTML(html)).filter("#unit");
                var u='#unit'+unit.attr('data-unit');
                $('.course_timeline').find('.active').removeClass('active');
                $(u).addClass('active');

                $('.unit_content').trigger('unit_traverse');
                runnecessaryfunctions();
                //=== UNIT COMMENTS ======
                if($('.unit_wrap').hasClass('enable_comments')){ 
                  $('.unit_content').trigger('load_comments');
                }

                if(typeof unit != 'undefined')
                  $('.unit_timer').trigger('activate');
            }
    });
});
 
/*==============================================================*/
/*======================= UNIT COMMENTS ========================*/
/*==============================================================*/

$( 'body' ).delegate( '.unit_content', 'load_comments', function(event){ 
        if($(this).find('.main_unit_content').hasClass('stop_notes') || $('body').hasClass('wp-fee-body'))
          return;

       var unit_id=$('#unit').attr('data-unit');
      $('.unit_content p').each(function(index){
            if (!$(this).parents('.question').length){
              $(this).attr('data-section-id',index);
              $(this).append('<span id="c'+index+'" class="side_comment">+</span>');
            }
        });
        unitComments();
         $.ajax({
            type: "POST",
            dataType: "json",
            url: ajaxurl,
            data: { action: 'get_unit_comment_count', 
                    security: $('#hash').val(),
                    unit_id: unit_id,
                  },
            cache: false,
            success: function (response) {
              $.each(response, function(idx, obj) {
                $('#'+obj.id).text(obj.count);
              });
              }
            });
        $('.side_comment').on('click',function(){ 
          if($(this).hasClass('active'))
            return false;
          var $this = $(this);
          var section = $('.side_comment.active').attr('id');
          $('.side_comment').removeClass('active');
          $('.side_comments .main_comments>li:not(".hide")').remove();
          $(this).addClass('active');
          var id = $(this).attr('id');
          $('.add-comment').fadeIn();
          $('.add-comment').next().fadeOut();
          var check = $(this).text();
          var href='#';
          $('.side_comments .main_comments').find('.loaded').remove();
          if( jQuery.isNumeric(check)){
            var comment_html ='';
            var cookie_id='unit_comments'+unit_id;
            //var unit_comments = $.cookie(cookie_id);
            var unit_comments = sessionStorage.getItem(cookie_id);
            //CHeck cookie
            if (unit_comments !== null){ 
               unit_comments = JSON.parse(unit_comments);
               $.each(unit_comments, function(idxx, objStr) { 
               $.each(objStr, function(idx, obj){ 
                  if(id == idx){ 
                    comment_html += '<li class="loaded"><div class="'+obj.type+' user'+obj.author.user_id+'" data-id="'+obj.ID+'"><img src="'+obj.author.img+'"><a href="'+obj.author.link+'" class="unit_comment_author">'+obj.author.name+'</a><div class="unit_comment_content">'+obj.content+'</div><ul class="actions" data-pid="'+$this.attr('id')+'">';
                    
                      jQuery.each(obj.controls, function(i,o) { 
                        if(o>1){
                         jQuery('.side_comments li.hide').find('.'+i).addClass('meta_info').attr('data-meta',o);
                        }
                        var control = jQuery('.side_comments li.hide').find('.'+i).parent()[0].outerHTML;
                        if(o>1){
                          jQuery('.side_comments li.hide').find('.'+i).removeClass('meta_info').removeAttr('data-meta');
                        }
                        comment_html +=control;
                      });
                    
                    comment_html +='</ul></div></li>';
                    href=$(comment_html).find('.popup_unit_comment').removeClass('meta_info').attr('data-href');
                    href +='?unit_id='+unit_id+'&section='+idx;
                  } 
                });
               });
               $('.side_comments .main_comments').append(comment_html);
               $('.side_comments .main_comments .popup_unit_comment').attr('href',href);
               jQuery('.tip').tooltip();
               $('.popup_unit_comment').magnificPopup({
                    type: 'ajax',
                    alignTop: true,
                    fixedContentPos: true,
                    fixedBgPos: true,
                    overflowY: 'auto',
                    closeBtnInside: true,
                    preloader: false,
                    midClick: true,
                    removalDelay: 300,
                    mainClass: 'my-mfp-zoom-in',
                    callbacks: {
                             parseAjax: function( mfpResponse ) {
                              mfpResponse.data = $(mfpResponse.data).find('.content');
                            }
                          }
                });
            }else{ //ajax request and grab the json from ajax
                section =$('.side_comment.active').attr('id');
                
                $.ajax({
                  type: "POST",
                  dataType: "json",
                  url: ajaxurl,
                  data: { action: 'unit_section_comments', 
                          security: $('#hash').val(),
                          unit_id: unit_id,
                          section: section,
                          num:$('.side_comment').length
                        },
                  cache: false,
                  success: function (jsonStr){
                     var cookie_value =JSON.stringify(jsonStr);
                     sessionStorage.setItem(cookie_id,cookie_value);
                      $.each(jsonStr, function(idxx, objStr){ 
                         $.each(objStr, function(idx, obj){ 
                          if(id == idx){
                            comment_html += '<li class="loaded"><div class="'+obj.type+' user'+obj.author.user_id+'" data-id="'+obj.ID+'"><img src="'+obj.author.img+'"><a href="'+obj.author.link+'" class="unit_comment_author">'+obj.author.name+'</a><div class="unit_comment_content">'+obj.content+'</div><ul class="actions" data-pid="'+$this.attr('id')+'">';

                              jQuery.each(obj.controls, function(i,o) { 
                                if(o>1){
                                 jQuery('.side_comments li.hide').find('.'+i).addClass('meta_info').attr('data-meta',o);
                                }
                                var control = jQuery('.side_comments li.hide').find('.'+i).parent()[0].outerHTML;
                                if(o>1){
                                  jQuery('.side_comments li.hide').find('.'+i).removeClass('meta_info').removeAttr('data-meta');
                                }
                                comment_html +=control;
                              });

                            comment_html +='</ul></div></li>';
                            var href=$(comment_html).find('.popup_unit_comment').attr('href');
                            href +='?unit_id='+unit_id+'&section='+idx;
                            $(comment_html).find('.popup_unit_comment').attr('href',href);
                          }
                        });
                      });   
                      $('.side_comments .main_comments').append(comment_html);
                      jQuery('.tip').tooltip();
                      $('.popup_unit_comment').magnificPopup({
                          type: 'ajax',
                          alignTop: true,
                          fixedContentPos: true,
                          fixedBgPos: true,
                          overflowY: 'auto',
                          closeBtnInside: true,
                          preloader: false,
                          midClick: true,
                          removalDelay: 300,
                          mainClass: 'my-mfp-zoom-in',
                          callbacks: {
                                   parseAjax: function( mfpResponse ) { 
                                    mfpResponse.data = $(mfpResponse.data).find('.content');
                                  }
                                }
                      });
                  }
              });
            } //end else 
          } // end if numeric check
          var all_href=$('#all_comments_link').attr('data-href');
          all_href +='?unit_id='+unit_id+'&section='+$('.side_comment.active').attr('id');
          $('#all_comments_link').attr('href',all_href);

          var top = $(this).offset().top; 
          var content_top=$('#unit_content').offset().top; 
          var height = $('.side_comments').height();
          var limit = $('.unit_prevnext').offset().top;
          if((top+height) > limit){
            top = limit - content_top - height;
          }else{
            top = top - content_top;
          }
          if(top >0){
            $('.side_comments').css('top',top+'px');
            $('.side_comments').removeClass('scroll');
          }else{
            $('.side_comments').addClass('scroll');
            var h=$('.main_unit_content').height();
            $('.side_comments').css('height',h+'px');
          }
        }); 
      /*=== END UNIT COMMENTS ======*/
  });
/* ===== UNIT COMMENTS =====*/
jQuery(document).ready(function($){

  

  $('.add-comment').on('click',function(){
      $(this).fadeOut(0);
      $(this).next('.comment-form').fadeIn(100);
  });

  $('.new_side_comment').on('click',function(){ 
    if(!$(this).hasClass('cleared')){
      $(this).html('');$(this).addClass('cleared');
      $(this).parent().parent().addClass('active');
      $(this).parent().parent().parent().find('.add-comment').addClass('deactive');
    }
  });

  $('.remove_side_comment').on('click',function(){
    $(this).closest('.side_comments').find('.add-comment').fadeIn(100);
    $(this).closest('.comment-form').fadeOut();
    $('.new_side_comment').removeClass('cleared');
    $('.new_side_comment').text(vibe_course_module_strings.add_comment);
  });
});

$( 'body' ).delegate( '.public_unit_comment', 'click', function(event){
    event.preventDefault();
    var $this = $(this);
    var id =$this.closest('li.loaded>div').attr('data-id');
    $.ajax({
            type: "POST",
            url: ajaxurl,
            data: { action: 'public_user_comment', 
                    security: $('#hash').val(),
                    id: id
                  },
            cache: false,
            success: function (html) {
                $this.removeClass('public_unit_comment');
                $this.addClass('private_unit_comment');
                $this.find('i').removeClass().addClass('icon-fontawesome-webfont-4');
                $this.attr('data-original-title',vibe_course_module_strings.private_comment);
                var unit_id = $('#unit').attr('data-unit');
                var cookie_id='unit_comments'+unit_id;
                sessionStorage.removeItem(cookie_id);
            }
    });
});
$( 'body' ).delegate( '.private_unit_comment', 'click', function(event){
    event.preventDefault();
    var $this = $(this);
    var id =$this.closest('li.loaded>div').attr('data-id');
    $.ajax({
            type: "POST",
            url: ajaxurl,
            data: { action: 'private_user_comment', 
                    security: $('#hash').val(),
                    id: id
                  },
            cache: false,
            success: function (html) {
                $this.removeClass('private_unit_comment');
                $this.addClass('public_unit_comment');
                $this.find('i').removeClass().addClass('icon-fontawesome-webfont-3');
                $this.attr('data-original-title',vibe_course_module_strings.public_comment);
                var unit_id = $('#unit').attr('data-unit');
                var cookie_id='unit_comments'+unit_id;
                sessionStorage.removeItem(cookie_id);
            }
    });
});
$( 'body' ).delegate( '.edit_unit_comment', 'click', function(event){
    event.preventDefault();
    var $this = $(this);
    var content = $this.parent().parent().parent();
    var form = $('.comment-form').first().clone();
    var img = content.find('img').clone();
    var unit_comment_author = content.find('.unit_comment_author').clone();
    var id = content.attr('data-id');
    form.find('img').replaceWith(function(){return img;});
    form.find('span').replaceWith(function(){return unit_comment_author;});
    var new_content = content.find('.unit_comment_content');
    form.find('.new_side_comment').html(new_content.html());
    //console.log(id+'#');
    form.find('.post_unit_comment').removeClass().addClass('edit_form_unit_comment').attr('data-id',id);
    form.find('.remove_side_comment').removeClass().addClass('remove_form_edit_unit_comment');
    content.parent().append(form);    
    content.hide();
    content.parent().find('.comment-form').show();
});

$( 'body' ).delegate( '.remove_form_edit_unit_comment', 'click', function(event){
   $(this).parent().parent().parent().parent().find('.note,.public').show();
   $(this).closest('.comment-form').remove();
   $('.new_side_comment').removeClass('cleared');
});
$( 'body' ).delegate( '.reply_unit_comment', 'click', function(event){
    event.preventDefault();
    var parent_li = $(this).parent().parent().parent().parent();
    var $this = $(this);
    if($(this).hasClass('meta_info')){  
      var id =$(this).attr('data-meta');
      $.ajax({
              type: "POST",
              url: ajaxurl,
              data: { action: 'get_user_reply', 
                      security: $('#hash').val(),
                      id: id
                    },
              cache: false,
              success: function (html) {
                if(!jQuery.isNumeric(html)){
                  parent_li.after(html);
                  $this.removeClass('reply_unit_comment');
                }
              }
      });
    }else{
      $('.add-comment').trigger('click');
      $('.comment-form').addClass('creply');
      $('.comment-form').attr('data-cid',$(this).closest('.actions').parent().attr('data-id'));
    }
});

$( 'body' ).delegate( '.instructor_reply_unit_comment', 'click', function(event){
    event.preventDefault();
    if($(this).hasClass('call'))
      return false;

    var $ithis=$(this);
    var message = $ithis.parent().parent().parent().find('.unit_comment_content').html();
    var unit_id =$('#unit').attr('data-unit');
    //console.log(unit_id);
    $.ajax({
            type: "POST",
            url: ajaxurl,
            data: { action: 'instructor_reply_user_comment', 
                    security: $('#hash').val(),
                    message:message,
                    id: unit_id,
                    section:$('.side_comment.active').attr('id')
                  },
            cache: false,
            success: function (html) {
              $ithis.addClass('call');
            }
    });
});
$( 'body' ).delegate( '.edit_form_unit_comment', 'click', function(event){
    event.preventDefault();
    var $new_this = $(this);
    var id =$new_this.attr('data-id');
    var new_content = $('.new_side_comment').html();
    $.ajax({
            type: "POST",
            url: ajaxurl,
            data: { action: 'edit_user_comment', 
                    security: $('#hash').val(),
                    content:new_content,
                    id: id
                  },
            cache: false,
            success: function (html) {
              var unit_id = $('#unit').attr('data-unit');
              var cookie_id='unit_comments'+unit_id;
              sessionStorage.removeItem(cookie_id);
              var new_parent =$new_this.closest('.comment-form').prev().parent();
              new_parent.find('.unit_comment_content').html(new_content);
              $new_this.closest('.comment-form').remove();
              new_parent.find('.note,.public').show();
            }
    });
});

$( 'body' ).delegate( '.remove_unit_comment', 'click', function(event){
    event.preventDefault();
    var $this = $(this);
    var id =$this.parent().parent().closest('li>div').attr('data-id');
    var note = $this.parent().parent().closest('li>div').parent();
    $this.addClass('animated spin');
    $.confirm({
        text: vibe_course_module_strings.remove_comment,
        confirm: function() {
           $.ajax({
                  type: "POST",
                  url: ajaxurl,
                  data: { action: 'remove_user_comment', 
                          security: $('#hash').val(),
                          id: id
                        },
                  cache: false,
                  success: function (html) {
                      $this.removeClass('animated');
                      $this.removeClass('spin');
                      note.remove();
                      var cid = $this.closest('.actions').attr('data-pid');
                      var count=parseInt($('#'+cid).text());
                      count--;
                      $('#'+cid).text(count);
                      $this.closest('li.loaded').fadeOut(200,function(){$(this).remove();});
                      var unit_id = $('#unit').attr('data-unit');
                      var cookie_id='unit_comments'+unit_id;
                      $('.new_side_comment').removeClass('cleared');
                      sessionStorage.removeItem(cookie_id);
                  }
          });
        },
        cancel: function() {
            $this.removeClass('animated');
            $this.removeClass('spin');
        },
        confirmButton: vibe_course_module_strings.remove_comment_button,
        cancelButton: vibe_course_module_strings.cancel
    });
});

$( 'body' ).delegate( '.post_unit_comment', 'click', function(event){
    event.preventDefault();
    if($(this).hasClass('disabled')){
      return false;
    }
    var reply =0;
    if($(this).closest('.comment-form').hasClass('creply')){
      reply = $(this).closest('.comment-form').attr('data-cid');
    }

    var $this = $(this);
    var section = $('.side_comment.active').attr('id');
    var unit_id = $('#unit').attr('data-unit');
    var list =$this.closest('.side_comments').find('ul.main_comments');
    var list_html = list.find('li.hide').clone();
    var content = $(this).closest('.comment-form').find('.new_side_comment').html();
    var cookie_id='unit_comments'+unit_id;

    $this.addClass('disabled');
    $.ajax({
            type: "POST",
            url: ajaxurl,
            data: { action: 'post_unit_comment', 
                    security: $('#hash').val(),
                    course_id: $('#course_id').val(),
                    unit_id: unit_id,
                    content:content,
                    section:section,
                    reply:reply
                  },
            cache: false,
            success: function (id) {
              $this.removeClass('disabled');
               if( jQuery.isNumeric(id)){
                 var cookie_id='unit_comments'+unit_id;
                 var unit_comments = $.cookie(cookie_id);
                 var comment={
                  section:{
                    'content':content,
                    'type':'note',
                    'author':{
                      'img':list_html.find('img').attr('src'),
                      'name':list_html.find('.unit_comment_author').text(),                    
                      'link':list_html.find('.unit_comment_author').attr('href'),
                    },
                    'controls':{
                      'edit_unit_comment':1,
                      'public_unit_comment':1,
                      'instructor_reply_unit_comment':1,
                      'popup_unit_comment':1,
                      'remove_unit_comment':1
                    }
                  }
                };
                 sessionStorage.removeItem(cookie_id);
                 list_html.find('.unit_comment_content').html(content);
                 list_html.find('.note').attr('data-id',id);
                 list_html.removeClass();
                 list_html.find('.actions .private_unit_comment').parent().remove();
                 list.append(list_html);
                 var href=$(list_html).find('.popup_unit_comment').attr('data-href');
                 href +='?unit_id='+unit_id+'&section='+$('.side_comment.active').attr('id');
                 $(list_html).find('.popup_unit_comment').attr('href',href);
                 jQuery('.tip').tooltip();
                 var count=$('#'+section).text();
                 if( jQuery.isNumeric(count)){
                    count=parseInt(count)+1;
                 }else{
                   count=1;
                 }
                 $('.new_side_comment').removeClass('cleared');
                 $('#'+section).text(count);
                 $('.add-comment').fadeIn();
                 $('.comment-form').removeClass('active').fadeOut();
                 $('.new_side_comment').text(vibe_course_module_strings.add_comment);
                 $('.popup_unit_comment').magnificPopup({
                    type: 'ajax',
                    alignTop: true,
                    fixedContentPos: true,
                    fixedBgPos: true,
                    overflowY: 'auto',
                    closeBtnInside: true,
                    preloader: false,
                    midClick: true,
                    removalDelay: 300,
                    mainClass: 'my-mfp-zoom-in',
                    callbacks: {
                             parseAjax: function( mfpResponse ) {
                              mfpResponse.data = $(mfpResponse.data).find('.content');
                            }
                          }
                });
               }else{
                $this.closest('.comment-form').append('<div class="error">'+id+'</div>');
               }
            }
    });
});

$( 'body' ).delegate( '.note-tabs li', 'click', function(event){
  event.preventDefault();
  $(this).parent().find('.selected').removeClass('selected');
  $(this).addClass('selected');
   var action = $(this).attr('id');
   $.ajax({
            type: "POST",
            url: ajaxurl,
            data: { action: action, 
                    security: $('#hash').val(),
                    unit_id:$.urlParam('unit_id'),
                    section:$.urlParam('section')
                  },
            cache: false,
            success: function (html) {
              $('.content').html(html);
              $(".live-edit").liveEdit({
                  afterSaveAll: function(params) {
                    return false;
                  }
              });
            }
          });
});
$( 'body' ).delegate( '#load_more_notes', 'click', function(event){
   var json = $('#notes_query').html();
   $.ajax({
            type: "POST",
            url: ajaxurl,
            data: { action: 'load_more_notes', 
                    security: $('#hash').val(),
                    json:json
                  },
            cache: false,
            success: function (html) {
              if( jQuery.isNumeric(html)){
                $('#load_more_notes').hide();
              }else{
                var newjson = $(html).filter('#new_notes_query').html();
                $('#notes_query').html(newjson);
                $('#notes_discussions .notes_list').append(html);
                $('#new_notes_query').remove();
              }
              $(".live-edit").liveEdit({
                  afterSaveAll: function(params) {
                    return false;
                  }
              });
            }
          });
});
jQuery(document).ready(function(){
   jQuery('.course_curriculum.accordion .course_section').click(function(event){
           jQuery(this).toggleClass('show');
           jQuery(this).nextUntil('.course_section').toggleClass('show');
   });
   jQuery('.course_timeline.accordion .section').on('click',function(event){
           jQuery(this).toggleClass('show');
           jQuery(this).nextUntil('.section').toggleClass('show');
   });
   jQuery('.course_timeline.accordion').each(function(){ 
      var $this = $(this);
      $this.find('.unit_line.active').prevAll('.section').trigger('click');
   });
   $('.unit_content').on('unit_traverse',function(){ 
      var section =$('.course_timeline.accordion').find('.unit_line.active').prev('.section');
      if($('.course_timeline.accordion').find('.unit_line.active').prev().hasClass('section')){
        jQuery('.course_timeline.accordion .section.show').trigger('click'); // Close the open one
      }
      $('.unit_content').find('audio,video').each(function(){ 
        
        if(typeof this.player !== "undefined"){
          $('#mark-complete').trigger('media_loaded');
        }
        this.addEventListener('ended', function (e) { 
          $('#mark-complete').trigger('media_complete');
        });
      });

      if(!section.hasClass('show'))
        section.trigger('click');
    });
   $('.retake_submit').click(function(){
      $(this).parent().submit();
   });
});


/*==============================================================*/
/*======================= In Course Quiz  ========================*/
/*==============================================================*/

// IN QUIZ TIMER
$('.unit_content').on('unit_traverse',function(){
  $('.inquiz_timer').each(function(){

    $('.inquiz_timer').one('activate',function(){
        var qtime = parseInt($(this).attr('data-time'));

        var $timer =$(this).find('.timer');
        var $this=$(this);

        $timer.timer({
          'timer': qtime,
          'width' : 72 ,
          'height' : 72 ,
          'fgColor' : vibe_course_module_strings.theme_color 
        });

        $timer.on('change',function(){ 
            var countdown= $this.find('.countdown');
            var val = parseInt($timer.attr('data-timer'));
            if(val > 0){
              val--;
              $timer.attr('data-timer',val);
              var $text='';
              if(val > 60){
                $text = Math.floor(val/60) + ':' + ((parseInt(val%60) < 10)?'0'+parseInt(val%60):parseInt(val%60)) + '';
              }else{
                $text = '00:'+ ((val < 10)?'0'+val:val);
              }
              countdown.html($text);
            }else{
                countdown.html(vibe_course_module_strings.timeout);
                if(!$('.submit_inquiz').hasClass('triggerred')){
                    $('.submit_inquiz').addClass('triggerred');
                    $('.submit_inquiz').trigger('click');
                } 
                $('.inquiz_timer').trigger('deactivate');
            }  
        });
        
    });

    $('.inquiz_timer').one('deactivate',function(event){
      var qtime = 0;
      var $timer =$(this).find('.timer');
      var $this=$(this);

      $timer.knob({
        'readonly':true,
        'max': qtime,
        'width' : 72 ,
        'height' : 72 ,
        'fgColor' : vibe_course_module_strings.theme_color ,
        'readonly':true 
      });
      event.stopPropagation();
    });
    // END IN QUIZ TIMER

      var qtime = parseInt($(this).attr('data-time'));
      var $timer =$(this).find('.timer');
      $timer.knob({
        'readonly':true,
        'max': qtime,
        'width' : 72 ,
        'height' : 72 ,
        'fgColor' : vibe_course_module_strings.theme_color ,
        'thickness': 0.1 ,
        'readonly':true 
      });
      if($(this).hasClass('start')){
        $('.inquiz_timer').trigger('activate');
      }
  });  
});

$( 'body' ).delegate( '.unit_button.start_quiz', 'click', function(event){
  event.preventDefault();
   var $this=$(this);
   $('#ajaxloader').removeClass('disabled');
   $('#unit_content').addClass('loading');
   if($this.hasClass('continue')){
      $.ajax({
              type: "POST",
              url: ajaxurl,
              data: { action: 'in_start_quiz', 
                      security: $('#hash').val(),
                      quiz_id:$('#unit.quiz_title').attr('data-unit'),
                    },
              cache: false,
              success: function (html) {
                $('.main_unit_content').html(html);
                $('.in_quiz').trigger('question_loaded');
                $this.removeClass('start_quiz continue');
                $this.attr('href','#');
                $this.addClass('submit_inquiz');
                runnecessaryfunctions();
                $this.text(vibe_course_module_strings.submit_quiz);
                $('.quiz_title .inquiz_timer').trigger('activate');
                $('#ajaxloader').addClass('disabled');
                $('#unit_content').removeClass('loading');
              }
            });
   }else{
      $.confirm({
        text: vibe_course_module_strings.start_quiz_notification,
        confirm: function() {
           $.ajax({
              type: "POST",
              url: ajaxurl,
              data: { action: 'in_start_quiz', 
                      security: $('#hash').val(),
                      quiz_id:$('#unit.quiz_title').attr('data-unit'),
                    },
              cache: false,
              success: function (html) {
                $('#ajaxloader').addClass('disabled');
                $('#unit_content').removeClass('loading');
                $('.main_unit_content').html(html);
                $('.in_quiz').trigger('question_loaded');
                $this.removeClass('start_quiz');
                $this.attr('href','#');
                $this.addClass('submit_inquiz');
                runnecessaryfunctions();
                $this.text(vibe_course_module_strings.submit_quiz);
                $('.quiz_title .inquiz_timer').trigger('activate');
              }
            });
        },
        cancel: function() {
          $('#ajaxloader').addClass('disabled');
          $('#unit_content').removeClass('loading');
        },
        confirmButton: vibe_course_module_strings.confirm,
        cancelButton: vibe_course_module_strings.cancel
    });
   }
});


$( 'body' ).delegate( '.unit_button.submit_inquiz', 'click', function(event){
   event.preventDefault();
   var $this=$(this);
   var answers=[];
   $('#ajaxloader').removeClass('disabled');
   $('#unit_content').addClass('loading');
   if(typeof all_questions_json !== 'undefined') {

        var unanswered_flag=0;
        $.each(all_questions_json, function(key, value) {
            var ans = localStorage.getItem(value);
            if(ans){
              var answer={'id':value,'value':ans};
              answers.push(answer);
            }else{
              unanswered_flag++;
            }
        });

      if($this.hasClass('triggerred')){  // Auto Submit
          $.ajax({
                type: "POST",
                url: ajaxurl,
                data: { action: 'in_submit_quiz', 
                        security: $('#hash').val(),
                        quiz_id:$('#unit.quiz_title').attr('data-unit'),
                        answers:JSON.stringify(answers)
                      },
                cache: false,
                success: function (html) {
                  $('#ajaxloader').addClass('disabled');
                  $('#unit_content').removeClass('loading');
                  html = $.trim(html);
                  if(html.indexOf('##') > 0){
                    var nextunit = html.substr(0, html.indexOf('##')); 
                    html = html.substr((html.indexOf('##')+2));
                    if(nextunit.length>0){
                      $('#next_unit').removeClass('hide');
                      $('#next_unit').attr('data-unit',nextunit);  
                      $('#next_quiz').removeClass('hide');
                      $('#next_quiz').attr('data-unit',nextunit); 
                      $('#unit'+nextunit).find('a').addClass('unit');
                      $('#unit'+nextunit).find('a').attr('data-unit',nextunit);
                    }
                  }else{ 
                     if(html.indexOf('##') == 0){ 
                        html = html.substr(2);
                        console.log(html);
                     }else{
                       $('#next_unit').removeClass('hide');
                     }
                  }
                  $('.main_unit_content').html(html);
                  $('.quiz_title .inquiz_timer').trigger('deactivate');
                  $('.in_quiz').trigger('question_loaded');
                  $this.removeClass('submit_inquiz');
                  $('.quiz_title .quiz_meta').addClass('hide');
                  $this.addClass('quiz_results_popup');
                  $this.attr('href',$('#results_link').val());
                  runnecessaryfunctions();
                  
                  $this.text(vibe_course_module_strings.check_results);
                  $('#unit'+$('#unit.quiz_title').attr('data-unit')).addClass('done');
                  $('body').find('.course_progressbar').removeClass('increment_complete');
                  $('body').find('.course_progressbar').trigger('increment');
                }
              });
      }else{
        if(unanswered_flag){
          $.confirm({
            text: vibe_course_module_strings.unanswered_questions,
            confirm: function() {
              $.confirm({
                text: vibe_course_module_strings.submit_quiz_notification,
                confirm: function() {
                 $.ajax({
                          type: "POST",
                          url: ajaxurl,
                          data: { action: 'in_submit_quiz', 
                                  security: $('#hash').val(),
                                  quiz_id:$('#unit.quiz_title').attr('data-unit'),
                                  answers:JSON.stringify(answers)
                                },
                          cache: false,
                          success: function (html) {
                            $('#ajaxloader').addClass('disabled');
                            $('#unit_content').removeClass('loading');
                            
                            html = $.trim(html);
                            if(html.indexOf('##') > 0){
                              var nextunit = html.substr(0, html.indexOf('##')); 
                              html = html.substr((html.indexOf('##')+2));
                              if(nextunit.length>0){
                                $('#next_unit').removeClass('hide');
                                $('#next_unit').attr('data-unit',nextunit);  
                                $('#next_quiz').removeClass('hide');
                                $('#next_quiz').attr('data-unit',nextunit); 
                                $('#unit'+nextunit).find('a').addClass('unit');
                                $('#unit'+nextunit).find('a').attr('data-unit',nextunit);
                              }
                            }else{ 
                               if(html.indexOf('##') == 0){ 
                                  html = html.substr(2);
                                  console.log(html);
                               }else{
                                 $('#next_unit').removeClass('hide');
                               }
                            }

                            $('.main_unit_content').html(html);

                            $('.quiz_title .inquiz_timer').trigger('deactivate');
                            $('.in_quiz').trigger('question_loaded');
                            $this.removeClass('submit_inquiz');
                            $('.quiz_title .quiz_meta').addClass('hide');
                            $this.addClass('quiz_results_popup');
                            $this.attr('href',$('#results_link').val());
                            runnecessaryfunctions();
                            
                            $this.text(vibe_course_module_strings.check_results);
                            $('#unit'+$('#unit.quiz_title').attr('data-unit')).addClass('done');
                            $('body').find('.course_progressbar').removeClass('increment_complete');
                            $('body').find('.course_progressbar').trigger('increment');
                          }
                        });
                },
                cancel: function() {
                  $('#ajaxloader').addClass('disabled');
                  $('#unit_content').removeClass('loading');
                },
                confirmButton: vibe_course_module_strings.confirm,
                cancelButton: vibe_course_module_strings.cancel
              });
            },
            cancel: function() {
              $('#ajaxloader').addClass('disabled');
              $('#unit_content').removeClass('loading');
              return false;
            },
            confirmButton: vibe_course_module_strings.confirm,
            cancelButton: vibe_course_module_strings.cancel
          });
      }else{
        $.ajax({
                type: "POST",
                url: ajaxurl,
                data: { action: 'in_submit_quiz', 
                        security: $('#hash').val(),
                        quiz_id:$('#unit.quiz_title').attr('data-unit'),
                        answers:JSON.stringify(answers)
                      },
                cache: false,
                success: function (html) {
                  $('#ajaxloader').addClass('disabled');
                  $('#unit_content').removeClass('loading');
                  html = $.trim(html);
                  if(html.indexOf('##') > 0){
                    var nextunit = html.substr(0, html.indexOf('##')); 
                    html = html.substr((html.indexOf('##')+2));
                    if(nextunit.length>0){
                      $('#next_unit').removeClass('hide');
                      $('#next_unit').attr('data-unit',nextunit);  
                      $('#next_quiz').removeClass('hide');
                      $('#next_quiz').attr('data-unit',nextunit); 
                      $('#unit'+nextunit).find('a').addClass('unit');
                      $('#unit'+nextunit).find('a').attr('data-unit',nextunit);
                    }
                  }else{ 
                     if(html.indexOf('##') == 0){ 
                        html = html.substr(2);
                        console.log(html);
                     }else{
                       $('#next_unit').removeClass('hide');
                     }
                  }
                  
                  $('.main_unit_content').html(html);

                  $('.quiz_title .inquiz_timer').trigger('deactivate');
                  $('.in_quiz').trigger('question_loaded');
                  $this.removeClass('submit_inquiz');
                  $('.quiz_title .quiz_meta').addClass('hide');
                  $this.addClass('quiz_results_popup');
                  $this.attr('href',$('#results_link').val());
                  runnecessaryfunctions();
                  
                   
                  
                  $this.text(vibe_course_module_strings.check_results);
                  $('#unit'+$('#unit.quiz_title').attr('data-unit')).addClass('done');
                  $('body').find('.course_progressbar').removeClass('increment_complete');
                  $('body').find('.course_progressbar').trigger('increment');
                }
              });
      } 
    }
  }else{
    $('#ajaxloader').addClass('disabled');
    $('#unit_content').removeClass('loading');
    alert(vibe_course_module_strings.submit_quiz_error);
  }
});


$( 'body' ).delegate( '.in_quiz .pagination ul li a.quiz_page', 'click', function(event){
  event.preventDefault();
   var $this=$(this);
   var page = $(this).text();
   $('#ajaxloader').removeClass('disabled');
   $('#unit_content').addClass('loading');
   $.ajax({
            type: "POST",
            url: ajaxurl,
            data: { action: 'in_start_quiz', 
                    security: $('#hash').val(),
                    quiz_id:$('#unit.quiz_title').attr('data-unit'),
                    page: page
                  },
            cache: false,
            success: function (html) {
              $('#ajaxloader').addClass('disabled');
              $('#unit_content').removeClass('loading');
              $('.main_unit_content').html(html);
              $('.in_quiz').trigger('question_loaded');
              $this.removeClass('start_quiz');
              $this.addClass('submit_quiz');
              runnecessaryfunctions();
              $this.text(vibe_course_module_strings.submit_quiz);
              $('body,html').animate({
                  scrollTop: 0
                }, 1200);
            }
          });
});

$( 'body' ).delegate( '.in_quiz', 'question_loaded',function(){
  runnecessaryfunctions();
  $('.quiz_meta').trigger('progress_check');
  if($('.matchgrid_options').hasClass('saved_answer')){
        var id;
        $('.matchgrid_options li').each(function(index,value){
            id = $('.matchgrid_options').attr('data-match'+index);
            $(this).append($('#'+id));
        });
    }

  jQuery('.in_question .question_options.sort').each(function(){

    var defaultanswer='1';
    var lastindex = $('ul.question_options li').size();
    if(lastindex>1)
    for(var i=2;i<=lastindex;i++){
      defaultanswer = defaultanswer+','+i;
    }

    jQuery(this).sortable({
      revert: true,
      cursor: 'move',
      refreshPositions: true, 
      opacity: 0.6,
      scroll:true,
      containment: 'parent',
      placeholder: 'placeholder',
      tolerance: 'pointer',
      update: function( event, ui ) {
          var order = $('.question_options.sort').sortable('toArray').toString();
          var id = $(this).parent().attr('data-ques');
          localStorage.setItem(id,order);
          $('.quiz_meta').trigger('progress_check');
      }
    }).disableSelection();
  });
  //Fill in the Blank Live EDIT
  $(".live-edit").liveEdit({
      afterSaveAll: function(params) {
        return false;
      }
  });

  $(".vibe_fillblank").on('keyup',function(){
      var value = escape($(this).text().trim());
      var id = $(this).parent().parent().parent().parent().attr('data-ques');
      localStorage.setItem(id,value);
      $('.quiz_meta').trigger('progress_check');
  });

  //Match question type
  $('.in_question .question_options.match').each(function(){
      $(this).droppable({
        drop: function( event, ui ){
          $(ui.draggable).removeAttr('style');
          $( this )
                .addClass( "ui-state-highlight" )
                .append($(ui.draggable))
        }
      });
      $(this).find('li').draggable({
        revert: "invalid",
        containment:$(this).parent()
      });
  });
  
  $( ".matchgrid_options li" ).droppable({
      activeClass: "ui-state-default",
      hoverClass: "ui-state-hover",
      drop: function( event, ui ){
        childCount = $(this).find('li').length;
        $(ui.draggable).removeAttr('style');
        if (childCount !=0){
            return;
        }  
        
         $( this )
            .addClass( "ui-state-highlight" )
            .append($(ui.draggable));
        var value='';
        $(this).parent().find('li.match_option').each(function(){
            var id = $(this).attr('id');
            if( jQuery.isNumeric(id))
              value +=id+',';
        });     
        var id = $(this).parent().parent().parent().parent().attr('data-ques');
        localStorage.setItem(id,value);
        $('.quiz_meta').trigger('progress_check');
      }
    });

  $('.question.largetext+textarea').on('keyup',function(){
      var value = $(this).val();
      var id = $(this).parent().attr('data-ques');
      localStorage.setItem(id,value);
      $('.quiz_meta').trigger('progress_check');
  });
  $('.question.smalltext+input').on('keyup',function(){
      var value = $(this).val();
      var id = $(this).parent().attr('data-ques');
      localStorage.setItem(id,value);
      $('.quiz_meta').trigger('progress_check');
  });
  $('#vibe_select_dropdown').change(function(){
      var id = $(this).parent().parent().attr('data-ques');
      var value = $(this).val();
      localStorage.setItem(id,value);
      $('.quiz_meta').trigger('progress_check');
  });
  $('.question_options.truefalse li').click(function(){
      var id = $(this).find('input:checked').attr('name');
      var value = $(this).find('input:checked').val();
      localStorage.setItem(id,value);
      $('.quiz_meta').trigger('progress_check');
  });
  $('.question_options.single li').click(function(){
      var id = $(this).find('input:checked').attr('name');
      var value = $(this).find('input:checked').val();
      localStorage.setItem(id,value);
      $('.quiz_meta').trigger('progress_check');
  });
  $('.question_options.multiple li').click(function(){
      var iclass= $(this).find('input:checked').attr('class');
      var id=$(this).parent().parent().attr('data-ques');
      var value = '';
      $(this).parent().find('input:checked').each(function(){
        value += $(this).val()+',';
      });
      localStorage.setItem(id,value);
      $('.quiz_meta').trigger('progress_check');
  });
});


$( 'body' ).delegate( '.in_quiz', 'question_loaded',function(){
  if(typeof questions_json !== 'undefined') {
    $.each(questions_json, function(key, value) { 
        $('.in_question[data-ques='+value+']').each(function(){
            var $this = $(this);
            var type = $(this).find('.question').attr('class');
            switch(type){
              case 'question match':
                var sval =localStorage.getItem(value);
                if(sval !== null){
                  var new_vals = sval.split(',');
                  $.each(new_vals,function(k,vals){
                    if($.isNumeric(vals))
                      $this.find('.matchgrid_options>ul>li').eq(k).append($('#'+vals+'.ques'+value));
                  });
                }
              break;
              case 'question sort':
                var sval =localStorage.getItem(value);
                if(sval !== null){
                  var new_vals = sval.split(',');
                  $.each(new_vals,function(k,vals){ 
                    if($.isNumeric(vals))
                      $this.find('.question_options.sort>li#'+vals+'.ques'+value).remove().appendTo('.question_options.sort');
                  });
                }
              break;
              case 'question single':
                var sval =localStorage.getItem(value);
                if(sval !== null)
                $(this).find('input[value="'+sval+'"]').prop( "checked", true );
              break;
              case 'question multiple':
                var sval =localStorage.getItem(value);
                if(sval !== null){
                  var new_vals = sval.split(',');
                  $.each(new_vals,function(k,vals){
                    $this.find('input[value="'+vals+'"]').prop( "checked", true );
                  });
                }
              break;
              case 'question select':
                $(this).find('select').val(localStorage.getItem(value));
              break;
              case 'question truefalse':
                var sval =localStorage.getItem(value);
                if(sval !== null)
                $(this).find('input[value="'+sval+'"]').prop( "checked", true );
              break;
              case 'question fillblank':
              var saved = localStorage.getItem(value);
              if(saved !== 'null' && saved){
                $(this).find('.vibe_fillblank').text(saved);
              }
              break;
              case 'question smalltext':
                $(this).find('input').val(localStorage.getItem(value));
              break;
              case 'question largetext':
                $(this).find('textarea').val(localStorage.getItem(value));
              break;
            }
        });
    });
  }
});
$( 'body' ).delegate( '.quiz_meta', 'progress_check',function(){
     if(typeof all_questions_json !== 'undefined') {
       var num = all_questions_json.length;
       var progress=0;
        $.each(all_questions_json, function(key, value) { 
          if(localStorage.getItem(value) !== null){
            progress++;
          }
        });
       if(!$('.quiz_meta').hasClass('show_progress')){
        $('.quiz_meta').addClass('show_progress');
       }
       $('.quiz_meta i span').text(progress+'/'+num);
       var percentage = Math.round(100*(progress/num));
       $('.quiz_meta .progress .bar').css('width',percentage+'%');
    }
});
/*=== In Unit Questions ===*/
$(document).ready(function($){
  
  $('.unit_content').on('unit_traverse',function(){
      
    $('.question').each(function(){
      var $this = $(this);
      jQuery('.question_options.sort').each(function(){
        jQuery(this).sortable({
          revert: true,
          cursor: 'move',
          refreshPositions: true, 
          opacity: 0.6,
          scroll:true,
          containment: 'parent',
          placeholder: 'placeholder',
          tolerance: 'pointer',
        }).disableSelection();
    });
    //Fill in the Blank Live EDIT
    $(".live-edit").liveEdit({
        afterSaveAll: function(params) {
          return false;
        }
    });

    //Match question type
    $('.question_options.match').droppable({
      drop: function( event, ui ){
      $(ui.draggable).removeAttr('style');
      $( this )
            .addClass( "ui-state-highlight" )
            .append($(ui.draggable))
      }
    });
    $('.question_options.match li').draggable({
      revert: "invalid",
      containment:'#question'
    });
    $( ".matchgrid_options li" ).droppable({
        activeClass: "ui-state-default",
        hoverClass: "ui-state-hover",
        drop: function( event, ui ){
          childCount = $(this).find('li').length;
          $(ui.draggable).removeAttr('style');
          if (childCount !=0){
              return;
          }  
          
           $( this )
              .addClass( "ui-state-highlight" )
              .append($(ui.draggable))
        }
      });

      $this.find('.check_answer').click(function(){
          $this.find('.message').remove();
          var id = $(this).attr('data-id');
          var answers = eval('ans_json'+id);
          
          switch(answers['type']){
            case 'select':
            case 'truefalse':
            case 'single':
            case 'sort':
            case 'match':
              value ='';
              if($this.find('input[type="radio"]:checked').length){
                value = $this.find('input[type="radio"]:checked').val();
              }
              if($this.find('select').length){
                value = $this.find('option:selected').val();
              }

              $this.find('.question_options.sort li.sort_option').each(function(){
                var id = $(this).attr('id');
                if( jQuery.isNumeric(id))
                  value +=id+',';
              });

              $this.find('.matchgrid_options li.match_option').each(function(){
                var id = $(this).attr('id');
                if( jQuery.isNumeric(id))
                  value +=id+',';
              });

              if(answers['type'] == 'sort' || answers['type'] == 'match'){
                value = value.slice(0,-1);
              }
       
                if( value == answers['answer']){
                  $this.append('<div class="message success">'+vibe_course_module_strings.correct+'</div>');
                }else{
                  $this.append('<div class="message error">'+vibe_course_module_strings.incorrect+'</div>');
                }
              
            break;
            case 'multiple':
              if($this.find('input[type="checkbox"]:checked').length){
                  if($this.find('input[type="checkbox"]:checked').length == answers['answer'].length){
                    $this.find('input[type="checkbox"]:checked').each(function(){
                      if ($.inArray($(this).val(), answers['answer']) == -1){
                        $this.append('<div class="message error">'+vibe_course_module_strings.incorrect+'</div>');
                        return false;  
                      }
                    });
                    $this.append('<div class="message success">'+vibe_course_module_strings.correct+'</div>');
                  }else{
                    $this.append('<div class="message error">'+vibe_course_module_strings.incorrect+'</div>');
                  }                  
              }
            break;

          }
      });
    });
  });

  /* === simple notes and discussion ===*/
  $('.unit_content').on('unit_traverse',function(){
    $('#discussion').each(function(){

        var $this = $(this);
        $('.add_comment').click(function(){
          $('.add_unit_comment_text').toggleClass('hide');
        });
      $('body').delegate('.unit_content .commentlist li .reply','click',function(){
          var $reply = $(this);
          $reply.addClass('hide');
          $('.unit_content .commentlist li .add_unit_comment_text').remove();
          var form = $('#add_unit_comment').clone().removeClass('hide').attr('id','').appendTo($reply.parent());
          form.find('.post_question').attr('data-parent',$reply.parent().parent().attr('data-id'));
          form.find('.post_question').addClass('comment_reply').text($reply.text());
          form.find('.post_question').removeClass('post_question');
          $('#discussion').trigger('ready');
      });
      $('body').delegate('.unit_content .commentlist li .cancel','click',function(e){
          if($(this).parent().parent().find('.reply').length){
            $(this).parent().parent().find('.reply').removeClass('hide');
            $(this).parent().parent().find('.add_unit_comment_text').remove();
          }
      });
      $('#add_unit_comment .cancel').click(function(){
        $('#add_unit_comment').addClass('hide');
      });
       $('.post_question').on('click',function(e){ 
            e.preventDefault(); 
            var textarea=$(this).parent().find('textarea');
            var val = textarea.val();

            $this.addClass('loading');

            if(val.length){ 
               $.ajax({
                      type: "POST",
                      url: ajaxurl,
                      data: { action: 'add_unit_comment', 
                              security: $('#hash').val(),
                              text: val,
                              unit_id: $this.attr('data-unit')
                            },
                      cache: false,
                      success: function (html) {
                          $this.removeClass('loading');
                          if(html.indexOf('<li') == 0){
                              $this.find('ol.commentlist').append(html);
                              textarea.val('');
                              $('.add_unit_comment_text').addClass('hide');
                          }else{
                            $this.append(html);
                          }
                      }
              });
            }else{
              $this.append('<div class="message">'+vibe_course_module_strings.incorrect+'</div>');
            }
        });
        $('#discussion').on('ready',function(){
            $('.comment_reply').on('click',function(e){ 
              e.preventDefault(); 
              var textarea=$(this).parent().find('textarea');
              var val = textarea.val();
              var parent = $(this).attr('data-parent');

              $this.addClass('loading');

              if(val.length){ 
                 $.ajax({
                        type: "POST",
                        url: ajaxurl,
                        data: { action: 'add_unit_comment', 
                                security: $('#hash').val(),
                                text: val,
                                parent:parent,
                                unit_id: $this.attr('data-unit')
                              },
                        cache: false,
                        success: function (html) {
                            $this.removeClass('loading');
                            if(html.indexOf('<li') == 0){
                              
                                $('#comment-'+parent).append('<ul class="children">'+html+'</ul>');
                                $('#comment-'+parent +' .add_unit_comment_text').remove();
                                $('.unit_content .commentlist li .reply').removeClass('hide');
                              
                              textarea.val('');
                              $('.add_unit_comment_text').addClass('hide');
                            }else{
                              $this.append(html);
                            }
                        }
                });
              }else{
                $this.append('<div class="message">'+vibe_course_module_strings.incorrect+'</div>');
              }
          });
        });
        $('.load_more_comments').click(function(){
            var page = parseInt($(this).attr('data-page'));
            var max = parseInt($(this).attr('data-max'));
            var $load = $(this);
            $this.addClass('loading');
             $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: { action: 'load_unit_comments', 
                            security: $('#hash').val(),
                            page: page,
                            unit_id: $this.attr('data-unit')
                          },
                    cache: false,
                    success: function (html) {
                        $this.removeClass('loading');
                        $this.find('.commentlist').append(html);
                        var count = parseInt($load.find('span').text());
                        var per = parseInt($load.attr('data-per'));
                        count = count -per;
                        page++;
                        $load.attr('data-page',page);
                        $load.find('span').text(count);
                        if(max <= page)
                          $load.hide(200);
                    }
            });
        });


    });
  });
  if($('.unit_content').length){
    $('.unit_content').trigger('unit_traverse');
  }    
});

})(jQuery);
