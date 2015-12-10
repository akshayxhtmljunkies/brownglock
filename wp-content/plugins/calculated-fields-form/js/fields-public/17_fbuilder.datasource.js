	$.fbuilder.controls[ 'datasource' ] = function(){};
	$.fbuilder.controls[ 'datasource' ].prototype = {
		isDataSource:true,
		active : '',
		list : {
			'database' : { cffaction : 'get_data_from_database' },
			'posttype'  : { cffaction : 'get_posts' },
			'taxonomy' : { cffaction : 'get_taxonomies' },
			'user' 		: { cffaction : 'get_users' },
			'csv' 			: {
				csvData : {
					text : 0,
					value : 0,
					fields : [],
					rows : []
				},
				getData : function( callback )
					{
						var obj = {
								data : []
							};
						
						for( var i in this.csvData.rows )
						{
							var text = this.csvData.text,
								value = this.csvData.value;
								
							if( typeof this.csvData.rows[ i ].length == 'undefined' )
							{
								text  = this.csvData.fields[ text ];
								value = this.csvData.fields[ value ];
							}
							
							obj.data.push( { text: this.csvData.rows[ i ][ text ], value : this.csvData.rows[ i ][ value ] } );
						}	
						callback( obj );
					}
			}
		},
		getData : function( callback )
			{
				var me 	= this,
					obj = me.list[ me.active ];
					
				if( typeof me.list[ me.active ][ 'getData' ] != 'undefined' )
				{	
					obj.getData( callback );
					if( $( '[id="'+me.name+'"]' ).closest( '.pbreak:hidden' ).length )
					{
						$( '[id="'+me.name+'"]' ).addClass( 'ignorepb' );
					}
				}
				else
				{
					var url = document.location.href,
						data = {
							cffaction : obj.cffaction,
							form 	  : obj.form,
							field	  : me.name.replace( me.form_identifier, '' ),
							vars	  : {} 
						};
						
					if( typeof obj.vars != 'undefined' )
					{
						if ( !me.replaceVariables( obj.vars, data[ 'vars' ] ) ) return;
					}
				
					if( typeof me.ajaxConnect != 'undefined' ) me.ajaxConnect.abort();
					me.ajaxConnect = $.ajax(
						{
							dataType : 'json',
							url : url,
							cache : false,
							data : data,
							success : (function( me ){
								return function( data ){
									callback( data );
									if( $( '[id="'+me.name+'"]' ).closest( '.pbreak:hidden' ).length )
									{
										$( '[id="'+me.name+'"]' ).addClass( 'ignorepb' );
									}	
								};
							})(me)
						}
					);
				}	
			},
		replaceVariables : function( vars, _rtn )
			{
				var	me = this,
					field,
					formId = me.form_identifier,
					id,
					isValid = true,
					tmpArr = [], // To avoid duplicate handles
					val;
				
				for( var i = 0, h = vars.length; i < h; i++ )
				{
					id 		= vars[ i ]+formId;
					field 	= $.fbuilder[ 'forms' ][ formId ].getItem( id );
					
					if( typeof field != 'undefined' && field != false )
					{
						val = field.val();
						if( $( '#'+id ).val() == '' ) isValid = false;
						if( ( typeof me.hasBeenPutRelationHandles == 'undefined' || !me.hasBeenPutRelationHandles ) && $.inArray( id, tmpArr ) == -1 )
						{	
							$( document ).on( 'change', '#'+id, function(){ me.after_show(); } );
						}	
					}
					else
					{
						val = ( typeof window[ vars[ i ] ] != 'undefined' ) ? window[ vars[ i ] ] : '';
					}
					
					_rtn[ vars[ i ] ] = (val+'').replace( /^['"]+/, '' ).replace( /['"]+$/, '');
				}	
				me.hasBeenPutRelationHandles = true;
				return isValid;
			}
	};