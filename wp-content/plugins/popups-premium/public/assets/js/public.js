/*!
 * imagesLoaded PACKAGED v3.1.8
 * JavaScript is all like "You images are done yet or what?"
 * MIT License
 */
(function(){function e(){}function t(e,t){for(var n=e.length;n--;)if(e[n].listener===t)return n;return-1}function n(e){return function(){return this[e].apply(this,arguments)}}var i=e.prototype,r=this,o=r.EventEmitter;i.getListeners=function(e){var t,n,i=this._getEvents();if("object"==typeof e){t={};for(n in i)i.hasOwnProperty(n)&&e.test(n)&&(t[n]=i[n])}else t=i[e]||(i[e]=[]);return t},i.flattenListeners=function(e){var t,n=[];for(t=0;e.length>t;t+=1)n.push(e[t].listener);return n},i.getListenersAsObject=function(e){var t,n=this.getListeners(e);return n instanceof Array&&(t={},t[e]=n),t||n},i.addListener=function(e,n){var i,r=this.getListenersAsObject(e),o="object"==typeof n;for(i in r)r.hasOwnProperty(i)&&-1===t(r[i],n)&&r[i].push(o?n:{listener:n,once:!1});return this},i.on=n("addListener"),i.addOnceListener=function(e,t){return this.addListener(e,{listener:t,once:!0})},i.once=n("addOnceListener"),i.defineEvent=function(e){return this.getListeners(e),this},i.defineEvents=function(e){for(var t=0;e.length>t;t+=1)this.defineEvent(e[t]);return this},i.removeListener=function(e,n){var i,r,o=this.getListenersAsObject(e);for(r in o)o.hasOwnProperty(r)&&(i=t(o[r],n),-1!==i&&o[r].splice(i,1));return this},i.off=n("removeListener"),i.addListeners=function(e,t){return this.manipulateListeners(!1,e,t)},i.removeListeners=function(e,t){return this.manipulateListeners(!0,e,t)},i.manipulateListeners=function(e,t,n){var i,r,o=e?this.removeListener:this.addListener,s=e?this.removeListeners:this.addListeners;if("object"!=typeof t||t instanceof RegExp)for(i=n.length;i--;)o.call(this,t,n[i]);else for(i in t)t.hasOwnProperty(i)&&(r=t[i])&&("function"==typeof r?o.call(this,i,r):s.call(this,i,r));return this},i.removeEvent=function(e){var t,n=typeof e,i=this._getEvents();if("string"===n)delete i[e];else if("object"===n)for(t in i)i.hasOwnProperty(t)&&e.test(t)&&delete i[t];else delete this._events;return this},i.removeAllListeners=n("removeEvent"),i.emitEvent=function(e,t){var n,i,r,o,s=this.getListenersAsObject(e);for(r in s)if(s.hasOwnProperty(r))for(i=s[r].length;i--;)n=s[r][i],n.once===!0&&this.removeListener(e,n.listener),o=n.listener.apply(this,t||[]),o===this._getOnceReturnValue()&&this.removeListener(e,n.listener);return this},i.trigger=n("emitEvent"),i.emit=function(e){var t=Array.prototype.slice.call(arguments,1);return this.emitEvent(e,t)},i.setOnceReturnValue=function(e){return this._onceReturnValue=e,this},i._getOnceReturnValue=function(){return this.hasOwnProperty("_onceReturnValue")?this._onceReturnValue:!0},i._getEvents=function(){return this._events||(this._events={})},e.noConflict=function(){return r.EventEmitter=o,e},"function"==typeof define&&define.amd?define("eventEmitter/EventEmitter",[],function(){return e}):"object"==typeof module&&module.exports?module.exports=e:this.EventEmitter=e}).call(this),function(e){function t(t){var n=e.event;return n.target=n.target||n.srcElement||t,n}var n=document.documentElement,i=function(){};n.addEventListener?i=function(e,t,n){e.addEventListener(t,n,!1)}:n.attachEvent&&(i=function(e,n,i){e[n+i]=i.handleEvent?function(){var n=t(e);i.handleEvent.call(i,n)}:function(){var n=t(e);i.call(e,n)},e.attachEvent("on"+n,e[n+i])});var r=function(){};n.removeEventListener?r=function(e,t,n){e.removeEventListener(t,n,!1)}:n.detachEvent&&(r=function(e,t,n){e.detachEvent("on"+t,e[t+n]);try{delete e[t+n]}catch(i){e[t+n]=void 0}});var o={bind:i,unbind:r};"function"==typeof define&&define.amd?define("eventie/eventie",o):e.eventie=o}(this),function(e,t){"function"==typeof define&&define.amd?define(["eventEmitter/EventEmitter","eventie/eventie"],function(n,i){return t(e,n,i)}):"object"==typeof exports?module.exports=t(e,require("wolfy87-eventemitter"),require("eventie")):e.imagesLoaded=t(e,e.EventEmitter,e.eventie)}(window,function(e,t,n){function i(e,t){for(var n in t)e[n]=t[n];return e}function r(e){return"[object Array]"===d.call(e)}function o(e){var t=[];if(r(e))t=e;else if("number"==typeof e.length)for(var n=0,i=e.length;i>n;n++)t.push(e[n]);else t.push(e);return t}function s(e,t,n){if(!(this instanceof s))return new s(e,t);"string"==typeof e&&(e=document.querySelectorAll(e)),this.elements=o(e),this.options=i({},this.options),"function"==typeof t?n=t:i(this.options,t),n&&this.on("always",n),this.getImages(),a&&(this.jqDeferred=new a.Deferred);var r=this;setTimeout(function(){r.check()})}function f(e){this.img=e}function c(e){this.src=e,v[e]=this}var a=e.jQuery,u=e.console,h=u!==void 0,d=Object.prototype.toString;s.prototype=new t,s.prototype.options={},s.prototype.getImages=function(){this.images=[];for(var e=0,t=this.elements.length;t>e;e++){var n=this.elements[e];"IMG"===n.nodeName&&this.addImage(n);var i=n.nodeType;if(i&&(1===i||9===i||11===i))for(var r=n.querySelectorAll("img"),o=0,s=r.length;s>o;o++){var f=r[o];this.addImage(f)}}},s.prototype.addImage=function(e){var t=new f(e);this.images.push(t)},s.prototype.check=function(){function e(e,r){return t.options.debug&&h&&u.log("confirm",e,r),t.progress(e),n++,n===i&&t.complete(),!0}var t=this,n=0,i=this.images.length;if(this.hasAnyBroken=!1,!i)return this.complete(),void 0;for(var r=0;i>r;r++){var o=this.images[r];o.on("confirm",e),o.check()}},s.prototype.progress=function(e){this.hasAnyBroken=this.hasAnyBroken||!e.isLoaded;var t=this;setTimeout(function(){t.emit("progress",t,e),t.jqDeferred&&t.jqDeferred.notify&&t.jqDeferred.notify(t,e)})},s.prototype.complete=function(){var e=this.hasAnyBroken?"fail":"done";this.isComplete=!0;var t=this;setTimeout(function(){if(t.emit(e,t),t.emit("always",t),t.jqDeferred){var n=t.hasAnyBroken?"reject":"resolve";t.jqDeferred[n](t)}})},a&&(a.fn.imagesLoaded=function(e,t){var n=new s(this,e,t);return n.jqDeferred.promise(a(this))}),f.prototype=new t,f.prototype.check=function(){var e=v[this.img.src]||new c(this.img.src);if(e.isConfirmed)return this.confirm(e.isLoaded,"cached was confirmed"),void 0;if(this.img.complete&&void 0!==this.img.naturalWidth)return this.confirm(0!==this.img.naturalWidth,"naturalWidth"),void 0;var t=this;e.on("confirm",function(e,n){return t.confirm(e.isLoaded,n),!0}),e.check()},f.prototype.confirm=function(e,t){this.isLoaded=e,this.emit("confirm",this,t)};var v={};return c.prototype=new t,c.prototype.check=function(){if(!this.isChecked){var e=new Image;n.bind(e,"load",this),n.bind(e,"error",this),e.src=this.src,this.isChecked=!0}},c.prototype.handleEvent=function(e){var t="on"+e.type;this[t]&&this[t](e)},c.prototype.onload=function(e){this.confirm(!0,"onload"),this.unbindProxyEvents(e)},c.prototype.onerror=function(e){this.confirm(!1,"onerror"),this.unbindProxyEvents(e)},c.prototype.confirm=function(e,t){this.isConfirmed=!0,this.isLoaded=e,this.emit("confirm",this,t)},c.prototype.unbindProxyEvents=function(e){n.unbind(e.target,"load",this),n.unbind(e.target,"error",this)},s});
!function(t){var i=t(window);t.fn.visible=function(t,e,o){if(!(this.length<1)){var r=this.length>1?this.eq(0):this,n=r.get(0),f=i.width(),h=i.height(),o=o?o:"both",l=e===!0?n.offsetWidth*n.offsetHeight:!0;if("function"==typeof n.getBoundingClientRect){var g=n.getBoundingClientRect(),u=g.top>=0&&g.top<h,s=g.bottom>0&&g.bottom<=h,c=g.left>=0&&g.left<f,a=g.right>0&&g.right<=f,v=t?u||s:u&&s,b=t?c||a:c&&a;if("both"===o)return l&&v&&b;if("vertical"===o)return l&&v;if("horizontal"===o)return l&&b}else{var d=i.scrollTop(),p=d+h,w=i.scrollLeft(),m=w+f,y=r.offset(),z=y.top,B=z+r.height(),C=y.left,R=C+r.width(),j=t===!0?B:z,q=t===!0?z:B,H=t===!0?R:C,L=t===!0?C:R;if("both"===o)return!!l&&p>=q&&j>=d&&m>=L&&H>=w;if("vertical"===o)return!!l&&p>=q&&j>=d;if("horizontal"===o)return!!l&&m>=L&&H>=w}}}}(jQuery);

