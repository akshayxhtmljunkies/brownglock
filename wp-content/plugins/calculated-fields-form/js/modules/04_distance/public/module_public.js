fbuilderjQuery = ( typeof fbuilderjQuery != 'undefined' ) ? fbuilderjQuery : jQuery;
fbuilderjQuery[ 'fbuilder' ] = fbuilderjQuery[ 'fbuilder' ] || {};
fbuilderjQuery[ 'fbuilder' ][ 'modules' ] = fbuilderjQuery[ 'fbuilder' ][ 'modules' ] || {};

fbuilderjQuery[ 'fbuilder' ][ 'modules' ][ 'distance' ] = {
	'prefix' : '',
	'callback' : function()
		{
			fbuilderjQuery[ 'fbuilder' ][ 'extend_window' ]( fbuilderjQuery[ 'fbuilder' ][ 'modules' ][ 'distance' ][ 'prefix' ], CF_DISTANCE );
		}
};