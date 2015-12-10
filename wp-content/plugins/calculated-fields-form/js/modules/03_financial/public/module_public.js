fbuilderjQuery = ( typeof fbuilderjQuery != 'undefined' ) ? fbuilderjQuery : jQuery;
fbuilderjQuery[ 'fbuilder' ] = fbuilderjQuery[ 'fbuilder' ] || {};
fbuilderjQuery[ 'fbuilder' ][ 'modules' ] = fbuilderjQuery[ 'fbuilder' ][ 'modules' ] || {};

fbuilderjQuery[ 'fbuilder' ][ 'modules' ][ 'financial' ] = {
	'prefix' : '',
	'callback' : function()
		{
			fbuilderjQuery[ 'fbuilder' ][ 'extend_window' ]( fbuilderjQuery[ 'fbuilder' ][ 'modules' ][ 'financial' ][ 'prefix' ], CF_FINANCE );
		},
    'validator' : function( v )
		{
			return ( typeof v == 'number' ) ? isFinite( v ) : ( typeof v != 'undefined' );
		}    
};