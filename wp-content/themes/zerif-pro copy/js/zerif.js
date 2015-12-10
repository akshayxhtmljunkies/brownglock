/* LOADER */
jQuery(document).ready(function(){
	
	var zerif_frame = jQuery('iframe.zerif_google_map');
	var zerif_frameSrc = new Array();

    if( zerif_frame.length ){
		jQuery.each( zerif_frame, function(i, f){
			zerif_frameSrc[i] = jQuery(f).attr('src');
            /*remove the src attribute so window will ignore these iframes*/
            jQuery(f).attr('src', '');
        }); 
	}
	
	function zerif_display_iframe_map() {
		if( zerif_frame.length ){
			jQuery.each( zerif_frame, function(a, x){
				/*put the src attribute value back*/
				jQuery(x).attr('src', zerif_frameSrc[a]);
			});
		}	
	}
	
	jQuery(".status").fadeOut();
	jQuery(".preloader").delay(1000).fadeOut("slow");
	setTimeout(zerif_display_iframe_map, 500);
});

/** BACKGROUND SLIDER ***/
jQuery(document).ready(function(){
	if ( jQuery('.fadein-slider .slide-item').length > 1 ) {
		jQuery('.fadein-slider .slide-item:gt(0)').hide();
		setInterval(function(){
			jQuery('.fadein-slider :first-child').fadeOut(2000).next('.slide-item').fadeIn(2000).end().appendTo('.fadein-slider');
		}, 10000);
	}
});


/* RESPONSIVE BACKGROUND ON MOBILE */
var portraitViewInit = 0, // Initial viewport orientation: Default Landscape
    resize = false;
jQuery( document ).ready( zerif_bg_responsive );
jQuery( window ).resize( zerif_bg_responsive );
function zerif_bg_responsive() {
  if( jQuery( '#mobile-bg-responsive' ).length > 0 && jQuery( 'body.custom-background' ).length > 0 && isMobile.any() ) {
    // There is background image
    var windowWidth   = window.innerWidth;
    var windowHeight  = window.innerHeight;
    // Check if orientation is Portrait or Landscape: Default is Landscape
    portraitView = 0;
    if( windowHeight >= windowWidth ) {
      portraitView = 1;
    }
    if( isMobile.iOS() ) {
      windowHeight+=100;
    }
    if( portraitViewInit != portraitView ) {
      var bgHelper = jQuery( '.zerif-mobile-bg-helper-bg' );
      if( !resize ) {
        var imgURL = jQuery( 'body.custom-background' ).css( 'background-image' );
        jQuery( '.zerif-mobile-bg-helper-bg-inside' ).css( { 
          'background-image': imgURL,
        } ).addClass( 'zerif-mobile-h-inside' );
        jQuery( '.zerif-mobile-bg-helper-wrap-all' ).addClass( 'zerif-mobile-h-all' );
        jQuery( '.zerif-mobile-bg-helper-content' ).addClass( 'zerif-mobile-h-content' );
        bgHelper.css( {
          'width':      windowWidth,
          'height':     windowHeight
        } ).addClass( 'zerif-mobile-h-bg' );
        portraitViewInit = portraitView;
        resize = true;
      } else {
        // Resize window
        bgHelper.css( {
          'width':      windowWidth,
          'height':     windowHeight
        } );
        portraitViewInit = portraitView;
      }
    }
  }
}


/*** DROPDOWN FOR MOBILE MENU */
var callback_mobile_dropdown = function () {

	var navLi = jQuery('#site-navigation li');

    navLi.each(function(){
        if ( jQuery(this).find('ul').length > 0 && !jQuery(this).hasClass('has_children') ){
            jQuery(this).addClass('has_children');
            jQuery(this).find('a').first().after('<p class="dropdownmenu"></p>');
        }
    });
    jQuery('.dropdownmenu').click(function(){
        if( jQuery(this).parent('li').hasClass('this-open') ){
            jQuery(this).parent('li').removeClass('this-open');
        }else{
            jQuery(this).parent('li').addClass('this-open');
        }
    });

    navLi.find('a').click(function(){
    	jQuery('.navbar-toggle').addClass('collapsed');
       	jQuery('.collapse').removeClass('in'); 
    });

};
jQuery(document).ready(callback_mobile_dropdown);