(function($){
    "use strict";

    var SPU_master = function() {

	var windowHeight 	= $(window).height();
	var isAdmin 		= spuvar.is_admin;
	var $boxes 			= [];

	//remove paddings and margins from first and last items inside box
	$(".spu-content").children().first().css({
		"margin-top": 0,
		"padding-top": 0
	}).end().last().css({
		'margin-bottom': 0,
		'padding-bottom': 0
	});

	// loop through boxes
	$(".spu-box").each(function() {

		// move to parent in safe mode
		if( spuvar.safe_mode ){

			$(this).prependTo('body');

		}

		// vars
		var $box 			= $(this);
		var triggerMethod 	= $box.data('trigger');
		var triggerClass 	= $box.data('trigger-value');
		var triggerSeconds 	= parseInt( $box.data('trigger-number'), 10 );
		var triggerPercentage = ( triggerMethod == 'percentage' ) ? ( parseInt( $box.data('trigger-number'), 10 ) / 100 ) : 0.8;
		var triggerHeight 	= ( triggerPercentage * $(document).height() );
		var timer 			= 0;
		$box.testMode 		= (parseInt($box.data('test-mode')) === 1);
		var id 				= $box.data('box-id');
		var autoHide 		= (parseInt($box.data('auto-hide')) === 1);
		var advancedClose   = (parseInt($box.data('advanced-close')) === 1);
		var removeClose   	= (parseInt($box.data('disable-close')) === 1);

		facebookFix( $box );

        // Custom links conversion
        $box.on('click', 'a:not(".spu-close-popup, .flp_wrapper a, .spu-not-close")', function(){
                // hide the popup and track conversion
            track( id, true);
            toggleBox( id, false, true);
        });

        // normalize class name
        if( triggerClass ){
            triggerClass.replace('.','');
            triggerClass = '.' + triggerClass;
        }

		if ( ! advancedClose ) {
			//close with esc
			$(document).keyup(function(e) {
				if (e.keyCode == 27) {
					toggleBox( id, false, false);
				}
			});
			//close on ipads // iphones
			var ua = navigator.userAgent,
			event = (ua.match(/iPad/i) || ua.match(/iPhone/i)) ? "touchstart" : "click";

			$('body').on(event, function (ev) {
				// test that event is user triggered and not programatically
				if( ev.originalEvent !== undefined && ! $(ev.target).is('.spu-clickable') ) {

					toggleBox( id, false, false);

				}
			});
			//not on the box
			$('body' ).on(event,'.spu-box, .spu-clickable', function(ev) {
				ev.stopPropagation();
			});
		}
		if ( removeClose ) {
			$box.find('.spu-close.spu-close-popup').remove();
		}


		//hide boxes and remove left-99999px we cannot since beggining of facebook won't display
		$box.hide().css('left','');

		// add box to global boxes array
		$boxes[id] = $box;

		// functions that check % of height
		var triggerHeightCheck = function()
		{
			if(timer) {
				clearTimeout(timer);
			}

			timer = window.setTimeout(function() {
				var scrollY = $(window).scrollTop();
				var triggered = ((scrollY + windowHeight) >= triggerHeight);

				// show box when criteria for this box is matched
				if( triggered ) {

					// remove listen event if box shouldn't be hidden again
					if( ! autoHide ) {
						$(window).unbind('scroll', triggerHeightCheck);
					}

					toggleBox( id, true, false);
				} else {
					toggleBox( id, false, false);
				}

			}, 100);
		}

        // function that show popup when the element with classname X become visible
        var triggerVisible = function()
        {

            if(timer) {
                clearTimeout(timer);
            }
            timer = window.setTimeout(function() {

                // show box when criteria for this box is matched
                if( $(triggerClass).visible(true) ) {

                    // remove listen event if box shouldn't be hidden again
                    if( ! autoHide ) {
                        $(window).unbind('scroll', triggerVisible);
                    }

                    toggleBox( id, true, false);
                } else {
                    toggleBox( id, false, false);
                }

            }, 100);
        }
		// function that show popup after X secs
		var triggerSecondsCheck = function()
		{
			if(timer) {
				clearTimeout(timer);
			}

			timer = window.setTimeout(function() {

				toggleBox( id, true, false);

			}, triggerSeconds * 1000);
		}
		// function that show popup when the user is about to leave page
		var triggerExitIntent = function(e)
		{

	            // Only trigger if leaving near the top of the page
	            if ( e.clientY >  20 ) {
	                return;
	            }

	            toggleBox( id, true, false);
	       		// we want to show it only once
	       		$(document).unbind('mouseleave', triggerExitIntent);
		}


		// show box if cookie not set or if in test mode
		var cookieValue = spuReadCookie( 'spu_box_' + id );

		if( ( cookieValue == undefined || cookieValue == '' ) || ( isAdmin && $box.testMode ) ) {

			if(triggerMethod == 'seconds') {

				triggerSecondsCheck();

			} else if(triggerMethod == 'exit-intent') {

				$(document).on('mouseleave', triggerExitIntent);

			} else if(triggerMethod == 'percentage') {
				$(window).bind( 'scroll', triggerHeightCheck );
				// init, check box criteria once
				triggerHeightCheck();
			} else if(triggerMethod == 'visible') {
				$(window).bind( 'scroll', triggerVisible );
				// init, check box criteria once
                triggerVisible();
			}

			// shows the box when hash refers to a box
			if(window.location.hash && window.location.hash.length > 0) {

				var hash = window.location.hash;
				var $element;

				if( hash.substring(1) === $box.attr( 'id' ) ) {
					setTimeout(function() {
						toggleBox( id, true, false );
					}, 100);
				}
			}
		}	/* end check cookie */
        // CLose button
		$box.on('click','.spu-close-popup',function() {

			// hide box
			toggleBox( id, false, false );

			if(triggerMethod == 'percentage') {
				// unbind
				$(window).unbind( 'scroll', triggerHeightCheck );
			}

		});

		// add link listener for this box
		$('a[href="#spu-' + id +'"], '+triggerClass).on('click',function(e) {
            e.preventDefault();
			toggleBox(id, true, false);
		}).css('cursor','pointer').addClass('spu-clickable');// in case of div, fix ios bug not registering clicks

		// add class to the gravity form if they exist within the box
		$box.find('.gform_wrapper form').addClass('gravity-form');
        // same for mc4wp
		$box.find('.mc4wp-form form').addClass('mc4wp-form');

        // check if form action is external and disable ajax
        var box_form = $box.find('form');
        if( box_form.length ) {
            // Only if form is not a known one disable ajax
            if( ! box_form.is(".wpcf7-form, .gravity-form, .infusion-form, .spu-optin-form, .widget_wysija, .ninja-forms-form, .mc4wp-form") ) {
                var action = box_form.attr('action'),
                    pattern = new RegExp(spuvar.site_url, "i");
                if (action && action.length) {
                    if (!pattern.test(action))
                        box_form.addClass('spu-disable-ajax');
                }
            }
            // if spu-disable-ajax is on container add it to form (usp forms for example)
            if( $('.spu-disable-ajax form').length ) {
                $('.spu-disable-ajax form').addClass('spu-disable-ajax');
            }
            // Disable ajax on form by adding .spu-disable-ajax class to it
            $box.on('submit','form.spu-disable-ajax:not(".flp_form")', function(){
                // track event
                track( id, true );
                $box.trigger('spu.form_submitted', [id]);
                toggleBox(id, false, true );
            });

            // Add generic form tracking
            $box.on('submit','form:not(".wpcf7-form, .gravity-form, .infusion-form, .spu-disable-ajax, .spu-optin-form, .widget_wysija, .ninja-forms-form, .mc4wp-form, .flp_form")', function(e){
                e.preventDefault();
                // track event
                track( id, true );

                var submit 	= true,
                    form 		= $(this),
                    data 	 	= form.serialize(),
                    url  	 	= form.attr('action'),
                    error_cb 	= function (data, error, errorThrown){
                        console.log('Spu Form error: ' + error + ' - ' + errorThrown);
                    },
                    success_cb 	= function (data){

                        var response = $(data).filter('#spu-'+ id ).html();
                        $('#spu-' + id ).html(response);

                        // check if an error was returned for m4wp
                        if( ! $('#spu-' + id ).find('.mc4wp-form-error').length ) {

                            // give 2 seconds for response
                            setTimeout( function(){

                                toggleBox(id, false, true );

                            }, spuvar.seconds_confirmation_close * 1000);

                        }
                    };
                // Send form by ajax and replace popup with response
                request(data, url, success_cb, error_cb, 'html');

                $box.trigger('spu.form_submitted', [id]);

                return submit;
            });

            // CF7 support
            $('body').on('mailsent.wpcf7', function(){
                track( id, true );
                $box.trigger('spu.form_submitted', [id]);
                toggleBox(id, false, true);
            });

            // Gravity forms support (only AJAX mode)
            $(document).on('gform_confirmation_loaded', function(){
                track( id, true );
                $box.trigger('spu.form_submitted', [id]);
                toggleBox(id, false, true );
            });

            // Infusion Software - not ajax
            $box.on('submit','.infusion-form', function(e){
                e.preventDefault();
                track( id, true );
                $box.trigger('spu.form_submitted', [id]);
                toggleBox(id, false, true );
                this.submit();
            });

            // SPU Optins
            $box.on('submit','.spu-optin-form', function(e){
                e.preventDefault();
                var error = false,
                    boton = $(this).find('.spu-submit'),
                    spin  = $(this).find('.spu-spinner');
                // If valid proccess
                if( ! (error = spu_optin_validate( $(this) ) ) ) {
                    track( id, true );
                    $box.trigger('spu.form_submitted', [id]);
                    spin.fadeIn();
                    boton.prop('disabled', true);
                    var form 		= $(this),
                        data 	 	= form.serializeArray(),
                        url  	 	= form.attr('action'),
                        error_cb 	= function (data, error, errorThrown){
                            console.log('Spu Form error: ' + error + ' - ' + errorThrown);
                            spin.fadeOut();
                            boton.prop('disabled', false);
                            $box.find('.spu-error').remove();
                            $box.find('.spu-fields-container').append('<div class="spu-error">'+error+'</div>');
                        },
                        success_cb 	= function (data){
                            spin.fadeOut();
                            boton.prop('disabled', false);
                            if( !data.error ) {
                                track( id, true );
                                $box.trigger('spu.optin_success', [id]);
                            } else {
                                if( data.error == 'honeypot')
                                    toggleBox(id, false, false );
                                $box.find('.spu-error').remove();
                                $box.find('.spu-fields-container').append('<div class="spu-error">'+data.error+'</div>');
                                $box.trigger('spu.optin_error', [id]);
                            }

                            if( data.redirect ) {
                                $box.trigger('spu.optin_on_redirect', [id]);
                                var i   = 0,
                                    sep = '&',
                                    redirect = data.redirect;
                                for ( var key in data.lead ) {
                                    if ( 0 == i ) {
                                        if ( data.redirect.indexOf('?') < 0 ) {
                                            sep = '?';
                                        }
                                    }
                                    redirect = redirect + sep + 'spu-' + encodeURIComponent(key) + '=' + encodeURIComponent(data.lead[key]);
                                    i++;
                                }
                                // Redirect the user.
                                window.location.href = redirect;
                            }
                            if( data.success_msg ) {
                                $box.trigger('spu.optin_on_succes_message', [id]);
                                var position  = $box.position(),
                                    width     = $box.outerWidth(),
                                    height    = $box.outerHeight(),
                                    zindex    =  999999999999,
                                    overlay   = '<div class="spu-success-overlay" style="display:none;"><a href="#" class="spu-close spu-close-popup spu-success-close"><i class="spu-icon spu-icon-close"></i></a></div>',
                                    message   = data.success_msg;

                                // Output the overlay.
                                $box.append(overlay);

                                // add the message
                                $box.find('.spu-success-overlay').append('<div class="spu-success-message">' + message + '</div>');

                                // Position the content inside of the div to be vertically centered.
                                $box.find('.spu-success-message').css({ 'margin-top': (height - $box.find('.spu-success-message').height()) / 2 });

                                // Display the overlay.
                                $box.find('.spu-success-overlay').fadeIn();

                                // Bind to window resize to ensure the success message is always the proper dimensions.
                                $(window).resize(function(){
                                    $('.spu-success-message').css({ 'margin-top': ($box.outerHeight() - $('.spu-success-message').height()) / 2 });
                                });
                            }
                            if ( data.success ) {
                                toggleBox(id, false, true );
                            }
                        };
                    // add extra data
                    data.push({ name : "box_id" , value : id});
                    data.push({ name : "action", value : "spu_optin" });

                    // Send form by ajax and replace popup with response
                    request(data, url, success_cb, error_cb, 'json');
                } else {
                    $box.find('.spu-error').remove();
                    $box.find('.spu-fields-container').append('<div class="spu-error">'+error+'</div>');
                }
            });
        }


	});

	// tracking
	var track = function(id, conversion){
        if( spuvar.disable_stats )
            return;
        var $box = $boxes[id],
            ga         = $box.data('ua-code'),
            conversion = conversion || false; //if not conversion we are tracking impression

        // If we have already tracked this popup, are in preview mode or test mode, don't do anything.
        if (  isAdmin && $box.testMode ) {
            return;
        }
        var sampling_active = spuvar.dsampling || false ;
        var sampling_rate   = spuvar.dsamplingrate;
        var do_request = false;

        if ( !sampling_active || conversion ) {
            do_request = true;
        } else {
            var num = Math.floor(Math.random() * sampling_rate) + 1;
            do_request = ( 1 === num );
        }

        if ( do_request ) {

            // Make the ajax request to track the data. If using Google Analytics, send to GA, otherwise track locally.
            if (ga) {
                trackGoogleAnalytics(id, ga, conversion);
            }
            // Our track
            data = {
                action: 'track_spup',
                box_id: id,
                post_id: spuvar.pid,
                referrer: window.location.href,
                user_agent: navigator.userAgent,
                previous: document.referer,
                conversion: conversion
            };
            request(data);
        }
    }

    // Method for tracking impression data via Google Analytics.
    var trackGoogleAnalytics = function(id, ga_id, track){
        // Prepare variables.
        var type     = track ? 'conversion' : 'impression';

        // Create a custom event tracker and dimensions if it has not been initialized.
        if ( ! SPU.ga_init ) {
        	if (typeof ga == 'function') {
        		ga('create', ga_id, 'auto', { 'name' : 'SpuTracker' });
        	}
            //yoast analytics
           	if (typeof __gaTracker == 'function') {
        		__gaTracker('create', ga_id, 'auto', { 'name' : 'SpuTracker' });
        	}

            SPU.ga_init = true;
        }
        if (typeof ga == 'function') {
        	// Send the event tracking data to Google Analytics.
        	ga('SpuTracker.send', 'event', 'wp-popup', type, 'spu-'+ id );
        }
        if (typeof __gaTracker == 'function') {
        	// Send the event tracking data to Google Analytics.
        	__gaTracker('SpuTracker.send', 'event', 'wp-popup', type, 'spu-'+ id );
        }

    }


	//function that center popup on screen
	function fixSize( id ) {
		var $box 			= $boxes[id];
		var windowWidth 	= $(window).width();
		var windowHeight 	= $(window).height();
		var popupHeight 	= $box.outerHeight();
		var popupWidth 		= $box.outerWidth();
		var intentWidth		= $box.data('width');
		var left 			= 0;
		var top 			= windowHeight / 2 - popupHeight / 2;
		var position 		= 'fixed';
		var currentScroll   = $(document).scrollTop();

		if( $box.hasClass('spu-centered') ){
			if( intentWidth < windowWidth ) {
				left = windowWidth / 2 - popupWidth / 2;
			}
			$box.css({
				"left": 	left,
				"position": position,
				"top": 		top,
			});
		}

		// if popup is higher than viewport we need to make it absolute
		if( (popupHeight + 50) > windowHeight ) {
			position 	= 'absolute';
			top 		= currentScroll;

			$box.css({
				"position": position,
				"top": 		top,
				"bottom": 	"auto",
			});
		}
	}

	//facebookBugFix
	function facebookFix( box ) {

		// Facebook bug that fails to resize
		var $fbbox = $(box).find('.spu-facebook');
		if( $fbbox.length ){
			//if exist and width is 0
			var $fbwidth = $fbbox.find('.fb-like > span').width();
			if ( $fbwidth == 0 ) {
				var $fblayout = $fbbox.find('.fb-like').data('layout');
				 if( $fblayout == 'box_count' ) {

				 	$fbbox.append('<style type="text/css"> #'+$(box).attr('id')+' .fb-like iframe, #'+$(box).attr('id')+' .fb_iframe_widget span, #'+$(box).attr('id')+' .fb_iframe_widget{ height: 63px !important;width: 80px !important;}</style>');

				 } else {

					$fbbox.append('<style type="text/css"> #'+$(box).attr('id')+' .fb-like iframe, #'+$(box).attr('id')+' .fb_iframe_widget span, #'+$(box).attr('id')+' .fb_iframe_widget{ height: 20px !important;width: 80px !important;}</style>');

				 }
			}
		}
	}
        /**
         * Check all shortcodes and automatically center them
         * @param box
         */
        function centerShortcodes( box ){
            var $box 	= box;
            var total = $box.data('total'); //total of shortcodes used
            if( total ) { //if we have shortcodes
                SPU_reload_socials();

                //wrap them all
                //center spu-shortcodes
                var swidth = 0;
                var free_width = 0;
                var boxwidth = $box.outerWidth();
                var cwidth = $box.find(".spu-content").width();
                if (!spuvar.disable_style && $(window).width() > boxwidth) {
                    $box.find(".spu-shortcode").wrapAll('<div class="spu_shortcodes"/>');

                    //calculate total width of shortcodes all togheter
                    $box.find(".spu-shortcode").each(function () {
                        swidth = swidth + $(this).outerWidth();
                    });
                    //available space to split margins
                    free_width = cwidth - swidth - (total*20);

                }
                if (free_width > 0) {
                    //leave some margin
                    $box.find(".spu-shortcode").each(function () {

                        $(this).css('margin-left', (free_width / 2 ));

                    });
                    //remove margin when neccesary
                    if (total == 2) {

                        $box.find(".spu-shortcode").last().css('margin-left', 0);

                    } else if (total == 3) {

                        $box.find(".spu-shortcode").first().css('margin-left', 0);

                    }
                }
            }
        }
    /**
     * Main function to show or hide the popup
     * @param id int box id
     * @param show boolean it's hiding or showing?
     * @param conversion boolean - Its a conversion or we are just closing
     * @returns {*}
     */
	function toggleBox( id, show, conversion ) {
		var $box 	= $boxes[id];
		var $bg	 	= $('#spu-bg-'+id);
		var $bgopa 	= $box.data('bgopa');
		var secondsClose    = parseInt($box.data('auto-close'));
        var conversion = conversion || false;

		// don't do anything if box is undergoing an animation
		if( $box.is( ":animated" ) ) {
			return false;
		}

		// is box already at desired visibility?
		if( ( show === true && $box.is( ":visible" ) ) || ( show === false && $box.is( ":hidden" ) ) ) {
			return false;
		}

		//if we are closing , set cookie //add an option to add cokies when close is pressed
		if( show === false) {
			// set cookie
			var days = parseInt( $box.data('cookie') );
			if( days > 0 ) {
				spuCreateCookie( 'spu_box_' + id, true, days );
			}
            $box.trigger('spu.box_close', [id]);
		} else {
            setTimeout(function(){
                centerShortcodes($box);
            },1500);
            $box.trigger('spu.box_open', [id]);
			//track
			track(id, false);

			//bind for resize
			$(window).resize(function(){

				fixSize( id );

			});
			fixSize( id );

		}
		// show box
		var animation = $box.data('spuanimation'),
            conversion_close = $box.data('close-on-conversion');

        if (animation === 'fade') {
            if (show === true) {
                $box.fadeIn('slow');
            } else if (show === false && ( (conversion_close && conversion ) || !conversion )  ) {
                $box.fadeOut('slow');
            }
        } else if ( animation === 'slider' ) {
            if (show === true ) {
                $box.slideDown('slow');
            } else if (show === false && ( (conversion_close && conversion ) || !conversion ) ) {
                $box.slideUp('slow');
            }
        } else {
            if (show === true ) {
                $box.toggle().toggleClass('spu-animate');
            } else if (show === false && ( (conversion_close && conversion ) || !conversion ) ) {
                $box.toggle().toggleClass('spu-animate');
            }
        }


		//background
		if( show === true && $bgopa > 0 ){
			$bg.fadeIn();
        } else if (show === false && ( (conversion_close && conversion ) || !conversion ) ) {
			$bg.fadeOut();
		}
		//timer function
		function spuTimer( id ) {
			  secondsClose = secondsClose - 1;
			  if (secondsClose <= 0)
			  {
			     clearInterval($box.counter);
			     $box.counter = 'undefined';
			     secondsClose = parseInt($boxes[id].data('auto-close'));
			     toggleBox(id, false, false);
			     return;
			  }

			 $("#spu-" + id ).find('.spu-timer').html( spuvar.l18n.wait + " " + secondsClose + " " + spuvar.l18n.seconds );
		}
		// Seconds left to close ( autoclose timer )
		if( show === true && secondsClose > 0 && typeof($box.counter) === 'undefined' )
		{
		 	$box.counter = setInterval(function(){ spuTimer( id )}, 1000);
		}
		return show;
	}


	return {
		show: function( box_id ) {
			return toggleBox( box_id, true, false );
		},
		hide: function( box_id ) {
			return toggleBox( box_id, false, false );
		},
		track: function( box_id, conversion ) {
			return track( box_id, conversion );
		},
		request: function( data, url, success_cb, error_cb ) {
			return request( data, url, success_cb, error_cb );
		}
	}

}
	/**
	 * Everything start here
	 */
if( spuvar.ajax_mode ) {

    var data = {
        pid : spuvar.pid,
        referrer : document.referrer,
        is_category : spuvar.is_category,
        is_archive : spuvar.is_archive
    },
    success_cb = function(response) {
    	
    	$('body').append(response);
        $(".spu-box").imagesLoaded( function() {
            window.SPU = SPU_master();
            SPU_reload_forms(); //remove spu_Action from forms
        });

    },
    error_cb 	= function (data, error, errorThrown){
        console.log('Problem loading popups - error: ' + error + ' - ' + errorThrown);
    }
    request(data, spuvar.ajax_mode_url , success_cb, error_cb, 'html');
} else {
    $(".spu-box").imagesLoaded( function(){
        window.SPU = SPU_master();
    });
}

 /**
 * Ajax requests
 * @param data
 * @param url
 * @param success_cb
 * @param error_cb
 * @param dataType
 */
function request(data, url, success_cb, error_cb, dataType){
    // Prepare variables.
    var ajax       = {
            url:      spuvar.ajax_url,
            data:     data,
            cache:    false,
            type:     'POST',
            dataType: 'json',
            timeout:  30000
        },
        dataType   = dataType || false,
        success_cb = success_cb || false,
        error_cb   = error_cb   || false;

    // Set ajax url is supplied
    if ( url ) {
        ajax.url = url;
    }
    // Set success callback if supplied.
    if ( success_cb ) {
        ajax.success = success_cb;
    }

    // Set error callback if supplied.
    if ( error_cb ) {
        ajax.error = error_cb;
    }

    // Change dataType if supplied.
    if ( dataType ) {
        ajax.dataType = dataType;
    }
    // Make the ajax request.
    $.ajax(ajax);
    
}


/**
 * Cookie functions
 */
function spuCreateCookie(name, value, days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
		var expires = "; expires=" + date.toGMTString();
	} else var expires = "";
	document.cookie = name + "=" + value + expires + "; path=/";
}

function spuReadCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for (var i = 0; i < ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0) == ' ') c = c.substring(1, c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
	}
	return null;
}

