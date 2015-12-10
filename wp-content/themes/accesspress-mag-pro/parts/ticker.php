<?php
/**
 * File for trending ticker at header
 * 
 *  @package AccessPress Mag Pro
 */
  $apmag_ticker_title = ( !of_get_option( 'ticker_title' ) ) ? __( 'Trending', 'accesspress-mag' ) : of_get_option( 'ticker_title' );
  $apmag_ticker_count = ( !of_get_option( 'ticker_count' ) ) ? '3' : of_get_option( 'ticker_count' );
  $apmag_ticker_speed = ( !of_get_option( 'ticker_speed' ) ) ? "3000" : of_get_option( 'ticker_speed' );
  $apmag_ticker_type =  ( !of_get_option( 'ticker_order' ) ) ? 'DESC' : of_get_option( 'ticker_order' );
  $ticker_args = array(
                  'post_type' => 'post',
                  'post_status' => 'publish',
                  'posts_per_page' => $apmag_ticker_count,
                  );
  if( empty( $apmag_ticker_type ) ){
     $ticker_args[ 'order' ] = 'DESC' ;
  }elseif( $apmag_ticker_type == 'asc' ){
     $ticker_args[ 'order' ] = 'ASC' ;
  }elseif( $apmag_ticker_type == 'rand' ){
     $ticker_args[ 'orderby' ] = 'rand' ;
  }
  $ticker_query = new WP_Query( $ticker_args );
  if( $ticker_query->have_posts() ){
?>
    <div class="apmag-news-ticker">
        <div class="apmag-container">
            <ul id="apmag-news" class="js-hidden">
<?php
    while( $ticker_query->have_posts() ){
        $ticker_query->the_post();
?>
                <li class="news-item"><a href="<?php the_permalink(); ?>"><?php echo the_title(); ?></a></li>
<?php
    }
?>
            </ul>
        </div>
    </div>    
<?php
  }  
?>

<script type="text/javascript">
jQuery(function($){
    // news ticker
        $('#apmag-news').ticker({
            speed: 0.10,
            htmlFeed: true,
            pauseOnItems: <?php echo $apmag_ticker_speed ;?>,
            fadeInSpeed: 600,
            displayType: 'reveal',
            titleText: '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $apmag_ticker_title ;?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
        });
    });
</script>


   
        
    