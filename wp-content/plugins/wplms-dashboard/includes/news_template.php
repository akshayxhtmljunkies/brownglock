<?php

$course_id = get_the_id();
$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
$num = get_option('posts_per_page');
$args = array(
	'post_type' => 'news',
	'paged' => $paged,
	'post_per_page'=> $num,
  'post_status' => 'publish',
	'meta_query'=> array(
		array(
            'meta_key' => 'vibe_news_course',
            'compare' => '=',
            'value' => $course_id,
            'type' => 'numeric'
            ),
          )
	);
$news = new WP_Query($args);
global $wp_query;

echo '<h3 class="heading">'.__('Course News','wplms-dashboard').'</h3>';
if($news->have_posts()){
	echo '<ul>';
  $wp_query=$news;
	while($news->have_posts()){
		$news->the_post();
		$format=get_post_format(get_the_ID());
          if(!isset($format) || !$format)
            $format = 'standard';

          echo '<li>';
          echo '<div class="'.$format.'-block news"><span class="right">'.sprintf('%02d', get_the_time('j')).' '.get_the_time('M').'\''.get_the_time('y').'</span>
                  <h4><a href="'.get_permalink().'">'.get_the_title().'</a></h4>';
            echo '<div class="news_thumb"><a href="'.get_permalink().'">'.get_the_post_thumbnail().'</a></div>';
                  the_excerpt();
            echo '<a href="'.get_permalink().'" class="right link">'.__('Read More','wplms-dashboard').'</a><ul class="tags">'.get_the_term_list($post->ID,'news-tag','<li>','</li><li>','</li>').'</ul>
            </div></li>';
	}
	echo '</ul>';
   pagination();
}else{
	echo '<div class="message error">'.__('No news available for Course','wplms-dashboard').'</div>';
}
wp_reset_postdata();
wp_reset_query();
?>