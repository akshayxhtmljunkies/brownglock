window[ 'cp_cff_dark_notebook_onchange' ] = function(){
	var e = fbuilderjQuery( this );
	if( e.attr( 'type' ) == 'radio' ) e.closest( '.fields' ).find( '.cp_cff_dark_notebook_radio' ).removeClass( 'active' ); 
	if( e.is( ':checked' ) ) e.siblings( '.cp_cff_dark_notebook_checkbox, .cp_cff_dark_notebook_radio' ).addClass( 'active' );	
	else e.siblings( '.cp_cff_dark_notebook_checkbox, .cp_cff_dark_notebook_radio' ).removeClass( 'active' );
};

window[ 'cp_cff_dark_notebook' ] = function(){
	if( typeof fbuilderjQuery == 'undefined' )
	{
		setTimeout( cp_cff_dark_notebook, 500 );
		return;
	}

	fbuilderjQuery( document ).one( 'showHideDepEvent cp_cff_dark_notebookEvent', function(){
		// Modifies the radio buttons and checkboxes appearances
		fbuilderjQuery( '.cp_cff_dark_notebook input[type="radio"]:not(:checked)' ).each( function(){ var e = fbuilderjQuery(this); if( e.siblings( '.cp_cff_dark_notebook_radio' ).length == 0 ) e.before( '<span class="cp_cff_dark_notebook_radio"></span>' ); });
		fbuilderjQuery( '.cp_cff_dark_notebook input[type="radio"]:checked' ).each( function(){ var e = fbuilderjQuery(this); if( e.siblings( '.cp_cff_dark_notebook_radio' ).length == 0 ) e.before( '<span class="cp_cff_dark_notebook_radio active"></span>' ); });
		fbuilderjQuery( '.cp_cff_dark_notebook input[type="checkbox"]:not(:checked)' ).each( function(){ var e = fbuilderjQuery(this); if( e.siblings( '.cp_cff_dark_notebook_checkbox' ).length == 0 ) e.before( '<span class="cp_cff_dark_notebook_checkbox"></span>' ); });
		fbuilderjQuery( '.cp_cff_dark_notebook input[type="checkbox"]:checked' ).each( function(){ var e = fbuilderjQuery(this); if( e.siblings( '.cp_cff_dark_notebook_checkbox' ).length == 0 ) e.before( '<span class="cp_cff_dark_notebook_checkbox active"></span>' ); });
		
		fbuilderjQuery( '.cp_cff_dark_notebook input[type="radio"],.cp_cff_dark_notebook input[type="checkbox"]' ).unbind( 'change', cp_cff_dark_notebook_onchange ).bind( 'change', cp_cff_dark_notebook_onchange );
	});
	fbuilderjQuery( document ).trigger( 'cp_cff_dark_notebookEvent' );
};
cp_cff_dark_notebook();