	$.fbuilder.controls[ 'fdropdownds' ] = function(){};
	$.extend(
		$.fbuilder.controls[ 'fdropdownds' ].prototype,
		$.fbuilder.controls[ 'fdropdown' ].prototype,
		$.fbuilder.controls[ 'datasource' ].prototype,
		{
			ftype:"fdropdownds",
			show:function()
				{
					this.choices = [];
					return $.fbuilder.controls[ 'fdropdown' ].prototype.show.call( this );
				},
			after_show : function(){
				var me = this;
				$.fbuilder.controls[ 'datasource' ].prototype.getData.call( this, function( data )
					{ 
						var str = '';
						if( typeof data.error != 'undefined' )
						{
							alert( data.error );
						}
						else
						{
							var t, v;
							for( var i = 0, h = data.data.length; i < h; i++ )
							{
                                v = ( ( typeof data.data[ i ][ 'value' ] != 'undefined' ) ? data.data[ i ][ 'value' ] : '' );
                                t = ( ( typeof data.data[ i ][ 'text' ] != 'undefined' )  ? data.data[ i ][ 'text' ]  :  v );
                                
								str += '<option value="' + $.fbuilder.htmlEncode( v ) + '" vt="' + $.fbuilder.htmlEncode( t ) +'">' + t + '</option>';
							}
						}	
						$( '#' + me.name ).html( str ).change();
					}
				);
			}	
			
	});