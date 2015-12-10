
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