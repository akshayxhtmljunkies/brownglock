/*
* distance.js v0.1
* By: CALCULATED FIELD PROGRAMMERS
* The script allows operations with distance
* Copyright 2015 CODEPEOPLE
* You may use this project under MIT or GPL licenses.
*/

;(function(root){
	var lib = {},
		loadingFlag = false,
		distanceArr = [],
		callbacks = []; 
	

	/*** PRIVATE FUNCTIONS ***/
	function _runCallbacks()
	{
		var h = callbacks.length;
		if( h )
		{
			for( var i = 0; i < h; i++ )
			{
				callbacks[i]();
			}	
		}	
		callbacks = [];
	};
	
	function _createScriptTags()
	{
		// If Google Maps has not been loaded, and has not been created the script tags for loading the API
		if( !loadingFlag )
		{	
			loadingFlag = true;
			var script=document.createElement('script');
			script.type  = "text/javascript";
			script.src=(( typeof window.location.protocol != 'undefined' ) ? window.location.protocol : 'http:' )+'//maps.google.com/maps/api/js?sensor=false&callback=CPCFF_DISTANCE_MODULE_RUNCALLBACKS';
			document.body.appendChild(script);
		}					
	};
	
	/*** PUBLIC FUNCTIONS ***/
	
	lib.cf_distance_version = '0.1';
	
	/*
	* DISTANCE( form_id, address_a_string, address_b_string, unit_system, travel_mode) 	
	*
	* unit_system: 
	* km  - Kilometters
	* mi  - Miles
	*
	* travel_mode:
	* DRIVING - Indicates standard driving directions using the road network
	* BICYCLING - Requests bicycling directions via bicycle paths & preferred streets
	* TRANSIT - Requests directions via public transit routes
	* WALKING - Requests walking directions via pedestrian paths & sidewalks
	*
	* form_id is passed from the _calculate function in the fbuilder.fcalculated.js file, and should not be passed from the equation's edition
	*
	* the function returns the distance between address_a and address_b, in the unit_system
	*/
	lib.DISTANCE = function( address_a, address_b, unit_system, travel_mode, form_id ){
		
		var us, tm, r = 0;
		
		if( typeof address_a != 'undefined' && typeof address_b != 'undefined' )
		{
			address_a = (new String(address_a)).replace( /^\s+/, '' ).replace( /\s+$/, '' );
			address_b = (new String(address_b)).replace( /^\s+/, '' ).replace( /\s+$/, '' );
			if( address_a.length > 2 && address_b.length > 2 )
			{
				unit_system = ( typeof unit_system != 'undefined' ) ? unit_system.toLowerCase() : 'km';
				travel_mode = ( typeof travel_mode != 'undefined' ) ? travel_mode.toUpperCase() : 'driving'; 
				form_id	    = ( typeof form_id != 'undefined' ) ? form_id : ( ( typeof fbuilderjQuery.fbuilder.calculator.form_id != 'undefined' ) ? fbuilderjQuery.fbuilder.calculator.form_id : '' );

				// The pair of address was processed previously
				for( var i in distanceArr )
				{
					if( distanceArr[ i ][ 'a' ] == address_a && distanceArr[ i ][ 'b' ] ) return distanceArr[ i ][ 'distance' ];
				}
				
				// Google Maps has not been included previously
				if( typeof google == 'undefined' || google['maps'] == null )
				{	
					// List of functions to be called after complete the Google Maps loading
					callbacks.push( 
						( 
							function( address_a, address_b, unit_system, travel_mode, form_id )
							{ 
								return function(){ DISTANCE( address_a, address_b, unit_system, travel_mode, form_id ) };
							}	
						)( address_a, address_b, unit_system, travel_mode, form_id ) 
					);
					_createScriptTags();
					return;	
				}	
				
				us = ( unit_system == 'mi' ) ? google.maps.UnitSystem.IMPERIAL : google.maps.UnitSystem.METRIC;
				switch( travel_mode.toUpperCase() )
				{
					case 'BICYCLING': tm = google.maps.TravelMode.BICYCLING; break;
					case 'TRANSIT'  : tm = google.maps.TravelMode.TRANSIT; break;
					case 'WALKING'  : tm = google.maps.TravelMode.WALKING; break;
					default  		: tm = google.maps.TravelMode.DRIVING; break;
				}

				var directionsService = new google.maps.DirectionsService(),
					request = {
						origin		: address_a,
						destination	: address_b,
						travelMode	: tm,
						unitSystem	: us
					};

				directionsService.route(
					request, 
					( 
						function( form_id )
						{
							return function (response, status) 
									{
										if (status == google.maps.DirectionsStatus.OK) 
										{
											r = response.routes[0].legs[0].distance.text;
											distanceArr.push( { 'a' : response[ 'request' ][ 'origin' ], 'b': response[ 'request' ][ 'destination' ], 'distance': r } );
											fbuilderjQuery.fbuilder.calculator.defaultCalc( '#'+form_id, false );
										}
									};
						} 
					)( form_id )
				);
			}	
		}	
		return r;
	};
	
	lib.CPCFF_DISTANCE_MODULE_RUNCALLBACKS = function(){ _runCallbacks(); };
	
	root.CF_DISTANCE = lib;
	
})(this);