/**/
jQuery( function( $ )
    {
		$.fn.cpcffautocomplete = function()
		{
			var e = $( this );
			e.each( function(){
				var s = $( this ),
					i,
					flag = false;
				if( s.siblings( '.cpcff-salesforce-attribute' ).length == 0 )
				{
					i = $( '<input type="text" placeholder="Attribute Name" class="cpcff-salesforce-attribute">' );
					i.attr( 'name', s.attr( 'name' ) );
					s.before( i );
					flag = true;
				}	
				else{
					i = s.siblings( '.cpcff-salesforce-attribute' );
				}	
				
				s.removeAttr( 'name' )
				 .change( 
					( function( i )
						{
							return function(){ i.val( $( this ).val() ) };
						} 
					)( i ) 
				 )
				
				if( flag ) s.change(); 
			} );
			return this;
		};
		
		$( '.cpcff-autocomplete' ).cpcffautocomplete();
	}    
);