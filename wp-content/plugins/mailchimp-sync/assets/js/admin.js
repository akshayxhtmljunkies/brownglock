(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
var Admin = (function() {
	'use strict';

	var Wizard = require('./Wizard.js');
	var FieldMapper = require('./FieldMapper.js');
	var $ = window.jQuery;

	// init wizard
	var wizardContainer = document.getElementById('wizard');
	if( wizardContainer ) {
		m.module( wizardContainer , Wizard );
	}

	// init fieldmapper
	new FieldMapper($('.mc4wp-sync-field-map'));
})();

module.exports = Admin;
},{"./FieldMapper.js":2,"./Wizard.js":5}],2:[function(require,module,exports){
var FieldMapper = function( $context ) {

	var $ = window.jQuery;

	function addRow() {
		var $row = $context.find(".row").last();
		var $newRow = $row.clone();
		var $userField = $newRow.find('.user-field');
		var $mailChimpField = $newRow.find('.mailchimp-field');

		$userField.val('').suggest( ajaxurl + "?action=mcs_autocomplete_user_field").attr('name', $userField.attr('name').replace(/\[(\d+)\]/, function (str, p1) {
			return '[' + (parseInt(p1, 10) + 1) + ']';
		}));

		// empty select boxes and set new `name` attribute
		$mailChimpField.val('').attr('name', $mailChimpField.attr('name').replace(/\[(\d+)\]/, function (str, p1) {
			return '[' + (parseInt(p1, 10) + 1) + ']';
		}));

		$newRow.insertAfter($row);

		setAvailableFields();
		return false;
	}

	function removeRow() {
		$(this).parents('.row').remove();
		setAvailableFields();
	}

	function setAvailableFields() {
		var selectBoxes = $context.find('.mailchimp-field');
		selectBoxes.each(function() {
			var otherSelectBoxes = selectBoxes.not(this);
			var chosenFields = $.map( otherSelectBoxes, function(a,i) { return $(a).val(); });

			$(this).find('option').each(function() {
				$(this).prop('disabled', ( $.inArray($(this).val(), chosenFields) > -1 ));
			});
		});
	}

	$context.find('.user-field').suggest( ajaxurl + "?action=mcs_autocomplete_user_field" );
	$context.find('.mailchimp-field').change(setAvailableFields).trigger('change');
	$context.find('.add-row').click(addRow);
	$context.on('click', '.remove-row', removeRow);
};

module.exports = FieldMapper;
},{}],3:[function(require,module,exports){
/**
 * Log model
 */
var Log = function() {
	var self = this;
	this.items = m.prop([]);

	// add line to items array
	this.addLine = function( text ) {

		var line = {
			time: new Date(),
			text: text
		};

		self.items().push( line );
		m.redraw();
	};

	// add some text to last log item
	this.addTextToLastLine = function( text ) {
		var line = self.items().pop();
		line.text += " " + text;
		self.items().push( line );
		m.redraw();
	};

	/**
	 * Scroll to bottom of log
	 *
	 * @param element
	 * @param initialized
	 * @param context
	 */
	this.scrollToBottom = function( element, initialized, context ) {
		element.scrollTop = element.scrollHeight;
	};

	// render all lines
	this.render = function() {
		return m("div.log", { config: self.scrollToBottom }, [
			self.items().map( function( item ) {

				var timeString =
					("0" + item.time.getHours()).slice(-2)   + ":" +
					("0" + item.time.getMinutes()).slice(-2) + ":" +
					("0" + item.time.getSeconds()).slice(-2);

				return m("p", [
					m('span.time', timeString),
					m.trust(item.text )
				] )
			})
		]);
	};
};

module.exports = Log;
},{}],4:[function(require,module,exports){
/**
 * User Model
 *
 * @param data
 * @constructor
 */
var User = function( data ) {
	this.id = m.prop( data.ID );
	this.username = m.prop( data.user_login );
	this.email = m.prop( data.user_email );
};

module.exports = User;
},{}],5:[function(require,module,exports){

var Log = require('./Log.js');
var User = require('./User.js');

var Wizard = (function() {

	var started = false, running = false, done = false;
	var userCount = 0;
	var usersProcessed = 0;
	var progress = m.prop( 0 );
	var log = new Log();
	var batch = m.prop([]);
	var roleSelect = document.getElementById('role-select');

	/**
	 * Initialise
	 */
	function init() {}

	function askToStart() {
		var sure = confirm( "Are you sure you want to start synchronising all of your users? This can take a while if you have many users, please don't close your browser window." );
		if( sure ) {
			start();
		}
	}

	function start() {
		started = true;
		running = true;

		fetchTotalUserCount()
			.then(prepareBatch)
			.then(subscribeFromBatch);

	}

	function resume() {
		running = true;
		subscribeFromBatch();
		m.redraw();
	}

	function pause() {
		running = false;
		m.redraw();
	}

	function fetchTotalUserCount() {

		var deferred = m.deferred();

		var data = { action : 'mcs_wizard', mcs_action: 'get_user_count', role: roleSelect.value };
		m.request({ method: "GET", url: ajaxurl, data: data }).then(function(data) {
			log.addLine("Found " + data + " users.");
			userCount = data;
			deferred.resolve();
		});

		return deferred.promise;
	}

	function prepareBatch() {

		var deferred = m.deferred();

		m.request( {
			method: "GET",
			url: ajaxurl,
			data: {
				action: 'mcs_wizard',
				mcs_action: 'get_users',
				offset: usersProcessed,
				role: roleSelect.value
			},
			type: User
		}).then( function( users ) {
			log.addLine("Fetched " + users.length + " users.");
			batch( users );
			deferred.resolve();
			m.redraw();
		}, function( error ) {
			log.addLine( "Error fetching users. Error: " + error );
		});

		return deferred.promise;
	}

	function subscribeFromBatch() {

		if( ! running || done ) {
			return false;
		}

		// bail if no users left
		if( batch().length === 0 ) {

			if( usersProcessed >= userCount ) {
				return false;
			} else {
				prepareBatch().then(subscribeFromBatch);
			}

			return false;
		}

		// Get first user
		var user = batch().shift();

		// Add line to log
		log.addLine("Synchronising <strong>user #" + user.id() + " " + user.username() + "</strong> (Email: <strong>" + user.email() + "</strong>)." );

		// Perform subscribe request
		var data = {
			action: "mcs_wizard",
			mcs_action: "subscribe_users",
			user_ids: [ user.id() ]
		};

		m.request({
			method: "GET",
			data: data,
			url: ajaxurl
		}).then(function( data ) {


			if( data.success ) {
				log.addTextToLastLine( "Success!" );

			}

			if( data.error ) {
				log.addTextToLastLine( "Error: " + data.error );
			}

			usersProcessed++;
			updateProgress();

		}, function( error ) {
			log.addLine( "Error: " + error );
		}).then(subscribeFromBatch, subscribeFromBatch);
	}

	function updateProgress() {
		// update progress
		var newProgress = Math.round( usersProcessed / userCount * 100 );

		progress( newProgress );

		if( newProgress >= 100 ) {
			done = true;
			log.addLine("Done!");
		}
	}

	/**
	 * View
	 *
	 * @returns {*}
	 */
	function view() {

		// Wizard isn't running, show button to start it
		if( ! started ) {
			return m('p', [
				m('input', { type: 'button', class: 'button', value: 'Synchronise All', onclick: askToStart } )
			]);
		} else

		// Show progress
		return [
			(
				( done ) ? '' : m("p",[
					m("input", { type: 'button', class: 'button', value: ( running ) ? "Pause" : "Resume", onclick: ( running ) ? pause : resume })
				]) ),
			m('div.progress-bar', [
				m( "div.value", { style: "width: "+ progress() +"%" } ),
				m( "div.text", {}, ( progress() == 100 ) ? "Done!" : "Working: " + progress() + "%" )
			]),

			log.render()
		];
	}

	init();

	return {
		'controller': init,
		'view': view
	}
})();



module.exports = Wizard;
},{"./Log.js":3,"./User.js":4}]},{},[1]);