/* show/hide reCaptcha */
jQuery(document).ready(function() {

  var thisOpen = false;
  jQuery('.contact-form .form-control').each(function(){
    if ( (typeof jQuery(this).val() != 'undefined') && (jQuery(this).val().length > 0) ){
      thisOpen = true;
      jQuery('.zerif-g-recaptcha').css('display','block').delay(1000).css('opacity','1');
      return false;
    }
  });
  if ( thisOpen == false && (typeof jQuery('.contact-form textarea').val() != 'undefined') && (jQuery('.contact-form textarea').val().length > 0) ) {
    thisOpen = true;
    jQuery('.zerif-g-recaptcha').css('display','block').delay(1000).css('opacity','1');
  }
  jQuery('.contact-form input, .contact-form textarea').focus(function(){
    if ( !jQuery('.zerif-g-recaptcha').hasClass('recaptcha-display') ) {
        jQuery('.zerif-g-recaptcha').css('display','block').delay(1000).css('opacity','1');
    }
  });

});

/* Bootstrap Fix */
if (navigator.userAgent.match(/IEMobile\/10\.0/)) {
	
	var msViewportStyle = document.createElement('style')

	msViewportStyle.appendChild(

		document.createTextNode(

			'@-ms-viewport{width:auto!important}'

		)

	)

	document.querySelector('head').appendChild(msViewportStyle);

}

jQuery(document).ready(function() {
	
	/* PARALLAX */
	var jQuerywindow = jQuery(window);

	jQuery('div[data-type="background"], header[data-type="background"], section[data-type="background"]').each(function(){

		var jQuerybgobj = jQuery(this);

		jQuery(window).scroll(function() {

			var yPos = -(jQuerywindow.scrollTop() / jQuerybgobj.data('speed'));

			var coords = '50% '+ yPos + 'px';

			jQuerybgobj.css({ 

				backgroundPosition: coords 

			});

		});

	});
	
});


/*=================================
===  SMOOTH SCROLL             ====
=================================== */

jQuery(document).ready(function(){
  jQuery('#site-navigation a[href*=#]:not([href=#]), header.header a[href*=#]:not([href=#])').bind('click',function () {
    var headerHeight;
    var hash    = this.hash;
    var idName  = hash.substring(1);    // get id name
    var alink   = this;                 // this button pressed
    // check if there is a section that had same id as the button pressed
    if ( jQuery('section [id*=' + idName + ']').length > 0 && jQuery(window).width() >= 751 ){
      jQuery('.current').removeClass('current');
      jQuery(alink).parent('li').addClass('current');
    }else{
      jQuery('.current').removeClass('current');
    }
    if ( jQuery(window).width() >= 751 ) {
      headerHeight = jQuery('#main-nav').height();
    } else {
      headerHeight = 0;
    }
    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
      var target = jQuery(this.hash);
      target = target.length ? target : jQuery('[name=' + this.hash.slice(1) +']');
      if (target.length) {
        jQuery('html,body').animate({
          scrollTop: target.offset().top - headerHeight + 5
        }, 1200);
        return false;
      }
    }
  });
});

