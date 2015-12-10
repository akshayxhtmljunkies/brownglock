fbuilderjQuery = (typeof fbuilderjQuery != 'undefined' ) ? fbuilderjQuery : jQuery;
fbuilderjQuery[ 'fbuilder' ] = fbuilderjQuery[ 'fbuilder' ] || {};
fbuilderjQuery[ 'fbuilder' ][ 'modules' ] = fbuilderjQuery[ 'fbuilder' ][ 'modules' ] || {};

fbuilderjQuery[ 'fbuilder' ][ 'modules' ][ 'distance' ] = {
	'tutorial' : 'http://wordpress.dwbooster.com/includes/calculated-field/distance.module.html',
	'toolbars'		: {
		'distance' : {
			'label' : 'Distance functions',
			'buttons' : [
							{ 
								"value" : "DISTANCE", 
								"code" : "DISTANCE(", 
								"tip" : "<p>Get the distance between two address. <strong>DISTANCE( Address A, Address B, Unit System, Travel Mode )</strong></p><p>The allowed values for Unit System are: km for kilometters, or mi for miles, km is the value by default.</p><p>The allowed values for Travel Mode are: DRIVING, BICYCLING, TRANSIT, or WALKING, DRIVING is the value by default.</p>" 
							}
						]
		}
	}
};