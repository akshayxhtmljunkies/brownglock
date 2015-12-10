	$.fbuilder.controls[ 'fhiddends' ]=function(){};
	$.extend(
		$.fbuilder.controls[ 'fhiddends' ].prototype,
		$.fbuilder.controls[ 'fhidden' ].prototype,
		$.fbuilder.controls[ 'datasource' ].prototype,
		{
			ftype:"fhiddends",
			show:function()
				{
					return $.fbuilder.controls[ 'fhidden' ].prototype.show.call( this );
				},
			after_show : function()
				{
					var me = this;
					$.fbuilder.controls[ 'datasource' ].prototype.getData.call( this, function( data )
						{ 
							var v = '';
							if( typeof data.error != 'undefined' )
							{
								alert( data.error );
							}
							else
							{
								if( data.data.length )
								{
									v = data.data[ 0 ][ 'value' ];
								}
							}	
							$( '#' + me.name ).val( v ).change();
						}
					);
				}	
		}	
	);