jQuery(document).ready(function(){
    var headerHeight;
    jQuery('.current').removeClass('current');
    jQuery('#site-navigation a[href$="' + window.location.hash + '"]').parent('li').addClass('current');
    if ( jQuery(window).width() >= 751 ) {
      headerHeight = jQuery('#main-nav').height();
    } else {
      headerHeight = 0;
    }
    if (location.pathname.replace(/^\//,'') == window.location.pathname.replace(/^\//,'') && location.hostname == window.location.hostname) {
      var target = jQuery(window.location.hash);
      if (target.length) {
        jQuery('html,body').animate({
          scrollTop: target.offset().top - headerHeight + 5
        }, 1200);
        return false;
      }
    }
});

/* TOP NAVIGATION MENU SELECTED ITEMS */
function zerif_scrolled() {
    
      if ( jQuery(window).width() >= 751 ) {
          var zerif_scrollTop = jQuery(window).scrollTop();       // cursor position
          var headerHeight = jQuery('#main-nav').outerHeight();   // header height
          var isInOneSection = 'no';                              // used for checking if the cursor is in one section or not
          // for all sections check if the cursor is inside a section
          jQuery("section").each( function() {
            var thisID = '#' + jQuery(this).attr('id');           // section id
            var zerif_offset = jQuery(this).offset().top;         // distance between top and our section
            var thisHeight  = jQuery(this).outerHeight();         // section height
            var thisBegin   = zerif_offset - headerHeight;                      // where the section begins
            var thisEnd     = zerif_offset + thisHeight - headerHeight;         // where the section ends  
            // if position of the cursor is inside of the this section
            if ( zerif_scrollTop >= thisBegin && zerif_scrollTop <= thisEnd ) {
              isInOneSection = 'yes';
              jQuery('.current').removeClass('current');
              jQuery('#site-navigation a[href$="' + thisID + '"]').parent('li').addClass('current');    // find the menu button with the same ID section
              return false;
            }
            if (isInOneSection == 'no') {
              jQuery('.current').removeClass('current');
            }
          });
      }
}
jQuery(window).on('scroll',zerif_scrolled);



jQuery(window).load(function() {
	
	/* SUBSCRIBE  */
	jQuery("form :input").each(function(index, elem) {
	
		var eId = jQuery(elem).attr("class");
	
		if( (eId == "sib-email-area") || (eId == "sib-NAME-area") ) {
		
			var label = null;
			if (eId && (label = jQuery(elem).parents("form").find("label."+eId)).length == 1) {
				jQuery(elem).attr("placeholder", jQuery(label).html());
				jQuery(label).remove();
			}
		}
	});
});	


/* TOP NAVIGATION MENU SELECTED ITEMS */
jQuery(window).scroll(function(){
	
	var zerif_scrollTop = jQuery(window).scrollTop();
	var zerif_windowHeight = jQuery(window).height();
	
	jQuery("section").each( function() {
		
		var zerif_offset = jQuery(this).offset();
		
		if (zerif_scrollTop <= zerif_offset.top && (jQuery(this).height() + zerif_offset.top) < (zerif_scrollTop + zerif_windowHeight) ) {
				
			jQuery('ul.nav > li a').each( function() {
				jQuery(this).removeClass('nav-active');
			});
			
			var zerif_current_id_visible = jQuery(this).attr('id');
			
			jQuery('ul.nav > li a').each( function() {
				if( jQuery(this).attr('href').indexOf(zerif_current_id_visible) >= 0 ) {
					jQuery('ul.nav > li a').each( function() {
						jQuery(this).removeClass('nav-active');
					});
					jQuery(this).addClass('nav-active');
				}
				
			});
		}
		
	});

});


/* SETS THE HEIGHT OF THE HEADER */
jQuery(window).load(function(){
  setminHeightHeader();
});
jQuery(window).resize(function() {
  setminHeightHeader();
});
function setminHeightHeader() {
  if ( jQuery('#main-nav').css('min-height') != 75 || jQuery('.header').css('min-height') != 75 ) {
    jQuery('#main-nav').css('min-height',75);
    jQuery('.header').css('min-height',75);
  }
  if ( jQuery(window).width() > 750 ) {
      var minHeight = parseInt( jQuery('#main-nav').height() );
      jQuery('#main-nav').css('min-height',minHeight);
      jQuery('.header').css('min-height',minHeight);
  }
}
/* - */


/* STICKY FOOTER */
jQuery(window).load(function(){
  fixFooterBottom();
});
jQuery(window).resize(function() {
  fixFooterBottom();
});

function fixFooterBottom(){

	var header      = jQuery('header.header');
	var footer      = jQuery('footer#footer');
	var content     = jQuery('.site-content > .container');

	content.css('min-height', '1px');

	var headerHeight  = header.outerHeight();
	var footerHeight  = footer.outerHeight();
	var contentHeight = content.outerHeight();
	var windowHeight  = jQuery(window).height();

	var totalHeight = headerHeight + footerHeight + contentHeight;

	if (totalHeight<windowHeight){
	  content.css('min-height', windowHeight - headerHeight - footerHeight );
	}else{
	  content.css('min-height','1px');
	}
}


/*** CENTERED MENU */
var callback_menu_align = function () {

	var headerWrap 		= jQuery('.header');
	var navWrap 		= jQuery('#site-navigation');
	var logoWrap 		= jQuery('.responsive-logo');
	var containerWrap 	= jQuery('.container');
	var classToAdd		= 'menu-align-center';

	if ( headerWrap.hasClass(classToAdd) ) 
	{
        headerWrap.removeClass(classToAdd);
	}
    var logoWidth 		= logoWrap.outerWidth();
    var menuWidth 		= navWrap.outerWidth();
    var containerWidth 	= containerWrap.width();

    if ( menuWidth + logoWidth > containerWidth ) {
        headerWrap.addClass(classToAdd);
    }
    else
    {
        if ( headerWrap.hasClass(classToAdd) )
        {
            headerWrap.removeClass(classToAdd);
        }
    }
}
jQuery(window).load(callback_menu_align);
jQuery(window).resize(callback_menu_align);

var isMobile = {
    Android: function() {
        return navigator.userAgent.match(/Android/i);
    },
    BlackBerry: function() {
        return navigator.userAgent.match(/BlackBerry/i);
    },
    iOS: function() {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
    },
    Opera: function() {
        return navigator.userAgent.match(/Opera Mini/i);
    },
    Windows: function() {
        return navigator.userAgent.match(/IEMobile/i);
    },
    any: function() {
        return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
    }
};

/* Rollover on mobile devices */
if( isMobile.any() ) {

  /* Our team section */
    jQuery('.team-member').on('click', function(){
        jQuery('.team-member-open').removeClass('team-member-open');
        jQuery(this).addClass('team-member-open');
        event.stopPropagation();
    });    
    jQuery("html").click(function() {
      jQuery('.team-member-open').removeClass('team-member-open');
  });
  
  /* Portfolio section */
  jQuery(document).ready(function(){
      jQuery('.cbp-rfgrid li').prepend('<p class="cbp-rfgrid-tr"></p>');
  });
    jQuery('.cbp-rfgrid li').on('click', function(){
        if ( !jQuery(this).hasClass('cbp-rfgrid-open') ){
            jQuery('.cbp-rfgrid-tr').css('display','block');
            jQuery('.cbp-rfgrid-open').removeClass('cbp-rfgrid-open');
            
            jQuery(this).addClass('cbp-rfgrid-open');
            jQuery(this).find('.cbp-rfgrid-tr').css('display','none');
            event.stopPropagation();            
        }
    });
    jQuery("html").click(function() {
        jQuery('.cbp-rfgrid-tr').css('display','block');
        jQuery('.cbp-rfgrid-open').removeClass('cbp-rfgrid-open');
  });
    
}

/* latest news */
jQuery(window).load(function(){

  if( jQuery( '#carousel-homepage-latestnews').length > 0 ) {
    if( jQuery( '#carousel-homepage-latestnews div.item' ).length < 2 ) {
      jQuery( '#carousel-homepage-latestnews > a' ).css('display','none');
    }
    var maxheight = 0;
    jQuery( '#carousel-homepage-latestnews div.item' ).each(function(){
      if( jQuery(this).height() > maxheight ) {
        maxheight = jQuery(this).height();
      }
    });
    jQuery( '#carousel-homepage-latestnews div.item' ).height(maxheight);
  }

});


/* testimonial Masonry style */
var window_width_old;
var exist_class = false;
jQuery(document).ready(function(){
  if( jQuery('.testimonial-masonry').length>0 ){
    exist_class = true;
    window_width_old = jQuery('.container').outerWidth();
    if( window_width_old < 970 ) {
        jQuery('.testimonial-masonry').zerifgridpinterest({columns: 1,selector: '.zerif_testim'});
    } else {
        jQuery('.testimonial-masonry').zerifgridpinterest({columns: 3,selector: '.zerif_testim'});
    }
  }
});

jQuery(window).resize(function() {
    if( window_width_old != jQuery('.container').outerWidth() && exist_class === true ){
        window_width_old = jQuery('.container').outerWidth();
        if( window_width_old < 970 ) {
            jQuery('.testimonial-masonry').zerifgridpinterest({columns: 1,selector: '.zerif_testim'});
        } else {
            jQuery('.testimonial-masonry').zerifgridpinterest({columns: 3,selector: '.zerif_testim'});
        }
    }
});


(function ($, window, document, undefined) {
    var defaults = {
            columns:                3,
            selector:               'div',
            excludeParentClass:     '',
        };
    function ZerifGridPinterest(element, options) {
        this.element    = element;
        this.options    = $.extend({}, defaults, options);
        this.defaults   = defaults;
        this.init();
    }
    ZerifGridPinterest.prototype.init = function () {
        var self            = this,
            $container      = $(this.element);
            $select_options = $(this.element).children();
        self.make_magic( $container, $select_options );
    };
    ZerifGridPinterest.prototype.make_magic = function (container) {
        var self            = this;
            $container      = $(container),
            columns_height  = [],
            prefix          = 'zerif',
            unique_class    = prefix + '_grid_' + self.make_unique();
            local_class     = prefix + '_grid';
        var classname;
        var substr_index    = this.element.className.indexOf(prefix+'_grid_');
        if( substr_index>-1 ) {
            classname = this.element.className.substr( 0, this.element.className.length-unique_class.length-local_class.length );
        } else {
            classname = this.element.className;
        }
        var my_id;
        if( this.element.id == '' ) {
            my_id = prefix+'_id_' + self.make_unique();
        } else {
            my_id = this.element.id;
        }
        $container.after('<div id="' + my_id + '" class="' + classname + ' ' + local_class + ' ' + unique_class + '"></div>');
        var i;
        for(i=1; i<=this.options.columns; i++){
            columns_height.push(0);
            var first_cols = '';
            var last_cols = '';
            if( i%self.options.columns == 1 ) { first_cols = prefix + '_grid_first'; }
            if( i%self.options.columns == 0 ) { first_cols = prefix + '_grid_last'; }
            $('.'+unique_class).append('<div class="' + prefix + '_grid_col_' + this.options.columns +' ' + prefix + '_grid_column_' + i +' ' + first_cols + ' ' + last_cols + '"></div>');
        }
        if( this.element.className.indexOf(local_class)<0 ){
            $container.children(this.options.selector).each(function(index){
                var min = Math.min.apply(null,columns_height);
                var this_index = columns_height.indexOf(min)+1;
                $(this).attr(prefix+'grid-attr','this-'+index).appendTo('.'+unique_class +' .' + prefix + '_grid_column_'+this_index);
                columns_height[this_index-1] = $('.'+unique_class +' .' + prefix + '_grid_column_'+this_index).height();
            });
        } else {
            var no_boxes = $container.find(this.options.selector).length;
            var i;
            for( i=0; i<no_boxes; i++ ){
                var min = Math.min.apply(null,columns_height);
                var this_index = columns_height.indexOf(min)+1;
                $('#'+this.element.id).find('['+prefix+'grid-attr="this-'+i+'"]').appendTo('.'+unique_class +' .' + prefix + '_grid_column_'+this_index);
                columns_height[this_index-1] = $('.'+unique_class +' .' + prefix + '_grid_column_'+this_index).height();
            }
        }
        $container.remove();
    }
    ZerifGridPinterest.prototype.make_unique = function () {
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        for( var i=0; i<10; i++ )
            text += possible.charAt(Math.floor(Math.random() * possible.length));
        return text;
    }
    $.fn.zerifgridpinterest = function (options) {
        return this.each(function () {
            var value = '';
            if (!$.data(this, value)) {
                $.data(this, value, new ZerifGridPinterest(this, options) );
            }
        });
    }
})(jQuery);
/* Header section */
jQuery(window).load(zerif_parallax_effect);
jQuery(window).resize(zerif_parallax_effect);

function zerif_parallax_effect(){

    if( jQuery('#parallax_move').length>0 ) {
      var scene = document.getElementById('parallax_move');
      var window_width = jQuery(window).outerWidth();
      jQuery('#parallax_move').css({
        'width':            window_width + 120,
        'margin-left':      -60,
        'margin-top':       -60,
        'position':         'absolute',
      });
      var h = jQuery('header#home').outerHeight();
      jQuery('#parallax_move').children().each(function(){
        jQuery(this).css({
            'height': h+100,
        });
      });
      if( !isMobile.any() ) {
        var parallax = new Parallax(scene);
      } else {
        jQuery('#parallax_move').css({
          'z-index': '0',
        });
        jQuery('#parallax_move .layer').css({
          'position': 'absolute',
          'top': '0',
          'left': '0',
          'z-index': '1',
        });
      }
    }

}