/** 
 * Social Callbacks
 */

jQuery(function($) {
   var SPUfb = false;

   var FbTimer = setInterval(function(){
        if( typeof FB !== 'undefined' && ! SPUfb) {

                subscribeFbEvent();

        }
    },1000);

	if ( typeof twttr !== 'undefined') {
        try{
            twttr.ready(function(twttr) {
                twttr.events.bind('tweet', twitterCB);
                twttr.events.bind('follow', twitterCB);
            });
        }catch(ex){}
	}


    function subscribeFbEvent(){
        try{
        FB.Event.subscribe('edge.create', function(href, html_element) {
            var box_id = $(html_element).parents('.spu-box').data('box-id');
            if( box_id) {
                SPU.hide(box_id);
                //Track the conversion.
                SPU.track( box_id, true);
            }
        });
        }catch(ex){}
        SPUfb = true;
        clearInterval(FbTimer);
    }
	function twitterCB(intent_event) {

		var box_id = $(intent_event.target).parents('.spu-box').data('box-id');
		
		if( box_id) {
			SPU.hide(box_id);
			//Track the conversion.
            SPU.track( box_id, true);			
		}
	}
});
function googleCB(a) {

	if( "on" == a.state ) {

		var box_id = jQuery('.spu-gogl').data('box-id');
		if( box_id) {
			SPU.hide(box_id);			
			//Track the conversion.
            SPU.track( box_id, true);					
		}
	}
}
function closeGoogle(a){
	if( "confirm" == a.type )
	{
		var box_id = jQuery('.spu-gogl').data('box-id');
		if( box_id) {
			SPU.hide(box_id);
	
		}
	}
}	
function SPU_reload_socials(){
	if( spuvar_social.facebook ) {

	    // reload fb
    	try{
        	FB.XFBML.parse();
    	}catch(ex){}
	}
    if( spuvar_social.google ){

    	// reload google
        try{
            gapi.plusone.go();
        }catch(ex){}

    }
    if( spuvar_social.twitter ){

    	//reload twitter
        try{
            twttr.widgets.load();
        }catch(ex){}
    }
}

function SPU_reload_forms(){
	// Clear actions
	$('.spu-box form').each( function(){
		var action = $(this).attr('action');
        if( action ){
            $(this).attr('action' , action.replace('?spu_action=spu_load',''));
        }
	});
	if ($.fn.wpcf7InitForm) {
    	$('.spu-box div.wpcf7 > form').wpcf7InitForm();
	}
}

/**
 * Validates optin form
  * @param form
 */
var spu_optin_validate = function( form ) {
    var name = form.find('.spu-name'),
        email = form.find('.spu-email'),
        response = false;

    if( name.length ) {
        if ( name.val().length == 0 ) {
            response = spuvar.l18n.name_error;
        }
    }

    if ( email.val().length == 0 || ! spu_valid_email(email.val()) ) {
        response = spuvar.l18n.email_error;
    }

    return response;
}

/**
 * Validate email
 * @param email
 * @returns {boolean}
 */
var spu_valid_email = function(email){
    return (new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i)).test(email);
}

})(jQuery);