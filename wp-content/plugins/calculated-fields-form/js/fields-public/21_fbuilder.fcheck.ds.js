	$.fbuilder.controls[ 'fcheckds' ] = function(){};
	$.extend(
		$.fbuilder.controls[ 'fcheckds' ].prototype,
		$.fbuilder.controls[ 'fcheck' ].prototype,
		$.fbuilder.controls[ 'datasource' ].prototype,
		{
			ftype:"fcheckds",
			show:function()
				{
					return '<div class="fields '+this.csslayout+'" id="field'+this.form_identifier+'-'+this.index+'"><label>'+this.title+''+((this.required)?"<span class='r'>*</span>":"")+'</label><div class="dfield"><span class="uh">'+this.userhelp+'</span></div><div class="clearer"></div></div>';
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
							for( var i = 0, h = data.data.length; i < h; i++ )
							{
								var e = data.data[ i ];
								str += '<div class="'+this.layout+'"><label><input name="'+me.name+'[]" id="' + me.name + '" class="field group ' + ( ( me.required ) ? " required" : "" ) + '" value="' + $.fbuilder.htmlEncode( e.value ) + '" vt="' + $.fbuilder.htmlEncode( e.text ) + '" type="checkbox" /> ' + e.text + '</label></div>';
							}
						}	
						$( '#field' + me.form_identifier + '-' + me.index + ' .dfield' ).html( str );
						$( '#' + me.name ).change();
					}
				);
			}	
	});