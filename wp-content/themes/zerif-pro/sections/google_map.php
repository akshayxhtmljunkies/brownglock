<?php

$zerif_googlemap_address = get_theme_mod('zerif_googlemap_address',__('New York, Leroy Street','zerif'));

$zerif_googlemap_static = get_theme_mod('zerif_googlemap_static');

if( !empty($zerif_googlemap_address) ):

	if( isset($zerif_googlemap_static) && $zerif_googlemap_static == 1 ):

		echo "<img id='map' src='https://maps.googleapis.com/maps/api/staticmap?center=".$zerif_googlemap_address."&size=640x640&scale=2' >";
		
	else:
		?>
		<div class="zerif_map_overlay" onClick="style.pointerEvents='none'"></div>
		<?php
		echo "<section id='map'><iframe class='zerif_google_map' frameborder='0' scrolling='no'  marginheight='0' marginwidth='0' src='https://maps.google.com/maps?&amp;q=".urlencode( $zerif_googlemap_address )."&amp;output=embed&iwloc'></iframe></section>";

	endif;
	
endif;	

?>