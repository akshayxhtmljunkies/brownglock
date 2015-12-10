<?php
/**
 * Header ads 
 * 
 * @package Accesspress Mag Pro
 */

?>
<div class="header-ad fullwidth">
    <?php 
        $apmag_header_ad = of_get_option( 'value_header_ad' );
        if(!empty($apmag_header_ad)){ echo $apmag_header_ad; } 
    ?>
</div><!--header ad-->