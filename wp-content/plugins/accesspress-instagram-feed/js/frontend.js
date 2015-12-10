(function ($) {
	    $(function () {
		     $(window).load(function(){
			  	var $grid = $('.ifgrid').isotope({
			    itemSelector: '.element-itemif'
		  	});
		});
	});
      
}(jQuery));

jQuery(document).ready(function($) {
  $("#owl-demo").owlCarousel({
    autoPlay: 3000,
    items : 4,
    itemsDesktop : [1199,3],
    itemsDesktopSmall : [979,3]
  });
  
});

// Mosaic View Layout Javascript 
function initHoverEffectForThumbView() {
    jQuery('.thumb-elem, .grid-elem header').each(function(){
      var thisElem = jQuery(this);
      var getElemWidth = thisElem.width();
      var getElemHeight = thisElem.height();
      var centerX = getElemWidth/2;
      var centerY = getElemHeight/2;

      thisElem.mouseenter(function(){
        thisElem.on('mousemove', function (e) {
          var mouseX = e.pageX - thisElem.offset().left;
                var mouseY = e.pageY - thisElem.offset().top;
                var mouseDistX = (mouseX / centerX) * 100 - 100;
                var mouseDistY = (mouseY / centerY) * 100 - 100;
                thisElem.find('img.the-thumb').css({
                  'left': -(mouseDistX/6.64) - 15 + "%",
                    'top':  -(mouseDistY/6.64) - 15 + "%"
                });
        });

        thisElem.find('.thumb-elem-section:not(.no-feat-img)').fadeIn('fast');
      }).mouseleave(function(){
        thisElem.find('.thumb-elem-section:not(.no-feat-img)').fadeOut('fast');
      });
    });
}


function initSimpleHoverEffectForThumbView() {
    jQuery('.thumb-elem, .grid-elem header').each(function(){
      var thisElem = jQuery(this);
      thisElem.mouseenter(function(){
        thisElem.find('.thumb-elem-section:not(.no-feat-img)').fadeIn('fast');
      }).mouseleave(function(){
        thisElem.find('.thumb-elem-section:not(.no-feat-img)').fadeOut('fast');
      });
    });
}

jQuery(window).load(function() {
  "use strict";

    if (!hoverEffect.disable_hover_effect && jQuery(window).width() > 768) {
     // jQuery('.thumb-elem, .grid-elem header').addClass('hovermove');
        initHoverEffectForThumbView();
    }else{
        initSimpleHoverEffectForThumbView();
    }
});
var hoverEffect = {"disable_hover_effect":""};