 
 	var maxadmin;
 	var maxcollection; 
 	
jQuery(document).ready(function($) {	
 
 	//j$ = $.noConflict();
  	maxadmin = new maxAdmin(); 
 	maxadmin.init(); 

	maxcollection = new maxCollection(); 
	maxcollection.init();  
 	
}); /* END OF JQUERY */
