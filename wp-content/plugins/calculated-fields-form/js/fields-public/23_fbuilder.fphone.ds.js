	$.fbuilder.controls[ 'fPhoneds' ]=function(){};
	$.extend( 
		$.fbuilder.controls[ 'fPhoneds' ].prototype,
		$.fbuilder.controls[ 'fPhone' ].prototype,
		$.fbuilder.controls[ 'datasource' ].prototype,
		{
			ftype:"fPhoneds",
			show:function()
				{
					return $.fbuilder.controls[ 'fPhone' ].prototype.show.call( this );
				},
			after_show : function()
				{
					var me = this;
                    $.fbuilder.controls[ 'fPhone' ].prototype.after_show.call( me );
					$.fbuilder.controls[ 'datasource' ].prototype.getData.call( me, function( data )
						{ 
							var p = $.trim( me.dformat.replace(/[^\s#]/g, '' ).replace( /\s+/g, ' ' ) ).split( ' ' ), 
								h = p.length, e = [];
							
							if( typeof data.error != 'undefined' )
							{
								alert( data.error );
							}
							else
							{
								if( data.data.length )
								{
									var v = data.data[ 0 ].value.replace( /\s+/, '' ), 
										r = '', vArr;
									
									for( var i = 0; i<h; i++ ){ r += '(.{' + p[ i ].length + '})'; }
									vArr = ( new RegExp( r ) ).exec( v );
									if( vArr ){ e = vArr.slice(1); }
								}
							}
							
							for( var i = 0; i < h; i++ )
							{
								$( '#' + me.name + '_' + i ).val( ( typeof e[ i ]  != 'undefined' ) ? e[ i ] : '' ).change();
							}
							
						}
					);
				}
		}
	);