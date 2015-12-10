/**/
jQuery( 
    function( $ )
    {
        window[ 'cpcff_woocommerce_validate'] = function( fId, e )
        {
			var r = true;
            
			if( typeof $.fn.valid !== 'undefined' )	r = $( e ).valid(); 
			
            if( !r )
            {
                setTimeout( 
                    (function( $, e )
                    {
                        return  function()
                                {
                                    $( e ).find( ':submit' ).removeAttr( 'disabled' );
                                };    
                    })( $, e ), 
                    500 
                );
            }  
            else
            {
				if( typeof fbuilderjQuery !== 'undefined' && fbuilderjQuery.fn.valid !== 'undefined' )	r = fbuilderjQuery( e ).valid(); 
                if( typeof window[ 'doValidate'+fId ] != 'undefined' ) r = window[ 'doValidate'+fId ]( e );
            }
            return r;
        };
		$( '[name="cp_calculatedfieldsf_pform_psequence"]' ).each(
            function()
            {
                var e   = $( this ),
                    fId = e.val(),
                    w   = e.closest( '.cpcff-woocommerce-wrapper' );
                
                if( 
                    w.length && 
                    typeof window[ 'doValidate' + fId ] != 'undefined' 
                )
                {
                    w.closest( 'form' )
                     .attr( 'id', 'cp_calculatedfieldsf_pform' + fId )
                     .attr( 'onsubmit', 'return cpcff_woocommerce_validate( "' + fId + '", this )' );
                     
                    w.find( '.pbSubmit' ).remove();
                }
            }
        );
		
        // Refresh the product price.
        $( '[name="woocommerce_cpcff_field"]' ).each( function(){
            var e = $( this ),
                f = e.siblings( '[name="woocommerce_cpcff_form"]' ),
                a = f.siblings( '[name="woocommerce_cpcff_product"]' ),
                s = $( '[name="cp_calculatedfieldsf_id"][value="' + f.val() + '"]' ).siblings( '[name="cp_calculatedfieldsf_pform_psequence"]' ).val(),
				tmpjQuery = ( typeof fbuilderjQuery != 'undefined' ) ? fbuilderjQuery : $;
			
			
            tmpjQuery( document ).on( 'change', '#'+e.val()+s, (function( a ){
                return function( evt ){
                    var e = $( evt.target );
                    $( '.post-'+a ).find( '.amount' ).each( function(){ if( $( this ).parent( 'del' ).length == 0 ) $( this ).html( e.val() ); } );
                }
            })( a.val() ) )

            tmpjQuery( document ).on( 
                'cpcff_default_calc', 
                '#cp_calculatedfieldsf_pform'+s, 
                (function( a, p ){
                    return function( evt ){ 
                        var e = $( '#'+p );
                        $( '.post-'+a ).find( '.amount' ).each( function(){ if( $( this ).parent( 'del' ).length == 0 ) $( this ).html( e.val() ); } ); 
                    }
                })( a.val(), e.val()+s )
            );
        } );
    }
);