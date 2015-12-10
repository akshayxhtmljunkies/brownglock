<?php

/**
 * FILE: func.php 
 * Author: Mr.Vibe 
 * Credits: www.VibeThemes.com
 * Project: WPLMS
 */
//This fix is for Local BBPress setup only
/*add_filter( 'bbp_verify_nonce_request_url', 'my_bbp_verify_nonce_request_url', 999, 1 );
function my_bbp_verify_nonce_request_url( $requested_url )
{
    return 'http://localhost' . $_SERVER['REQUEST_URI'];
}*/

function bbp_enable_visual_editor( $args = array() ) {
    $args['tinymce'] = true;
    return $args;
}

add_action( 'init','wplms_remove_wpseo_from_buddypress',20);
function wplms_remove_wpseo_from_buddypress(){
  if(function_exists('bp_is_blog_page')){
    if(!bp_is_blog_page()){
      global $wpseo_front;
      remove_filter( 'wp_title', array( $wpseo_front, 'title' ), 15 );
     }  
  }
}

add_filter('woocommerce_enable_setup_wizard','wplms_disable_woocommerce_setup_wizard');
function wplms_disable_woocommerce_setup_wizard($setup_wizard){
  return false;
}

add_filter( 'bbp_after_get_the_content_parse_args', 'bbp_enable_visual_editor' );

function wplms_removeHeadLinks(){
  $xmlrpc = vibe_get_option('xmlrpc');
  if(isset($xmlrpc) && $xmlrpc){
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link'); 
    add_filter('xmlrpc_enabled','__return_false');
  }
}
add_action('init', 'wplms_removeHeadLinks');

add_filter('wplms_sidebar','wplms_sidebar_select',10,2);
function wplms_sidebar_select($sidebar,$id = NULL){
  if(isset($id)){
    $selected_sidebar=get_post_meta($id,'vibe_sidebar',true);  
    if(isset($selected_sidebar) && $selected_sidebar){

        /*=== FOR BACKWARD COMPATIBILITY ===*/
        if($selected_sidebar == 'mainsidebar' && $sidebar != 'mainsidebar'){
               $selected_sidebar = $sidebar;
        }else
          $sidebar=$selected_sidebar; 
        /*=== END BACKWARD COMPATIBILITY ===*/
    }
  }
  return $sidebar;
}


// Adding Course Categories in LMS Menu
function wpa83704_adjust_the_wp_menu() {
    add_submenu_page(
        'lms',
        __('Course Categories','vibe'),
        __('Course Category','vibe'),
        'edit_posts',
        'edit-tags.php?taxonomy=course-cat'
    );
    add_submenu_page(
        'lms',
        __('Quiz type','vibe'),
        __('Quiz type','vibe'),
        'edit_posts',
        'edit-tags.php?taxonomy=quiz-type'
    );
    $level = vibe_get_option('level');
    if(isset($level) && $level){
      add_submenu_page(
        'lms',
        __('Levels','vibe'),
        __('Level','vibe'),
        'edit_posts',
        'edit-tags.php?taxonomy=level'
    );
    }
    $location = vibe_get_option('location');
    if(isset($location) && $location){
      add_submenu_page(
        'lms',
        __('Locations','vibe'),
        __('Location','vibe'),
        'edit_posts',
        'edit-tags.php?taxonomy=location'
    );
    }
    $linkage = vibe_get_option('linkage');
    if(isset($linkage) && $linkage){
      add_submenu_page(
        'lms',
        __('Linkage','vibe'),
        __('Linkage','vibe'),
        'edit_posts',
        'edit-tags.php?taxonomy=linkage'
    );
    }
}
add_action( 'admin_menu', 'wpa83704_adjust_the_wp_menu', 999 );

//Correcting the Active bug in WordPress admin menus (refer: https://core.trac.wordpress.org/ticket/12718) 
function course_cat_menu_correction($parent_file) {
    global $current_screen;
    $taxonomy = $current_screen->taxonomy;
    if ($taxonomy == 'course-cat' || $taxonomy == 'level' || $taxonomy == 'linkage' || $taxonomy == 'quiz-type')
        $parent_file = 'lms';
    return $parent_file;
}
add_action('parent_file', 'course_cat_menu_correction');

/*==== START Course Maximum STUDENTS Filter ======*/

add_filter('wplms_course_button_extra','vibe_course_button_students_extra',10,2);
function vibe_course_button_students_extra($extra,$course_id){
    $max_students=get_post_meta($course_id,'vibe_max_students',true);
    if(isset($max_students) && $max_students && $max_students < 9998){
        $number=bp_course_count_students_pursuing($course_id);
        $extra .= '<span>'.$number.' / '.$max_students.' '.__('SEATS','vibe').'</span>';
    }
    return $extra;
}

remove_filter('wplms_course_button_extra','vibe_course_button_students_extra');
add_filter('wplms_course_button_extra','custom_vibe_course_button_students_extra',10,2);
function custom_vibe_course_button_students_extra($extra,$course_id){
    $max_students=get_post_meta($course_id,'vibe_max_students',true);
    if(isset($max_students) && $max_students && $max_students < 9998){
        $number=bp_course_count_students_pursuing($course_id);
        $extra .= '<span>'.($max_students-$number).' '.__('SEATS LEFT','vibe').'</span>';
    }
    return $extra;
}


add_action('wplms_after_every_unit','wplms_add_style_for_instructor',10,1);
function wplms_add_style_for_instructor($post_id){

  $notes=vibe_get_option('unit_comments');
  if(!isset($notes) || !is_numeric($notes))
    return;
    $customizer=get_option('vibe_customizer');
    $primary_bg = '#70c989';
    if(isset($customizer) && is_array($customizer)){
      if(isset($customizer['primary_bg']))
          $primary_bg = $customizer['primary_bg'];
    }
    $author_id = get_post_field( 'post_author', $post_id );
    echo '<style>.note.user'.$author_id.' img{border: 2px solid '.$primary_bg.';}</style>';
}

/*==== Simple Notes & Discussion ======*/

add_filter('wplms_unit_classes','wplms_check_notes_filter');
function wplms_check_notes_filter($unit_class){
  $notes_style = vibe_get_option('notes_style');
  if(isset($notes_style) && is_numeric($notes_style)){
    $unit_class .= ' stop_notes';
  }
  return $unit_class;
}

add_action('wplms_after_every_unit','wplms_show_notes_discussion',10,1);
function wplms_show_notes_discussion($unit_id){
  $notes_style = vibe_get_option('notes_style');
  $unit_comments = vibe_get_option('unit_comments');
  
  if(isset($notes_style) && is_numeric($notes_style) && is_numeric($unit_comments) && is_user_logged_in()){
    ?>
      <div id="discussion" data-unit="<?php echo $unit_id; ?>">
      <h3 class="heading"><?php _e('Discussion','vibe'); 
        $user_id = get_current_user_id();
        $post_author = get_post_field('post_author',$unit_id,true);
        $post_authors = array($post_author);
        if(function_exists('get_coauthors')){
          $post_authors = get_coauthors( $unit_id );
        }
        if( in_array($user_id,$post_authors) || current_user_can('manage_posts')) { 
          edit_post_link(__('Manage','vibe'),'','',$unit_id);
        }
      ?>
      </h3>
      <ol class="commentlist">
          <?php    
              

              $per_page = get_option('comments_per_page');
              if(!is_numeric($per_page))
                $per_page = 5;
              
              $comments_count = get_comments(array('post_id'=>$unit_id,'count'=>true,'parent'=>0));
              $max = ceil($comments_count/$per_page);

              $offset = 0;
              $comments = get_comments(array(
                  'post_id' => $unit_id,
                  'offset' => 0,
                  //'number' => $per_page,
                  'status' => 'approve'
              ));

              wp_list_comments(array(
                  'per_page' => $per_page, //Allow comment pagination
                  'avatar_size' => 120,
                  'callback' => 'wplms_unit_comment' //Show the latest comments at the top of the list
              ), $comments);
          ?>
      </ol>
    <?php

      if($comments_count > $per_page){
        $more = $comments_count - $per_page;
        
        if($max > 1){
          ?>
            <a class="load_more_comments right" data-page="1" data-max="<?php echo $max; ?>" data-per="<?php echo $per_page; ?>"><?php printf(__('Load more (<span>%s</span>)','vibe'),$more); ?></a>
          <?php
        }
      }
      ?>
      <a class="add_comment"><?php printf(__('Ask Question','vibe'),$more); ?></a>
      <div id="add_unit_comment" class="add_unit_comment_text hide">
        <textarea></textarea>
        <a class="button post_question"><?php _e('Post Question','vibe'); ?></a>
        <a class="button cancel"><?php _e('Cancel','vibe'); ?></a>
      </div>
      </div>
      <?php
  }
}

function wplms_unit_comment($comment, $args, $depth) {
  $GLOBALS['comment'] = $comment;
  extract($args, EXTR_SKIP);

  if ( 'div' == $args['style'] ) {
    $tag = 'div';
    $add_below = 'comment';
  } else {
    $tag = 'li';
    $add_below = 'div-comment';
  }
?>
  <<?php echo $tag ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>" data-id="<?php comment_ID() ?>">
  <?php if ( 'div' != $args['style'] ) : ?>
  <div id="div-comment-<?php comment_ID() ?>" class="comment-body">
  <?php endif; ?>
  <div class="comment-author vcard">
  <?php if ( $args['avatar_size'] != 0 ) {
    if ( function_exists( 'bp_core_fetch_avatar' ) ) :
    echo bp_core_fetch_avatar( array(
    'item_id' => $comment->user_id,
    'type' => 'thumb',
    ));
    endif;
    //echo get_avatar( $comment, $args['avatar_size'] );
  }?>
  <div class="comment-meta commentmetadata">
    <?php
    if(current_user_can('edit_posts')){
    ?>  <a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>"><?php
    }

    $time_passed = human_time_diff(strtotime(get_comment_date('d.m.Y').' '.get_comment_time()),current_time('timestamp'));
      printf( __('%1$s ago','vibe'), $time_passed ); 
    if(current_user_can('edit_posts')){
    ?></a><?php
    }  ?>
  </div>
  <?php printf( __( '<cite class="fn">%s</cite>' ), get_comment_author_link() ); ?>
  </div>
  <?php if ( $comment->comment_approved == '0' ) : ?>
    <em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.' ); ?></em>
    <br />
  <?php endif; ?>

  <?php comment_text(); ?>

  <div class="reply">
    <a><?php _e('REPLY','vibe');?></a>
  </div>
  <?php if ( 'div' != $args['style'] ) : ?>
  </div>
  <?php endif; ?>
<?php
}

/*==== START Course : COURSE TIME Filter ======*/

add_filter('wplms_course_button_extra','vibe_course_button_time_extra',10,2);
function vibe_course_button_time_extra($extra,$course_id){
    $start_date=get_post_meta($course_id,'vibe_start_date',true);
    $timestamp = strtotime(date_i18n( get_option( 'date_format' ), strtotime( $start_date )));
    if(isset($start_date) && $timestamp > current_time('timestamp')){ 
        $time_remaining = human_time_diff(current_time('timestamp'),$timestamp);
        $extra .= '<span>'.__('COURSE STARTS IN ','vibe').$time_remaining.'</span>';
    }
    return $extra;
}
add_filter('wplms_course_details_widget','vibe_show_course_start_time_in_course_details');
function vibe_show_course_start_time_in_course_details($course_details){
    $course_id = get_the_ID();
    $start_date=get_post_meta($course_id,'vibe_start_date',true);
    $timestamp = strtotime( $start_date );
    if(isset($start_date) &&  $timestamp  > time()){ 
        $time_remaining = human_time_diff(time(),$timestamp);
        $extra=array('start_time' => '<li><i class="i_course_time">'.$time_remaining.'</i>'.__('STARTS IN ','vibe').'</li>');
        array_splice($course_details, 1, 0, $extra);
    }
    return $course_details;
}

add_filter('wplms_take_course_page','vibe_course_time_check',10,2);
//add_filter('wplms_course_product_id','vibe_course_time_check',10,2);
function vibe_course_time_check($link,$course_id){
    $start_date=get_post_meta($course_id,'vibe_start_date',true);
    if(isset($start_date) && strtotime($start_date) > time()){
          return '#';
    }
    return $link;
}

add_action('bp_before_course_body','vibe_check_start_date_post');
function vibe_check_start_date_post(){
  if(isset($_POST['start_course'])){
    $course_id=get_the_ID();
    $start_date=get_post_meta($course_id,'vibe_start_date',true);
      if(strtotime($start_date) > time()){
          $time_remaining = tofriendlytime(strtotime($start_date) - time());
          echo '<div id="message" class="notice"><p>'.__('COURSE STARTS IN ','vibe').$time_remaining.'</p></div>';
      }
  }
}

add_action('bp_before_course_body','vibe_check_annoucement');
function vibe_check_annoucement(){
    $course_id=get_the_ID();
    $announcement= get_post_meta($course_id,'announcement',true);
    if(isset($announcement) && strlen($announcement) > 2){
      $announcement_student_type = get_post_meta($course_id,'announcement_student_type',true);
      
      switch($announcement_student_type){
        case 1: // All students
            if(is_user_logged_in()){
              echo '<div id="message" class="notice"><p>'.$announcement.'</p></div>';
            }
        break;
        case 2:// Pursuing course
            if(is_user_logged_in()){
                $user_id=get_current_user_id();
                $course_status = get_user_meta($user_id,'course_status'.$course_id,true);
                if(is_numeric($course_status) && $course_status < 2 ){
                  echo '<div id="message" class="notice"><p>'.$announcement.'</p></div>';
                }
            }
        break;
        case 3:// Who finished course
            if(is_user_logged_in()){
                $user_id=get_current_user_id();
                $course_status = get_user_meta($user_id,'course_status'.$course_id,true);
                if(is_numeric($course_status) && $course_status > 2){
                  echo '<div id="message" class="notice"><p>'.$announcement.'</p></div>';
                }
            }
        break;
        case 4:// Who finished course
              echo '<div id="message" class="notice"><p>'.$announcement.'</p></div>';
        break;
      }
      
    }
}


/*==== WORDPRESS SEO COMPATIBILITY ======*/


add_filter('wpseo_pre_analysis_post_content','vibe_page_builder_content',10,2);
function vibe_page_builder_content($post_content,$post){

  if(get_post_type($post->ID) != 'page')
    return $post_content;

  $builder_enable = get_post_meta( $post->ID, '_enable_builder', true );
  $builder_layout = get_post_meta( $post->ID, '_builder_settings', true );
  $add_content = get_post_meta( $post->ID, '_add_content', true );
  
        
  if ( isset($builder_layout) &&  isset($builder_layout['layout_shortcode']) && '' != $builder_layout['layout_shortcode'] && $add_content == 'no') { 
             
            $content = $builder_layout['layout_shortcode'];
          
        }
        
        if ( $builder_layout && '' != $builder_layout['layout_shortcode'] && $add_content == 'yes_top') {
           
            $content = $content.$builder_layout['layout_shortcode'];
        }
        
        if ( $builder_layout && '' != $builder_layout['layout_shortcode'] && $add_content == 'yes_below') {
            
            $content = $builder_layout['layout_shortcode'].$content;
        }
        if(isset($content)){
            $post_content = str_replace('[','',$content);
            $post_content = str_replace(']','',$content);
        }

    return $post_content;
}

//FIX : COOKIE LOGIN FIX  

function set_wp_test_cookie() {   
  setcookie(TEST_COOKIE, 'WP Cookie check', 0, COOKIEPATH, COOKIE_DOMAIN);  
  if ( SITECOOKIEPATH != COOKIEPATH )     
    setcookie(TEST_COOKIE, 'WP Cookie check', 0, SITECOOKIEPATH, COOKIE_DOMAIN); 
} 
add_action( 'after_setup_theme', 'set_wp_test_cookie', 101 );

// FIX : REDIRECT TO HOME PAGE ON LOGOUT

add_filter('logout_url', 'wplms_logout_url',10,2);
function wplms_logout_url($logout_url,$redirect = null ) {
  return $logout_url . '&amp;redirect_to=' . urlencode( home_url() );
}

//FIX : BP DEFAULT AVATAR FIX : Refer : https://buddypress.trac.wordpress.org/ticket/4571 

add_filter( 'bp_core_fetch_avatar_no_grav', '__return_true' );
add_filter( 'bp_core_default_avatar_user', 'vibe_custom_avatar' );
function vibe_custom_avatar($avatar){
  global $bp;
   $avatar=vibe_get_option('default_avatar');
   if(!isset($avatar) || !$avatar || strlen($avatar)<5)
    $avatar = VIBE_URL.'/images/avatar.jpg';
   return $avatar;
} 

add_filter('wplms_activity_loop','wplms_student_activity');
function wplms_student_activity($appended){
  $student_activity = vibe_get_option('student_activity');
  if(!current_user_can('edit_posts') && isset($student_activity) && $student_activity){
    $appended .='&user_id='.get_current_user_id();
  }
    
    return $appended;
}
// Fix for Member/Settings/Profile => 1.5 hrs of reading BuddyPress core code just to find the hook :D
add_filter('bp_settings_screen_xprofile','bp_settings_custom_profile',1,1);
function bp_settings_custom_profile($profile){
  return '/members/single/settings/profile';
}

add_filter('course_friendly_time','convert_unlimited_time',1,2);
function convert_unlimited_time($time_html,$time){
    if(intval($time/86400) > 999){
      return __('UNLIMITED ACCESS','vibe');
    }
    return $time_html;
}

add_filter('course_friendly_time','wplms_custom_course_parameter',10,3);
function wplms_custom_course_parameter($time_html,$time,$id){
  if(get_post_type($id) != BP_COURSE_CPT)
    return $time_html;

  $parameter = vibe_get_option('course_duration_display_parameter');
  if($parameter && intval($time/86400) < 999){
    $time_html = floor($time/$parameter).' '.calculate_duration_time($parameter);
  }
  return $time_html;
}


/// BEGIN AJAX HANDELERS
add_action( 'wp_ajax_reset_googlewebfonts', 'reset_googlewebfonts' );
	function reset_googlewebfonts(){ 
            echo "reselecting..";
            $r = get_option('google_webfonts');
            if(isset($r)){
                delete_option('google_webfonts');
            }
                die();
}

if(!function_exists('import_data')){           
add_action( 'wp_ajax_import_data', 'import_data' );
  function import_data(){
    if(!current_user_can('manage_options'))
      die();

    $name = stripslashes($_POST['name']);
                $code = base64_decode(trim($_POST['code'])); 
                if(is_string($code))
                    $code = unserialize ($code);
                $value = get_option($name);
                if(isset($value)){
                      update_option($name,$code);
                }else{
                    echo "Error, Option does not exist !";
                }
                die();
            }
} 


add_action('wplms_be_instructor_button','wplms_be_instructor_button');
function wplms_be_instructor_button(){
  $teacher_form = vibe_get_option('teacher_form');

  if(isset($teacher_form) && is_numeric($teacher_form)){
    echo '<a href="'.(isset($teacher_form)?get_permalink($teacher_form):'#').'" class="button create-group-button full">'. __( 'Become an Instructor', 'vibe' ).'</a>';  
  }else{
    vibe_breadcrumbs();
  }
}

add_filter('wplms_curriculum_time_filter','wplms_custom_curriculum_time_filter',2,10);
function wplms_custom_curriculum_time_filter($html,$min){
  $minutes = $min;
  $hours = '00';
  if($minutes > 60){
    $hours = intval($minutes/60);
    $minutes = $minutes - $hours*60;
  }
  if($min > 9998){
    $html = '<span><i class="icon-clock"></i> '.__('UNLIMITED TIME','vibe').'</span>';
  }
  
  return $html;
}


add_action( 'pre_get_posts', 'course_search_results' );
function course_search_results($query){

  if(!$query->is_search() && !$query->is_main_query())
    return $query;

  if(isset($_GET['course-cat']))
      $course_cat = $_GET['course-cat'];

  if(isset($_GET['instructor']))
      $instructor = $_GET['instructor'];  

  if ( function_exists('get_coauthors')) {
    $taxquery=array();
    if(isset($course_cat) && $course_cat !='*' && $course_cat !='' && is_numeric($instructor))
    $taxquery = array('relation' => 'AND');

    if(isset($course_cat) && $course_cat !='*' && $course_cat !=''){
      $taxquery[]=array(
        'taxonomy' => 'course-cat',
        'field'    => 'slug',
        'terms'    => $course_cat
        );
    }
    if(isset($instructor) && $instructor !='*' && $instructor !='' && is_numeric($instructor)){
      $instructor_name = 'cap-'.strtolower(get_the_author_meta('user_login',$instructor)); 
      $taxquery[]=array(
        'taxonomy' => 'author',
        'field'    => 'slug',
        'terms'    => $instructor_name
        );
    }
    $query->set('tax_query', $taxquery);
  }else{ 
    if(isset($course_cat) && $course_cat !='*' && $course_cat !=''){
      $query->set('course-cat', $course_cat);
    }
    if(isset($instructor) && $instructor !='*' && $instructor !=''){
      $query->set('author', $instructor);
    }
  }
  return $query;
}

// Temporary Fix to the WordPress Bug : https://core.trac.wordpress.org/ticket/11330
add_filter( 'request', 'my_request_filter' );
function my_request_filter( $query_vars ) {
    if( isset( $_GET['s'] ) && empty( $_GET['s'] ) ) {
        $query_vars['s'] = " ";
    }
    return $query_vars;
}

//add_action( 'pre_get_comments', 'restrict_questions_answers' );
function restrict_questions_answers( $comments ) {
    global $post;
    /*
    if($post->post_type != 'quiz' && $post->post_type != 'question'){
      //$comments->query_vars['post_type'] = 'post';
      //$comments->meta_query->parse_query_vars( $comments->query_vars );  
    }*/
}

add_action('template_redirect','vibe_check_access_check');
function vibe_check_access_check(){ 

    if(!is_singular(array('unit','question')))
      return;

    $flag=0;
    global $post;
    if(!current_user_can('edit_posts')){
      $flag=1;
      $free=get_post_meta(get_the_ID(),'vibe_free',true);
      if(vibe_validate($free) || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && is_user_logged_in()))
        $flag=0;
    }else{
        $flag=0;
        $instructor_privacy = vibe_get_option('instructor_content_privacy');
        $user_id=get_current_user_id();
        if(isset($instructor_privacy) && $instructor_privacy && !current_user_can('manage_options')){
            if($user_id != $post->post_author)
              $flag=1;
        }
    }
    if($post->post_type == 'unit'){
      $post_type = __('UNITS','vibe');
    }else if($post->post_type == 'question'){
      $post_type = __('QUESTIONS','vibe');
    }

    $message = sprintf(__('DIRECT ACCESS TO %s IS NOT ALLOWED','vibe'),$post_type);
    $flag = apply_filters('wplms_direct_access_to'.$post->post_type,$flag,$post);
    if($flag){
        wp_die($message,$message,array('back_link'=>true));
    }
}

add_action( 'template_redirect', 'vibe_check_course_archive' );
function vibe_check_course_archive(){
    if(is_post_type_archive('course') && !is_search()){
        $pages=get_site_option('bp-pages');
        if(is_array($pages) && isset($pages['course'])){
          $all_courses = get_permalink($pages['course']);
          wp_redirect($all_courses);
          exit();
        }
    }
}

add_action( 'template_redirect', 'vibe_product_woocommerce_direct_checkout' );
function vibe_product_woocommerce_direct_checkout()
{   
  if(in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))){
        $check=vibe_get_option('direct_checkout');
        $check =intval($check);
    if(isset($check) &&  $check == 2){
      if( is_single() && get_post_type() == 'product' && isset($_GET['redirect'])){
          global $woocommerce;
          $found = false;
          $product_id = get_the_ID();
          $courses = vibe_sanitize(get_post_meta(get_the_ID(),'vibe_courses',false));
          if(isset($courses) && is_array($courses) && count($courses)){
            if ( sizeof( WC()->cart->get_cart() ) > 0 ) {
              foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
                $_product = $values['data'];
                if ( $_product->id == $product_id )
                  $found = true;
              }
              // if product not found, add it
              if ( ! $found )
                WC()->cart->add_to_cart( $product_id );
                $checkout_url = $woocommerce->cart->get_checkout_url();
                wp_redirect( $checkout_url);  
            } else {
              // if no products in cart, add it
              WC()->cart->add_to_cart( $product_id );
              $checkout_url = $woocommerce->cart->get_checkout_url();
              wp_redirect( $checkout_url);  
            }

            
            exit();
          }
      }
    }
    if(isset($check) &&  $check == 3){ 
      if( is_single() && get_post_type() == 'product' && isset($_GET['redirect'])){ 
          global $woocommerce; 
          $found = false;
          $product_id = get_the_ID();
          $courses = vibe_sanitize(get_post_meta(get_the_ID(),'vibe_courses',false));
          
          if(isset($courses) && is_array($courses) && count($courses)){
            if ( sizeof( WC()->cart->get_cart() ) > 0 ) {
              foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
                $_product = $values['data'];
                if ( $_product->id == $product_id )
                  $found = true;
              }
              // if product not found, add it
              if ( ! $found )
                WC()->cart->add_to_cart( $product_id );
                $cart_url = $woocommerce->cart->get_cart_url(); 
                wp_redirect( $cart_url); 
            }else {
              WC()->cart->add_to_cart( $product_id );
              $cart_url = $woocommerce->cart->get_cart_url(); 
              wp_redirect( $cart_url);
            }
            exit();
          }
      }
    }
  }
}

add_action('woocommerce_order_item_name','vibe_view_woocommerce_order_course_details',2,100);
function vibe_view_woocommerce_order_course_details($html, $item ){
 
  $product_id=$item['item_meta']['_product_id'][0];
  if(isset($product_id) && is_numeric($product_id)){
      $courses = vibe_sanitize(get_post_meta($product_id,'vibe_courses',false));
      if(isset($courses) && is_Array($courses)){
        $html .= ' [ <i>'.__('COURSE : ','vibe');
        foreach($courses as $course){ 
          if(is_numeric($course))
           $html .= '<a href="'.get_permalink($course).'"><strong><i>'.get_the_title($course).'</i></strong></a>, ';
        }
        $html .=' </i> ]';
      }
  }
  return $html;
}

add_action('woocommerce_share','wplms_social_buttons_on_product',1000);
function wplms_social_buttons_on_product(){
    echo do_shortcode('[social_buttons]');
}


// added in version 1.5.3 , Assignment -> Unit Locking : Mark Assignments complete before seeing the mark this unit complete switch 
add_filter('wplms_unit_mark_complete','wplms_assignments_force_unit_complete',1,3);  
function wplms_assignments_force_unit_complete($mark_unit_html,$unit_id,$course_id){
    $flag=0;
    $assignment_locking = vibe_get_option('assignment_locking');
    if(isset($assignment_locking) && $assignment_locking){

        $unit_assignments = get_post_meta($unit_id,'vibe_assignment',false);
        if(is_Array($unit_assignments) && is_array($unit_assignments[0]))
          $unit_assignments = vibe_sanitize($unit_assignments);

        if(isset($unit_assignments) && is_array($unit_assignments))
        foreach($unit_assignments as $unit_assignment){
          if(is_numeric($unit_assignment)){
            $user_id = get_current_user_id();
            $assignment_complete = get_post_meta($unit_assignment,$user_id,true);
            if(isset($assignment_complete) && $assignment_complete){
              $flag=0;
            }else{
              $flag=1;
            }
          }//end-if  
        }//end-for
      }
      
  if($flag)
      return '<a>'.__('FINISH ASSIGNMENT TO MARK UNIT COMPLETE','vibe').'</a>';
  return $mark_unit_html;
}  


add_filter('wplms_unit_mark_complete','wplms_media_force_unit_complete',10,3);  
function wplms_media_force_unit_complete($mark_unit_html,$unit_id,$course_id){
    
    $unit_media_lock = vibe_get_option('unit_media_lock');
    if(isset($unit_media_lock) && $unit_media_lock){
       $flag=0;
        $unit_content=get_post($unit_id);

        if(strpos($unit_content->post_content,'[video ') !== false){
          $flag=1;
        }else{
          if(strpos($unit_content->post_content,'[audio ') !== false)
            $flag=1;
          else
            $flag=0;
        }

        if($flag)
          return '<a href="#" id="mark-complete" data-unit="'.$unit_id.'" class="unit_button tip" title="'.__('Finish Video/Audio to mark this unit complete','vibe').'">'.__('Mark this Unit Complete','vibe').'</a>';    
    }
  return $mark_unit_html;
} 


add_action( 'wp_ajax_tour_number', 'inc_tour_number' );
  function inc_tour_number(){ 
            $r = get_option('tour_number');
            if(isset($r)){ $r++;
                update_option('tour_number', $r);
            }else
                add_option('tour_number', 0);
                die();
}

add_filter('get_avatar','change_avatar_css');

function change_avatar_css($class) {
$class = str_replace("class='avatar", "class='retina_avatar zoom animate", $class) ;
return $class;
}


function the_sub_title($id=NULL){
  global $post;
  if(isset($id)){
    $return=get_post_meta($id,'vibe_subtitle',true);
  }else{
    $return=get_post_meta($post->ID,'vibe_subtitle',true);  
  }
  if(isset($return) && strlen($return) > 5){
    echo '<h5>'.$return.'</h5>';  
  }
  
}

if(!function_exists('vibe_socialicons')){
    function vibe_socialicons(){
        global $vibe_options; $html='';
        $social_icons = vibe_get_option('social_icons');
        $social_icons_type = vibe_get_option('social_icons_type');
        $html = '<ul class="socialicons '.$social_icons_type.'">';

        $show_social_tooltip = vibe_get_option('show_social_tooltip');
        
        if(is_array($social_icons) && is_array($social_icons['social'])){
         foreach($social_icons['social'] as $key=>$icon){ 
            $url=$social_icons['url'][$key];
            $title = $icon;
            if($icon == 'fontawesome-webfont-5')
              $title = 'youtube';
            
            $html .= '<li><a href="'.$url.'" title="'.$title.'" target="_blank"  class="'.$icon.'"><i class="icon-'.$icon.'"></i></a></li>';
            }
         }
        $html .= '</ul>';
        return $html;  
     } 
}



if(!function_exists('get_all_taxonomy_terms')){
    function get_all_taxonomy_terms(){
        $taxonomies=get_taxonomies('','objects'); 
        $termchildren = array();
        foreach ($taxonomies as $taxonomy ) {
            $toplevelterms = get_terms($taxonomy->name, 'hide_empty=0&hierarchical=0&parent=0');
          foreach ($toplevelterms as $toplevelterm) {
                    $termchildren[$toplevelterm->slug] = $taxonomy->name.' : '.$toplevelterm->name;
            }
            }
            
    return $termchildren;  
    }
}

function change_wp_login_url() {
  //echo vibe_site_url();
}
function change_wp_login_title() {
  //echo get_option('blogname');
}
add_filter('login_headerurl', 'change_wp_login_url');
add_filter('login_headertitle', 'change_wp_login_title');

if(!function_exists('wplms_profile_group_tabs')){
  function wplms_profile_group_tabs($tabs=NULL, $groups=NULL, $group_name=NULL){
    $instructor_field_group = vibe_get_option('instructor_field_group');
    if(isset($instructor_field_group) && $instructor_field_group !=''){
      if(isset($groups) && is_array($groups))
        foreach($groups as $key=>$group){ 
         if($group->name == $instructor_field_group && !current_user_can('edit_posts')){ 
           unset($tabs[$key]);
           break;
         }
       }
    }
     return $tabs;
  }

  add_filter('xprofile_filter_profile_group_tabs','wplms_profile_group_tabs',1,3);
}

add_action('bp_init','wplms_profile_group_tabs');


if(!function_exists('wp_get_attachment_info')){
function wp_get_attachment_info( $attachment_id ) {
       
	$attachment = get_post( $attachment_id );
        if(isset($attachment)){
	return array(
		'alt' => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
		'caption' => $attachment->post_excerpt,
		'description' => $attachment->post_content,
		'href' => get_permalink( $attachment->ID ),
		'src' => $attachment->guid,
		'title' => $attachment->post_title
	);
       }
}
}


function set_wpmenu(){
    echo '<p style="padding:20px 0 10px;text-align:center;">'.__('Setup Menus','vibe').'</p>';
}

add_action('wplms_validate_certificate','wplms_validata_certificate_code',10,2);
function wplms_validata_certificate_code($user_id,$course_id){
  bp_course_validate_certificate('user_id='.$user_id.'&course_id='.$course_id);  
}

add_filter('wplms_certificate_code_template_id','wplms_get_template_id_from_certificate_code');
function wplms_get_template_id_from_certificate_code($code){
  $codes = explode('-',$code);
  $template = intval($codes[0]);
  return $template;
}

add_filter('wplms_certificate_code_user_id','wplms_get_user_id_from_certificate_code');
function wplms_get_user_id_from_certificate_code($code){
  $codes = explode('-',$code);
  if(isset($codes[2]) && is_numeric($codes[2])){
    $user_id = intval($codes[2]);
    return $user_id;
  }
}
add_filter('wplms_certificate_code_course_id','wplms_get_course_id_from_certificate_code');
function wplms_get_course_id_from_certificate_code($code){
  $codes = explode('-',$code);
  if(isset($codes[1]) && is_numeric($codes[1]) && get_post_type($codes[1]) == 'course'){
    $course_id = intval($codes[1]);
    return $course_id;
  }
}

/*==== PMPRO CONNECT ====*/

add_action('wplms_the_course_button','wplms_pmp_pro_connect',10,2);
function wplms_pmp_pro_connect($course_id,$user_id){
  if ( in_array( 'paid-memberships-pro/paid-memberships-pro.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) && is_user_logged_in()) {

     $membership_ids=vibe_sanitize(get_post_meta($course_id,'vibe_pmpro_membership',false));

     if(pmpro_hasMembershipLevel($membership_ids,$user_id) && isset($membership_ids) && count($membership_ids) >= 1){
      
        $coursetaken=get_user_meta($user_id,$course_id,true);
        if(!isset($coursetaken) || $coursetaken ==''){

            $duration=get_post_meta($course_id,'vibe_duration',true);
            $course_duration_parameter = apply_filters('vibe_course_duration_parameter',86400);
            $new_duration = time()+$course_duration_parameter*$duration;
            $new_duration = apply_filters('wplms_pmpro_course_check',$new_duration);
            if(update_user_meta($user_id,$course_id,$new_duration)){
              bp_course_update_user_course_status($user_id,$course_id,0); //since version 1.8.4
              $group_id=get_post_meta($course_id,'vibe_group',true);
              if(isset($group_id) && $group_id !=''){
                groups_join_group($group_id, $user_id );
              }
            }

        }
     }
  }
}



add_action('pmpro_after_change_membership_level','wplms_pmprostop_previous_courses',10,2);
function wplms_pmprostop_previous_courses($level_id, $user_id){
  global $pmpro_pages, $wpdb;

  if($level_id !=0)
    return;

  $my_started_courses = $wpdb->get_results($wpdb->prepare("SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = %d",$user_id), ARRAY_A);

  if(isset($my_started_courses) && is_array($my_started_courses) && count($my_started_courses)){
    foreach($my_started_courses as $my_course){
      if(get_post_type($my_course['post_id']) == 'course'){
             if(delete_post_meta($my_course['post_id'],$user_id)){
                delete_user_meta($user_id,$my_course['post_id']);
                delete_user_meta($user_id,'course_status'.$my_course['post_id']);
             }else{
              wp_die(__('SOME ISSUE OCCURED WHILE REMOVING STUDENT FROM COURSES','vibe'));
             }
      }    
    }
  }
  //disable this hook so we don't loop
  remove_action("pmpro_after_change_membership_level", "wplms_pmprostop_previous_courses", 10, 2);
      
  return;
}

add_filter('wplms_private_course_button','wplms_check_pmpro_button');
add_filter('wplms_private_course_button_label','wplms_check_pmpro_course_button');
function wplms_check_pmpro_button($link){
    $course_id = get_the_ID();
    if ( in_array( 'paid-memberships-pro/paid-memberships-pro.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )) {
      $membership_ids=vibe_sanitize(get_post_meta($course_id,'vibe_pmpro_membership',false));

      if(isset($membership_ids) && is_array($membership_ids) && count($membership_ids)){
         $pmpro_levels_page_id = get_option('pmpro_levels_page_id');
         $link = get_permalink($pmpro_levels_page_id);
      }
    }
    return $link;
}
function wplms_check_pmpro_course_button($label){
  $course_id = get_the_ID();
  if ( in_array( 'paid-memberships-pro/paid-memberships-pro.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )) {
    $membership_ids=vibe_sanitize(get_post_meta($course_id,'vibe_pmpro_membership',false));
    if(isset($membership_ids) && is_array($membership_ids) && count($membership_ids)){
      $label = apply_filters('wplms_take_this_course_button_label',__('TAKE THIS COURSE','vibe'),$course_id);
    }
  }
  return $label;
}
/*===== PMPRO END ====*/

function get_image_id($image_url) {
    global $wpdb;
    
    $attachment = $wpdb->get_var($wpdb->prepare("SELECT ID FROM {$wpdb->posts} WHERE guid='%s'",$image_url));
  if($attachment)
        return $attachment;
    else
        return false;
}




/*==== End Show Values ====*/


add_filter('widget_text', 'do_shortcode');


function custom_excerpt($chars=0, $id = NULL) {
	global $post;
        if(!isset($id)) $id=$post->ID;
	$text = get_post($id);
        
	if(strlen($text->post_excerpt) > 10)
            $text = $text->post_excerpt . " ";
        else
            $text = $text->post_content . " ";
        
	$text = strip_tags($text);
        $ellipsis = false;
        $text = strip_shortcodes($text);
	if( strlen($text) > $chars )
		$ellipsis = true;
  

	$text = substr($text,0,intval($chars));

	$text = substr($text,0,strrpos($text,' '));

	if( $ellipsis == true && $chars > 1)
		$text = $text . "...";
        
	return $text;
}



//Registeration and Activation refirect URL  

add_filter( 'registration_redirect' , 'vibe_registration_redirect' );
function vibe_registration_redirect() {
    $pageid=vibe_get_option('activation_redirect');
    return get_permalink($pageid);
}
add_action('bp_core_activated_user','vibe_redirect_after_registration');
function vibe_redirect_after_registration(){
    $pageid=vibe_get_option('activation_redirect');
    if(!empty($page_id)){
        wp_redirect( get_permalink($pageid) );
    }
}

/*==== End Redirection ===*/

if(!function_exists('pagination')){
function pagination($pages = '', $range = 4)
{  
     $showitems = ($range * 2)+1;  
 
     global $paged;
     if(empty($paged)) $paged = 1;
 
     if($pages == '')
     {
         global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {
             $pages = 1;
         }
     }   
 
     if(1 != $pages)
     {
         echo "<div class=\"pagination\"><span>".__('Page','vibe')." ".$paged." ".__('of','vibe')." ".$pages."</span>";
         if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>&laquo; ".__('First','vibe')."</a>";
         if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo; ".__('Previous','vibe')."</a>";
 
         for ($i=1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                 echo ($paged == $i)? "<span class=\"current\">".$i."</span>":"<a href='".get_pagenum_link($i)."' class=\"inactive\">".$i."</a>";
             }
         }
 
         if ($paged < $pages && $showitems < $pages) echo "<a href=\"".get_pagenum_link($paged + 1)."\">".__('Next','vibe')." &rsaquo;</a>";  
         if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>".__('Last','vibe')." &raquo;</a>";
         echo "</div>\n";
     }
}
}

if(!function_exists('get_current_post_type')){
function get_current_post_type() {
  global $post, $typenow, $current_screen;
  
  //lastly check the post_type querystring
  if( isset( $_REQUEST['post_type'] ) )
    return sanitize_key( $_REQUEST['post_type'] );
  
  elseif ( isset( $_REQUEST['post'] ) )
    return get_post_type($_REQUEST['post']);
  
  elseif ( $post && $post->post_type )
    return $post->post_type;
  
  elseif( $typenow )
    return $typenow;

  //check the global $current_screen object - set in sceen.php
  elseif( $current_screen && $current_screen->post_type )
    return $current_screen->post_type;

  //we do not know the post type!
  return 'post';
}
}

if(!function_exists('vibe_sanitize')){
  function vibe_sanitize($array){
    if(isset($array[0]) && is_array($array[0]))
      return $array[0];
  }
}

if(!function_exists('vibe_validate')){
  function vibe_validate($value){
    if(isset($value) && $value && $value !='H')
      return true;
    else
      return false;
  }
}


if(!function_exists('vibe_breadcrumbs')){
function vibe_breadcrumbs() {  

    global $post;
   
    /* === OPTIONS === */  
    $text['home']     = __('Home','vibe'); // text for the 'Home' link  
    $text['category'] = '%s'; // text for a category page  
    $text['search']   = '%s'; // text for a search results page  
    $text['tag']      = '%s'; // text for a tag page  
    $text['author']   = '%s'; // text for an author page  
    $text['404']      = 'Error 404'; // text for the 404 page  
  
    $showCurrent = apply_filters('vibe_breadcrumbs_show_title',1); // 1 - show current post/page title in breadcrumbs, 0 - don't show  
    $showOnHome  = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show  
    $delimiter   = ''; // delimiter between crumbs  
    $before      = '<li class="current"><span itemprop="title">'; // tag before the current crumb  
    $after       = '</span></li>'; // tag after the current crumb  
    /* === END OF OPTIONS === */  
  
    global $post;  
    $homeLink = home_url();  
    $linkBefore = '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">';  
    $linkAfter = '</li>';  
    $linkAttr = ' rel="v:url" property="v:title" itemprop="url"';  
    $link = $linkBefore . '<a' . $linkAttr . ' href="%1$s" ><span itemprop="name">%2$s</span></a>' . $linkAfter;  
  
    if (is_home() || is_front_page()) {  
  
        if ($showOnHome == 1) echo '<div id="crumbs"><a href="' . $homeLink . '">' . $text['home'] . '</a></div>';  
  
    } else {  
  
        echo '<ul class="breadcrumbs">' . sprintf($link, $homeLink, $text['home']) . $delimiter;  
  
        if ( is_category() ) {  
            $thisCat = get_category(get_query_var('cat'), false);  
            if ($thisCat->parent != 0) {  
                $cats = get_category_parents($thisCat->parent, TRUE, $delimiter);  
                $cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);  
                $cats = str_replace('</a>', '</a>' . $linkAfter, $cats);  
                echo $cats;  
            }  
            echo $before . sprintf($text['category'], single_cat_title('', false)) . $after;  
  
        } elseif ( is_search() ) {  
            echo $before . sprintf($text['search'], get_search_query()) . $after;  
  
        } elseif ( is_day() ) {  
            echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;  
            echo sprintf($link, get_month_link(get_the_time('Y'),get_the_time('m')), get_the_time('F')) . $delimiter;  
            echo $before . get_the_time('d') . $after;  
  
        } elseif ( is_month() ) {  
            echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;  
            echo $before . get_the_time('F') . $after;  
  
        } elseif ( is_year() ) {  
            echo $before . get_the_time('Y') . $after;  
  
        } elseif ( is_single() && !is_attachment() ) {  

            $post_type_var = get_post_type();

            switch($post_type_var){
              case 'post':
                  $cat = get_the_category(); 
                  if(isset($cat) && is_array($cat))
                    $cat = $cat[0];  


                  $cats = get_category_parents($cat, TRUE, $delimiter);  
                  if(isset($cats) && !is_object($cats)){
                  if ($showCurrent == 0) 
                    $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);  
                  
                  $cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);  

                  $cats = str_replace('</a>', '</a>' . $linkAfter, $cats);  
                  echo $cats;  
                  }
                  global $post;
                  if ($showCurrent == 1) echo $before . $post->post_title. $after; 
              break;
              case 'product':
                  $shop_page_url = get_permalink( woocommerce_get_page_id( 'shop' ) );
                  $post_type = get_post_type_object(get_post_type());  
                  printf($link, $homeLink . '/' . basename($shop_page_url) . '/', $post_type->labels->singular_name);  
                  global $post;
                  if ($showCurrent == 1) echo $delimiter . $before . $post->post_title . $after; 
              break;
              case 'course':
                  $post_type =  get_post_type_object(get_post_type()); 

                  $course_category = get_the_term_list($post->ID, 'course-cat', '', '', '' );  

                  $slug = $post_type->rewrite;  
                  if(isset($course_category)){

                  $course_category = str_replace('<a', $linkBefore . '<a' . $linkAttr, $course_category);                    
                  $course_category = str_replace('rel="tag">','rel="tag"><span itemprop="title">',$course_category);
                  $course_category = str_replace('</a>', '</span></a>' . $linkAfter, $course_category);  
                  printf($link, $homeLink . '/' . $slug['slug'] . '/', __('Course','vibe'));  //$post_type->labels->singular_name
                  echo apply_filters('wplms_breadcrumbs_course_category',$course_category);

                  }
                  global $post;
                  if ($showCurrent == 1) echo $delimiter . $before . $post->post_title . $after; 
              break;
              case 'forum':
                  $post_type = get_post_type_object(get_post_type());  
                  $slug = $post_type->rewrite;  
                  if($slug['slug'] == 'forums/forum')
                    $slug['slug'] = 'forums';
                  printf($link, $homeLink . '/' . $slug['slug'] . '/', $post_type->labels->singular_name);
                  global $post;  
                  if ($showCurrent == 1) echo $delimiter . $before . $post->post_title . $after; 
              break;
              default:
                  $post_type = get_post_type_object(get_post_type());  
                  $slug = $post_type->rewrite;  
                  printf($link, $homeLink . '/' . $slug['slug'] . '/', $post_type->labels->singular_name);
                  global $post;  
                  if ($showCurrent == 1) echo $delimiter . $before . $post->post_title . $after; 
              break;
            }
  
        } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {  
            $post_type = get_post_type_object(get_post_type());  

            echo $before . $post_type->labels->singular_name . $after;  
  
        } elseif ( is_attachment() ) {  
            $parent = get_post($post->post_parent);  
            $cat = get_the_category($parent->ID); 
            if(isset($cat[0])){
            $cat = $cat[0];  
            $cats = get_category_parents($cat, TRUE, $delimiter);  
            $cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);  
            $cats = str_replace('</a>', '</a>' . $linkAfter, $cats);  
            echo $cats;  
            }
            printf($link, get_permalink($parent), __('Attachment','vibe'));  
            global $post;
            if ($showCurrent == 1) echo $delimiter . $before . $post->post_title . $after;  
  
        } elseif ( is_page() && !$post->post_parent ) {  
            global $post;
            if ($showCurrent == 1) echo $before . $post->post_title . $after;  
  
        } elseif ( is_page() && $post->post_parent ) {  
            $parent_id  = $post->post_parent;  
            $breadcrumbs = array();  
            while ($parent_id) {  
                $page = get_page($parent_id);  
                $breadcrumbs[] = sprintf($link, get_permalink($page->ID), get_the_title($page->ID));  
                $parent_id  = $page->post_parent;  
            }  
            $breadcrumbs = array_reverse($breadcrumbs);  
            for ($i = 0; $i < count($breadcrumbs); $i++) {  
                echo $breadcrumbs[$i];  
                if ($i != count($breadcrumbs)-1) echo $delimiter;  
            }  
            global $post;
            if ($showCurrent == 1) echo $delimiter . $before .  $post->post_title . $after;  
  
        } elseif ( is_tag() ) {  
            echo $before . sprintf($text['tag'], single_tag_title('', false)) . $after;  
  
        } elseif ( is_author() ) {  
            global $author;  
            $userdata = get_userdata($author);  
            echo $before . sprintf($text['author'], $userdata->display_name) . $after;  
  
        } elseif ( is_404() ) {  
            echo $before . $text['404'] . $after;  
        }  
  
        if ( get_query_var('paged') ) {  
            if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';  
            echo '<li>'.__('Page','vibe') . ' ' . get_query_var('paged').'</li>';  
            if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';  
        }  
  
        echo '</ul>';  
  
    }  
} // end vibe_breadcrumbs()  
}

function get_all_testimonials(){
  $args=array(
    'post_type' => 'testimonials',
    'orderby'   => 'modified',
    'numberposts' => 999
    );

  $testimonials=get_posts($args);

  $testimonial_array=array();
  foreach($testimonials as $testimonial){
    $testimonial_array[$testimonial->ID]=$testimonial->post_title;
  }
  return $testimonial_array;
}

if(!function_exists('calculate_duration_time')){
  function calculate_duration_time($seconds) {
    switch($seconds){
        case 1: $return = __('Seconds','vibe');break;
        case 60: $return = __('Minutes','vibe');break;
        case 3600: $return = __('Hours','vibe');break;
        case 86400: $return = __('Days','vibe');break;
        case 604800: $return = __('Weeks','vibe');break;
        case 2592000: $return = __('Months','vibe');break;
        case 31104000: $return = __('Years','vibe');break;
        default:
        $return = apply_filters('vibe_calculation_duration_default',$return,$seconds);
        break;
    }
  return $return;
  } 
}

function vbp_current_user_notification_count() {
    if(function_exists('bp_notifications_get_notifications_for_user'))
      $notifications = bp_notifications_get_notifications_for_user(bp_loggedin_user_id(), 'object');
    $count = !empty($notifications) ? count($notifications) : 0;
   return $count;
}

// WOO COMMERCE  HANDLES
remove_filter( 'lostpassword_url',  'wc_lostpassword_url', 10, 0 );
remove_action( 'woocommerce_before_main_content','woocommerce_breadcrumb', 20, 0);
add_action('woocommerce_before_main_content','vibe_breadcrumbs',20,0);

remove_action('woocommerce_pagination', 'woocommerce_pagination', 10);
add_action( 'woocommerce_pagination', 'pagination', 10);


/**
* Set custom add to cart redirect
*/


add_action('woocommerce_init', 'vibe_woocommerce_direct_checkout');

if(!function_exists('vibe_woocommerce_direct_checkout')){
  function vibe_woocommerce_direct_checkout(){
    if(in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))){
        $check=vibe_get_option('direct_checkout');
        if(isset($check) && ($check == 1  || $check == '1')){
          update_option('woocommerce_cart_redirect_after_add', 'no');
          update_option('woocommerce_enable_ajax_add_to_cart', 'no');
          add_filter('add_to_cart_redirect', 'vcustom_add_to_cart_redirect');
        }
    }else
      return;
  }
}

function vcustom_add_to_cart_redirect() {
    return get_permalink(get_option('woocommerce_checkout_page_id'));
}


add_action('wplms_course_unit_meta','vibe_custom_print_button');
if(!function_exists('vibe_custom_print_button')){
    function vibe_custom_print_button(){
      $print_html='<a href="#" class="print_unit"><i class="icon-printer-1"></i></a>';
        echo apply_filters('wplms_unit_print_button',$print_html);  
      }
}


add_action('wplms_course_start_after_time','wplms_course_progressbar',1,2);
function wplms_course_progressbar($course_id,$unit_id){
    $user_id=get_current_user_id();
    $course_progressbar = vibe_get_option('course_progressbar');
    if(!isset($course_progressbar) || !$course_progressbar)
       return;

   $key = 'course_progressbar'.$course_id;
   $percentage = get_user_meta($user_id,$key,true);
   if(isset($percentage) && $percentage){

   }else{
    $units = bp_course_get_curriculum_units($course_id);
    $total_units = count($units);
    $key = array_search($unit_id,$units);
    $meta=get_user_meta($user_id,$unit_id,true);
    if(isset($meta) && $meta)
      $key++;

    if(!$total_units)$total_units=1;
    $percentage = round((($key/$total_units)*100),2); // Indexes are less than the count value
  }
    
    if($percentage > 100)
      $percentage= 100;

    $unit_increase = round(((1/$total_units)*100),2);
    if(!isset($key))
    echo "<script>
          jQuery(document).ready(function($){
            $('.course_progressbar').each(function(){
              var cookie_id = 'course_progress'+".$course_id.";
              ".
              (isset($percentage)?  // If key is 0 or not set remove cookie
                " 
                     $.cookie(cookie_id, ".$percentage.", { expires: 2, path: '/' });
                      ":"
              if($.cookie(cookie_id)!= null){
                $(this).attr('data-value',$.cookie(cookie_id));
                $(this).find('.bar').css('width',$.cookie(cookie_id)+'%');
                $(this).find('.bar span').text($.cookie(cookie_id)+'%');
              }"
              )."
            });
          });
          </script>";
    echo '<div class="progress course_progressbar" data-increase-unit="'.$unit_increase.'" data-value="'.$percentage.'">
             <div class="bar animate cssanim stretchRight load" style="width: '.$percentage.'%;"><span>'.$percentage.'%</span></div>
           </div>';

}

add_action('wp_ajax_record_course_progress','wplms_course_progress_record');
function wplms_course_progress_record(){
    $course_id = $_POST['course_id'];
    if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'security') || !is_numeric($course_id) ){
       _e('Security check Failed. Contact Administrator.','vibe');
       die();
    }
    $course_progress = $_POST['progress'];
    $user_id = get_current_user_id();
    $progress='progress'.$course_id;
    update_user_meta($user_id,$progress,$course_progress);
    die();
}



add_filter('wplms_course_product_id','vibe_course_max_students_check',10,2);
function vibe_course_max_students_check($pid,$course_id){

    $max_students=get_post_meta($course_id,'vibe_max_students',true);
      if(is_numeric($max_students) && $max_students < 9998){
        $number = bp_course_count_students_pursuing($course_id);
        $user_id = get_current_user_id();
        $check = bp_course_get_user_course_status($user_id,$course_id);
        if($number >= $max_students && !$check)
          return '?error=full';
    }

    $pre_course=get_post_meta($course_id,'vibe_pre_course',true);
    if(!empty($pre_course)){
      if(is_user_logged_in()){
        $user_id = get_current_user_id();
        if(is_numeric($pre_course)){
          $user_check = get_user_meta($user_id,'course_status'.$pre_course,true);
          if($user_check >=3){
            return $pid;
          }
        }elseif(is_array($pre_course)){
            foreach($pre_course as $course_id){
              $flag = 0;
              $user_check = get_user_meta($user_id,'course_status'.$course_id,true);
              if($user_check < 3){
                $flag = 1;
                break;
              }
            }
            if(empty($flag))
              return $pid;
        }
      }
      return '?error=precourse';
    }
    return $pid;
}


add_action('wplms_before_start_course','wplms_start_course_go_home');
function wplms_start_course_go_home(){
  if(!is_user_logged_in()){
    wp_redirect( vibe_site_url() );
    exit();  
  }
}

add_action('wplms_course_before_front_main','wplms_error_message_handle');
function wplms_error_message_handle(){
  global $post;

  if(isset($_REQUEST['error'])){ 
    $user_id = get_current_user_id();
  switch($_REQUEST['error']){
    case 'precourse':
      $pre=get_post_meta($post->ID,'vibe_pre_course',true);
      if(is_numeric($pre)){
        $user_check = get_user_meta($user_id,'course_status'.$pre_course,true);
        if($user_check < 3){
          echo '<div id="message" class="notice"><p>'.__('Requires completion of course : ','vibe').'<a href="'.get_permalink($pre).'">'.get_the_title($pre).'</a></p></div>';  
        }
      }elseif(is_array($pre)){
            foreach($pre as $course_id){
              $flag = 0;
              $user_check = get_user_meta($user_id,'course_status'.$course_id,true);
              if($user_check < 3){
                $flag = 1;
                $pre = $course_id;
                break;
              }
            }
            if(!empty($flag)){
              echo '<div id="message" class="notice"><p>'.__('Requires completion of course : ','vibe').'<a href="'.get_permalink($pre).'">'.get_the_title($pre).'</a></p></div>';
            }
        }
    break;
    case 'full':
      echo '<div id="message" class="notice"><p>'.__('All seats in course are full ','vibe').'</p></div>';
    break;
    case 'not-accessible':
      echo '<div id="message" class="notice"><p>'.__('Requested resource is not accessible ','vibe').'</p></div>';
    break;
    case 'login':
      $link_html = '<a href="'.wp_login_url( get_permalink() ).'" class="link"> '.__(' LOGIN','vibe').'</a> | <a href="'.wp_registration_url().'" class="link"> '.__(' REGISTER NOW','vibe').'</a>';
      $link_html =apply_filters('wplms_registeration_page',$link_html);
      echo '<div id="message" class="notice"><p>'.__('You must be logged in to take this course ','vibe').' &nbsp;&rarr;&nbsp;&nbsp;'.$link_html.'</p></div>';
    break;
    }
  }
}

add_filter('wplms_course_admin_bulk_actions_list','wplms_course_admin_bulk_actions_add_students_list');
function wplms_course_admin_bulk_actions_add_students_list($list){
  $instructor_add_students = vibe_get_option('instructor_add_students');
  if(isset($instructor_add_students) && $instructor_add_students || current_user_can('manage_options'))
    $list .= '<li><a href="#" class="expand_add_students tip" title="'.__('Add Students to Course','vibe').'"><i class="icon-users"></i></a></li>';

  return $list;
}

add_filter('wplms_course_admin_bulk_actions_list','wplms_course_admin_bulk_actions_assign_badges_certificates_list');
function wplms_course_admin_bulk_actions_assign_badges_certificates_list($list){
  $instructor_assign_badges = vibe_get_option('instructor_assign_badges');
  if(isset($instructor_assign_badges) && $instructor_assign_badges || current_user_can('manage_options'))
    $list .= '<li><a href="#" class="expand_assign_students tip" title="'.__('Assign Badges/Certificates to Students','vibe').'"><i class="icon-key-fill"></i></a></li>';

  return $list;
}

add_filter('wplms_course_admin_bulk_actions_list','wplms_course_admin_bulk_actions_extend_subscription');
function wplms_course_admin_bulk_actions_extend_subscription($list){
  $instructor_extend_subscription = vibe_get_option('instructor_extend_subscription');
  if(isset($instructor_extend_subscription) && $instructor_extend_subscription || current_user_can('manage_options'))
    $list .= '<li><a href="#" class="extend_subscription_students tip" title="'.__('Extend Subscription','vibe').'"><i class="icon-layers"></i></a></li>';

  return $list;
}

add_filter('wplms_course_admin_bulk_actions_list','wplms_course_admin_bulk_actions_change_course_status_students');
function wplms_course_admin_bulk_actions_change_course_status_students($list){
  $instructor_change_status = vibe_get_option('instructor_change_status');
  if(isset($instructor_change_status) && $instructor_change_status || current_user_can('manage_options'))
    $list .= '<li><a href="#" class="expand_change_status tip" title="'.__('Change Course Status','vibe').'"><i class="icon-set"></i></a></li>';

  return $list;
}

/*===  Course Retake ===*/
add_action('bp_before_course_home_content','wplms_check_course_retake');
function wplms_check_course_retake(){
    if ( !isset($_POST['security']))
      return;

    $user_id = get_current_user_id();
  
    if ( !wp_verify_nonce($_POST['security'],'retake'.$user_id) ){
        echo '<p class="error">'.__('Security check failed !','vibe').'</p>';
        return;
    }
    
    $course_id = get_the_ID();
    $status = bp_course_get_user_course_status($user_id,$course_id);

    if(isset($status) && is_numeric($status)){  // Necessary for continue course
      do_action('wplms_student_course_reset',$course_id,$user_id);
      bp_course_update_user_course_status($user_id,$course_id,0); // New function
      $course_curriculum=vibe_sanitize(get_post_meta($course_id,'vibe_course_curriculum',false));

      foreach($course_curriculum as $c){
        if(is_numeric($c)){
          delete_user_meta($user_id,$c);
          delete_post_meta($c,$user_id);
          if(get_post_type($c) == 'quiz'){

            $questions = vibe_sanitize(get_post_meta($c,'quiz_questions'.$user_id,false));
            
            if(!isset($questions) || !is_array($questions)) // Fallback for Older versions
              $questions = vibe_sanitize(get_post_meta($c,'vibe_quiz_questions',false));
            else
              delete_post_meta($c,'quiz_questions'.$user_id); // Re-capture new questions in quiz begining

            if(isset($questions) && is_array($questions) && is_Array($questions['ques']))
                foreach($questions['ques'] as $question){
                  global $wpdb;
                  if(isset($question) && $question !='' && is_numeric($question))
                  $wpdb->query($wpdb->prepare("UPDATE $wpdb->comments SET comment_approved='trash' WHERE comment_post_ID=%d AND user_id=%d",$question,$user_id));
                }
          }

        }
      }

      $user_badges=vibe_sanitize(get_user_meta($user_id,'badges',false));
      $user_certifications=vibe_sanitize(get_user_meta($user_id,'certificates',false));

      if(isset($user_badges) && is_Array($user_badges) && in_array($course_id,$user_badges)){
          $key=array_search($course_id,$user_badges);
          unset($user_badges[$key]);
          $user_badges = array_values($user_badges);
          update_user_meta($user_id,'badges',$user_badges);
      }
      if(isset($user_certifications) && is_Array($user_certifications) && in_array($course_id,$user_certifications)){
          $key=array_search($course_id,$user_certifications);
          unset($user_certifications[$key]);
          $user_certifications = array_values($user_certifications);
          update_user_meta($user_id,'certificates',$user_certifications);
      }
      /*==== End Fix ======*/
      do_action('wplms_course_retake',$course_id,$user_id);

      echo "<script>jQuery(document).ready(function($){ $.removeCookie('course_progress$course_id', { path: '/' });  });</script>";
  }else{
    echo '<p class="error">'.__('There was issue in retaking this course for the user. Please contact admin.','vibe').'</p>';
  }
}
/*===  Quiz Retake ===*/

add_action('wplms_before_quiz','wplms_check_quiz_retake');
function wplms_check_quiz_retake(){
    if(isset($_POST['initiate_retake']) && isset($_POST['security']) && is_user_logged_in()){
      $user_id = get_current_user_id();
        if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'retake'.$user_id) ){
            wp_die(__('Security check failed !','vibe'),__('Security Error','vibe'),array('back_link' => true));
        }
        if(function_exists('student_quiz_retake')){
            student_quiz_retake();
        }
    }
}

add_action('wplms_before_quiz','wplms_check_quiz_submission');
function wplms_check_quiz_submission(){

}


// Below function is used in multiple locations so keeping as it is
function wplms_get_course_unfinished_unit($course_id){
  $user_id = get_current_user_id();  

  if(isset($_COOKIE['course'])){
      $coursetaken=1;
  }else{
      $coursetaken=get_user_meta($user_id,$course_id,true);      
  }

  $course_curriculum=vibe_sanitize(get_post_meta($course_id,'vibe_course_curriculum',false));

  $key=0;
  $uid='';
  if(isset($coursetaken) && $coursetaken){
      if(isset($course_curriculum) && is_array($course_curriculum) && count($course_curriculum)){
        foreach($course_curriculum as $uid){
          if(is_numeric($uid)){
            $units[$key]=$uid;
            $unittaken=get_user_meta($user_id,$uid,true);
            if(!isset($unittaken) || !$unittaken){
              break;
            }
            $key++;
          }
        }
      }else{
          echo '<div class="error"><p>'.__('Course Curriculum Not Set','vibe').'</p></div>';
          return;
      }    
  }

$uid = apply_filters('wplms_get_course_unfinished_unit',$uid);
$key = apply_filters('wplms_get_course_unfinished_unit_key',$key,$uid,$course_id);


$flag=apply_filters('wplms_next_unit_access',true,$units[($key-1)]);
$drip_enable=get_post_meta($course_id,'vibe_course_drip',true);
$drip_duration_parameter = apply_filters('vibe_drip_duration_parameter',86400);

if(vibe_validate($drip_enable)){
    $drip_duration = get_post_meta($course_id,'vibe_course_drip_duration',true);
    
    if($key > 0){
       $pre_unit_time=get_post_meta($units[($key-1)],$user_id,true);

       if(isset($pre_unit_time) && $pre_unit_time){
        $value = $pre_unit_time + ($key)*$drip_duration*$drip_duration_parameter;
        $value = apply_filters('wplms_drip_value',$value,$units[($key-1)],$course_id,$units[$unitkey]);
         if( $value > time()){
                $flag=0;
            }
        }else{
            $flag=0;
        }  

  }
}  

  if(isset($uid) && $flag && isset($key)){// Should Always be set 
    if($key == 0){
      $unit_id =''; //Show course start if first unit has not been started
    }else{
      $unit_id=$uid; // Last un finished unit
    }
  }else{
     if(isset($key) && $key > 0){
       $unit_id=$units[($key-1)] ;
     }else{
      $unit_id = '' ;
     }
  } 

  return $unit_id;
}

/* ====== WOOCOMMERCE FIXES ===== */

add_filter('woocommerce_payment_complete_order_status', 'wplms_force_change_status_function',10,2);
function wplms_force_change_status_function($order_status,$order_id){
  $force_complete = vibe_get_option('force_complete');
  $flag=0;

  if(isset($force_complete) && $force_complete ){
    $order = new WC_Order($order_id);
    $items = $order->get_items();
    $user_id=$order->user_id;
    foreach($items as $item){
      $product_id = $item['product_id'];
      $is_virtual = get_post_meta( $product_id, '￼_virtual', true );
      if( $is_virtual == 'yes' ){
        $flag=1;
        break;
      }
    } 
    
    if($flag) 
     return 'completed';
  }


  return $order_status;
}

add_action('wplms_force_woocommerce_order_complete','wplms_force_paypal_orders_complete',1,1);
function wplms_force_paypal_orders_complete($order){
  $force_complete = vibe_get_option('force_complete');  
  if(isset($force_complete) && $force_complete == 2){
     $order->update_status('completed');
  }
}

add_filter( 'woocommerce_checkout_fields' , 'woo_remove_billing_checkout_fields');
function woo_remove_billing_checkout_fields( $fields ) {
  global $woocommerce;
    $remove_fields = vibe_get_option('remove_woo_fields');

    if(!isset($remove_fields) || !$remove_fields){
      return $fields;
    }
    
    $products = $woocommerce->cart->get_cart();

    if(woo_cart_has_virtual_product()) {
      
      unset($fields['billing']['billing_company']);
      unset($fields['billing']['billing_address_1']);
      unset($fields['billing']['billing_address_2']);
      unset($fields['billing']['billing_city']);
      unset($fields['billing']['billing_postcode']);
      unset($fields['billing']['billing_country']);
      unset($fields['billing']['billing_state']);
      unset($fields['billing']['billing_phone']);
      unset($fields['billing']['billing_address_2']);
      unset($fields['billing']['billing_postcode']);
      unset($fields['billing']['billing_company']);
      unset($fields['billing']['billing_city']);
    }
    
    return $fields;
}


function woo_cart_has_virtual_product() {
  
  global $woocommerce;
  $has_virtual_products = false;
  $virtual_products = 0;

  $products = $woocommerce->cart->get_cart();

  foreach( $products as $product ) {
    
    $product_id = $product['product_id'];
    $is_virtual = get_post_meta( $product_id, '_virtual', true );

    if( $is_virtual == 'yes' )
      $virtual_products += 1;
  }
  
  if( count($products) == $virtual_products )
    $has_virtual_products = true;
  
  return $has_virtual_products;
 
}
/*==== END WOOCOMMERCE FIXES =====*/

/* == ADD  REVIEW FORM ON COURSE FINISH ===*/
add_filter('wplms_course_finished','wplms_course_finished_course_review_form');
function wplms_course_finished_course_review_form($return){
  global $withcomments;
  $withcomments = true;
  ob_start();
  comments_template('/course-review.php',true);
  $return .= ob_get_contents();
  ob_end_clean();

  return $return;
}

add_action('woocommerce_thankyou','wplms_redirect_to_course',10,1);
function wplms_redirect_to_course($order_id){

    if(!class_exists('WC_Order'))
      return;

    $order = new WC_Order( $order_id );
    $items = $order->get_items();
    $order_courses = array();

    foreach($items as $item){
        $product_name = $item['name'];
        
        $product_id = apply_filters('bp_course_product_id',$item['product_id'],$item);

        $vcourses=vibe_sanitize(get_post_meta($item['product_id'],'vibe_courses',false));
        if(isset($vcourses) && is_array($vcourses) && count($vcourses) && $vcourses !=''){
            $order_courses[$item['product_id']]['courses']=$vcourses;

            $product_id = apply_filters('bp_course_product_id',$item['product_id'],$item);

            $product_id = $item['variation_id'];
            
            $subscribed=get_post_meta($product_id,'vibe_subscription',true);
            if(vibe_validate($subscribed)){ 
              $duration=get_post_meta($product_id,'vibe_duration',true);
              $product_duration_parameter = apply_filters('vibe_product_duration_parameter',86400);
              $date=tofriendlytime($duration*$product_duration_parameter);
              $order_courses[$item['product_id']]['subs']='<strong>'.__('COURSE SUBSCRIBED FOR ','vibe').' : <span>'.$date.'</span></strong>';
            }else{  
              $order_courses[$item['product_id']]['subs']= '<strong>'.__('SUBSCRIBED FOR FULL COURSE','vibe').'</strong>';
            }
        }
      }

      if(isset($order_courses) && is_array($order_courses) && count($order_courses)){
          echo '<h3 class="heading">'.__('Courses Subscribed','vibe').'</h3>
          <ul class="order_details">
            <li><a>'.__('COURSE','vibe').'</a>
            <strong>'.__('SUBSCRIPTION','vibe').'</strong></li>';

            if($order->status == 'completed' || $order->status == 'complete'){
              $ostatus=__('START COURSE','vibe');
            }else if($order->status == 'pending'){
              do_action('wplms_force_woocommerce_order_complete',$order);
              $ostatus =__('WAITING FOR ORDER CONFIRMATION TO START COURSE','vibe');
            }else{
              $ostatus=__('WAITING FOR ADMIN APPROVAL','vibe');
            }

            foreach($order_courses as $order_course){
                foreach($order_course['courses'] as $course){
                  echo '<li>
                        <a class="course_name">'.get_the_title($course).'</a>
                        <a href="'.get_permalink($course).'"  class="button">
                        '.$ostatus.'</a>'.$order_course['subs'].'
                        </li>'; 
                }
            }
          echo '</ul>';

           if(count($order_courses) == 1 && count($order_courses[$product_id]['courses']) == 1 && ($order->status == 'completed' || $order->status == 'complete')){
            $thankyou_redirect=vibe_get_option('thankyou_redirect');
            if(isset($thankyou_redirect) && $thankyou_redirect){
                echo '<script>
                  jQuery(location).attr("href","'.get_permalink($course).'");
                </script>';
              }
            } 
      }
}
/* ===== INSTRUCTOR PRIVACY ====== */
add_filter('wplms_frontend_cpt_query','wplms_instructor_privacy_filter');
function wplms_instructor_privacy_filter($args=array()){
    $instructor_privacy = vibe_get_option('instructor_content_privacy');
    if(isset($instructor_privacy) && $instructor_privacy && !current_user_can('manage_options')){
        global $current_user;
        get_currentuserinfo();
        $args['author'] = $current_user->ID;
    }
    return $args;
}

add_filter('wplms_backend_cpt_query','wplms_instructor_privacy_filter2'); // Modified to protect Product association
function wplms_instructor_privacy_filter2($args=array()){
    $instructor_privacy = vibe_get_option('instructor_content_privacy');
    if(isset($instructor_privacy) && $instructor_privacy && !current_user_can('manage_options')){
        global $current_user;
        get_currentuserinfo();
        if($args['post_type'] != 'product')
          $args['author'] = $current_user->ID;
    }
    return $args;
}

/*===== Instructing Courses ======*/

add_action('init','wplms_instructing_courses_endpoint');
function wplms_instructing_courses_endpoint(){
  $instructing_courses=apply_filters('wplms_instructing_courses_endpoint','instructing-courses');
  add_rewrite_endpoint($instructing_courses, EP_AUTHORS);
}

add_filter( 'template_include', 'wplms_check_instructing_courses_endpoint');
function wplms_check_instructing_courses_endpoint($template){
  if(!is_author())
    return $template;
  global $wp_query;
  $instructing_courses=apply_filters('wplms_instructing_courses_endpoint','instructing-courses');
  if ( !isset( $wp_query->query_vars[$instructing_courses] ) ){
     $wp_query->set( 'post_type',  array('post') );
     return VIBE_PATH.'/author.php';
  }else{
    $wp_query->set( 'post_type',  array('course') );
    return VIBE_PATH.'/author-course.php';
  }
}


add_action('pre_get_posts', 'wplms_authors_page_query');
function wplms_authors_page_query($wp_query){ //Authors Page
  if(!$wp_query->is_author)
    return;

     if (! is_admin() ){
        if ( $wp_query->is_author() && $wp_query->is_main_query()){
          $instructing_courses=apply_filters('wplms_instructing_courses_endpoint','instructing-courses');
          global $paged;$paged = get_query_var('paged') ? get_query_var('paged') : 1;
             if ( !isset( $wp_query->query_vars[$instructing_courses] ) ){ 
                $author_cpts = apply_filters('wplms_author_cpts',array('post'));
                $wp_query->set( 'post_type', $author_cpts);
              }else{
                 $wp_query->set( 'post_type',  array('course') ); 
                 $check_paged = $wp_query->query_vars[$instructing_courses];
                 if(isset($paged)){
                    $wp_query->set( 'paged', $paged ); 
                 }if(strpos($check_paged,'age/')){
                    $cp=explode('/',$check_paged);
                    if(is_array($cp) && is_numeric($cp[1]))
                      $wp_query->set( 'paged', $cp[1] ); 
                 }
              }
        }
     }
}

/* ===== Linkage ====== */

add_filter('wplms_frontend_cpt_query','wplms_linkage_filter');
add_filter('wplms_backend_cpt_query','wplms_linkage_filter');
function wplms_linkage_filter($args=array()){
  global $post;

  $course_id=$post->ID;
  if(isset($_GET['action']) && is_numeric($_GET['action'])){
      $course_id=$_GET['action'];
  }
  $linkage = vibe_get_option('linkage');
  if(isset($linkage) && $linkage){
    $terms = get_the_terms($course_id,'linkage');
    if ( $terms && ! is_wp_error( $terms ) ){
      $links = array();
      if(is_array($terms)){
        foreach ( $terms as $term ) {
          $links[] = $term->slug;
        }
        $args['tax_query']=array(
                              'relation' => 'OR',
                              array(
                                'taxonomy' => 'linkage',
                                'field' => 'slug',
                                'terms' => $links
                              )
                            );
      }
    }
  }

  return $args;
}


/* ===== INSTRUCTOR PRIVACY IN UPLOADED MEDIA FILES ====== */

add_filter( 'posts_where', 'wplms_attachments_wpquery_where');
function wplms_attachments_wpquery_where( $where ){
  $instructor_privacy = vibe_get_option('instructor_content_privacy');
    if(isset($instructor_privacy) && $instructor_privacy && !current_user_can('manage_options')){
      
      if( is_user_logged_in() && current_user_can('edit_posts')){
        global $current_user;
        if( isset( $_POST['action'] ) ){
          if( $_POST['action'] == 'query-attachments' ){
            $where .= ' AND post_author='.$current_user->data->ID;
          }
        }
      }
    }
  return $where;
}




// DYNAMIC QUIZ QUESTION SELECTION

add_action('wplms_before_quiz_begining','wplms_dynamic_quiz_select_questions',10,1);
function wplms_dynamic_quiz_select_questions($quiz_id=NULL){
  $user_id= get_current_user_id();
  if(!is_numeric($user_id))
    return;

  if(!isset($quiz_id)){
    global $post;  
    $quiz_id = $post->ID;
  }

  $quiz_created = vibe_sanitize(get_post_meta($quiz_id,'quiz_questions'.$user_id,false));
  if(isset($quiz_created) && $quiz_created && is_array($quiz_created) && count($quiz_created)){
     return;
  }

  $quiz_dynamic = get_post_meta($quiz_id,'vibe_quiz_dynamic',true);
  $quiz_questions = array('ques'=>array(),'marks'=>array()); 
  if(vibe_validate($quiz_dynamic)){ 

      $tags = vibe_sanitize(get_post_meta($quiz_id,'vibe_quiz_tags',false));
      $number = get_post_meta($quiz_id,'vibe_quiz_number_questions',true);
      
      if(!isset($number) || !is_numeric($number)) $number=0;

      $marks = get_post_meta($quiz_id,'vibe_quiz_marks_per_question',true);
      $args = array(
                'post_type' => 'question',
                'orderby' => 'rand', 
                'posts_per_page' => $number,
                'tax_query' => array(
                  array(
                    'taxonomy' => 'question-tag',
                    'field' => 'id',
                    'terms' => $tags
                  ),
                )
              );
      $the_query = new WP_Query( $args );
      while ( $the_query->have_posts() ) {
        $the_query->the_post();
        $quiz_questions['ques'][]=get_the_ID();
        $quiz_questions['marks'][]=$marks;
      }
      wp_reset_postdata();
  }else{
    $quiz_questions = vibe_sanitize(get_post_meta($quiz_id,'vibe_quiz_questions',false));
    $randomize=get_post_meta($quiz_id,'vibe_quiz_random',true);
    if(isset($randomize) && $randomize != 'H'){
      if(isset($quiz_questions['ques']) && is_array($quiz_questions['ques']) && count($quiz_questions['ques']) > 1){
          $randomized_keys = array_rand($quiz_questions['ques'], count($quiz_questions['ques'])); 
          shuffle($randomized_keys);
           foreach($randomized_keys as $current_key) { 
               $rand_quiz_questions['ques'][] = $quiz_questions['ques'][$current_key];
               $rand_quiz_questions['marks'][] = $quiz_questions['marks'][$current_key]; 
           }
        }
       $quiz_questions = $rand_quiz_questions;   
      }
  }
  update_post_meta($quiz_id,'quiz_questions'.$user_id,$quiz_questions);
}

add_filter('vibe_course_duration_parameter','wplms_custom_course_duration_parameter');
function wplms_custom_course_duration_parameter($course_duration_parameter){
  // Course duration for subscription based
  if(function_exists('vibe_get_option')){
      $duration_parameter = vibe_get_option('course_duration_display_parameter');
      if(isset($duration_parameter) && is_numeric($duration_parameter) && $duration_parameter)
          $course_duration_parameter = $duration_parameter;
  }
  return $course_duration_parameter;  
}

/* === HIDE COURSES === */

add_filter('wplms_carousel_course_filters','wplms_exlude_courses_directroy');
add_filter('wplms_grid_course_filters','wplms_exlude_courses_directroy');
add_filter('vibe_editor_filterable_type','wplms_exlude_courses_directroy');
add_filter('bp_course_wplms_filters','wplms_exlude_courses_directroy');

function wplms_exlude_courses_directroy($args){
  if($args['post_type'] == 'course'){
    $user_id=get_current_user_id();
    if(isset($args['meta_query']) && is_array($args['meta_query']) && is_user_logged_in()){
       foreach($args['meta_query'] as $query){
         if(isset($query) && is_array($query)){
            if($query['key'] == $user_id){
              return $args;
            }
         }
       }
    }
    $excluded_courses=vibe_get_option('hide_courses');
      if(isset($excluded_courses) && is_array($excluded_courses) && !isset($args['author'])){
        $args['post__not_in'] = $excluded_courses;
      } 
  }
  return $args;    
}

add_filter('pre_get_posts','wplms_exlude_courses_from_directroy');
function wplms_exlude_courses_from_directroy($query) {
  if ( !$query->is_admin && $query->is_main_query() && $query->tax_query) {
    $excluded_courses=vibe_get_option('hide_courses');
    $query->set('post__not_in', $excluded_courses );
  }
  return $query;
}
add_filter('pre_get_posts','wplms_exclude_courses_authors');
function wplms_exclude_courses_authors($query){
  if ( !$query->is_admin && $query->is_author && $query->is_main_query()) {
    $excluded_courses=vibe_get_option('hide_courses');
    $query->set('post__not_in', $excluded_courses );
  }
  return $query;
}
/* ==== DOWNLOAD STATS ==== */
  
add_action('wplms_course_stats_panel','wplms_course_stats_download_options',10,1);
function wplms_course_stats_download_options($course_id){
    echo '<h3 class="heading" id="download_stats_options">'.__('Download Stats','vibe').'<i class="icon-download-3 right"></i></h3>';
    echo '<div class="select_download_options">
    <ul>';
    $stats_array = apply_filters('wplms_course_stats_list',array(
      'stats_student_start_date' => __('Date (Joining)','vibe'),
      'stats_student_completion_date' => __('Date (Finish)','vibe'),
      'stats_student_id' => __('ID','vibe'),
      'stats_student_name' => __('Student Name','vibe'),
      'stats_student_unit_status' => __('Units Status','vibe'),
      'stats_student_quiz_score' => __('Quiz scores','vibe'),
      'stats_student_badge' => __('Badge','vibe'),
      'stats_student_certificate' => __('Certificate','vibe'),
      'stats_student_marks' => __('Course Marks','vibe')
      ));
    foreach($stats_array as $key => $label){
      echo '<li><input type="checkbox" id="'.$key.'" class="field" value="1" /><label for="'.$key.'">'.$label.'</label></li>';
    }
    
    echo '</ul><br class="clear" /><select id="stats_students">
    <option value="all_students">'.__('All students','vibe').'</option>
    <option value="finished_students">'.__('Students who finished the course','vibe').'</option>
    <option value="pursuing_students">'.__('Students pursuing the course','vibe').'</option>
    <option value="badge_students">'.__('Students who got a badge in the course','vibe').'</option>
    <option value="certificate_students">'.__('Students who got a certificate in the course','vibe').'</option>
    </select>';
    wp_nonce_field('security','stats_security');
    echo '<a class="button full" id="download_stats" data-course="'.$course_id.'"><i class="icon-download-3"></i> '.__('Process Stats','vibe').'</a>
    </div>';
}

add_action('wplms_question_after_content','wplms_front_end_quiz_stats_hook');
add_action('wplms_assignment_after_content','wplms_front_end_quiz_stats_hook');
add_action('wplms_front_end_quiz_controls','wplms_front_end_quiz_stats_hook');
function wplms_front_end_quiz_stats_hook(){
  global $post;
  $id = $post->ID;
  $user_id = get_current_user_id();
  $check = vibe_get_option('stats_visibility');
  $flag=0;
  if(isset($check)){
    switch($check){
      case '1':
      if(!is_user_logged_in())
        $flag= 1;
      break;
      case '2':
         if(!current_user_can('edit_posts'))
          $flag=1;
      break;
      case '3':
        $instructors = apply_filters('wplms_course_instructors',get_post_field('post_author',$id,'raw'),$id);
        if((!is_array($instructors) || !in_array($user_id,$instructors)) && !current_user_can( 'manage_options' ))
          $flag=1;
      break;
    }
  }

  if($flag)
    return;

  echo '
    <ul class="data_stats" data-type="'.$post->post_type.'" data-id="'.$id.'">
      <li id="desc" class="active"><i class="icon-clipboard-1"></i></li>
      <li id="stats"><i class="icon-graph"></i></li>
    </ul>
  ';

  

  
}

/**
   *
   * @package WPLMS Course Status Functions
   * @since 1.8.4
   */
  if(!function_exists('bp_course_get_user_course_status')){
    function bp_course_get_user_course_status($user_id,$course_id){
      // NEW COURSE STATUSES
      // 1 : START COURSE
      // 2 : CONTINUE COURSE
      // 3 : FINISH COURSE : COURSE UNDER EVALUATION
      // 4 : COURSE EVALUATED
      $course_status = get_user_meta($user_id,'course_status'.$course_id,true);
      if(!isset($course_status) || !is_numeric($course_status)){
        $course_status=get_post_meta($course_id,$user_id,true); 
        if(is_numeric($course_status) && $course_status){
          $course_status++;
          if($course_status > 3)
            $course_status = 4;
          $course_status = apply_filters('wplms_course_status',$course_status);
          update_user_meta($user_id,'course_status'.$course_id,$course_status);
        }
      }
      return $course_status;
    }
    function bp_course_update_user_course_status($user_id,$course_id,$status){
      // NEW COURSE STATUSES
      // 1 : START COURSE
      // 2 : CONTINUE COURSE
      // 3 : FINISH COURSE : COURSE UNDER EVALUATION
      // 4 : COURSE EVALUATED
      update_post_meta($course_id,$user_id,$status); // Maintaining OLD COURSE STATUSES
      $status++;
      $status = apply_filters('wplms_course_status',$status);
      update_user_meta($user_id,'course_status'.$course_id,$status);
    }
  }
  /*========*/

  add_action('wp','wplms_bp_dtheme_setup');
  function wplms_bp_dtheme_setup(){
      if ( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
        if(function_exists('bp_is_active')){
          // Register buttons for the relevant component templates
          // Friends button
          if ( bp_is_active( 'friends' ) )
            add_action( 'bp_member_header_actions',    'bp_add_friend_button',           5 );

          // Activity button
          if ( bp_is_active( 'activity' ) && bp_activity_do_mentions() )
            add_action( 'bp_member_header_actions',    'bp_send_public_message_button',  20 );

          // Messages button
          if ( bp_is_active( 'messages' ) )
            add_action( 'bp_member_header_actions',    'bp_send_private_message_button', 20 );

          // Group buttons
          if ( bp_is_active( 'groups' ) ) {
            add_action( 'bp_group_header_actions',     'bp_group_join_button',           5 );
            add_action( 'bp_group_header_actions',     'bp_group_new_topic_button',      20 );
            add_action( 'bp_directory_groups_actions', 'bp_group_join_button' );
          }

          // Blog button
          if ( bp_is_active( 'blogs' ) )
            add_action( 'bp_directory_blogs_actions',  'bp_blogs_visit_blog_button' );
          
        }
    }
  }
  add_filter('bp_get_add_friend_button','wplms_bp_get_add_friend_button');
  function wplms_bp_get_add_friend_button($args){
    if(bp_is_member())
    $args['link_text'] = '<i class="icon-user"></i>';
    return $args;
  } 
  add_filter('bp_get_send_public_message_button','wplms_bp_get_send_public_message_button');
  function wplms_bp_get_send_public_message_button($args){
    if(bp_is_member())
    $args['link_text'] = '<i class="icon-email"></i>';
    return $args;
  }
  add_filter('bp_get_send_message_button_args','wplms_bp_get_send_private_message_button');
  function wplms_bp_get_send_private_message_button($args){
    if(bp_is_member())
    $args['link_text'] = '<i class="icon-letter-mail-1"></i>';
    return $args;
  }

add_filter( 'init','wplms_yoast_seo_title_fix',20);
function wplms_yoast_seo_title_fix() {
    if(class_exists('WPSEO_Frontend')){
        add_filter( 'wp_title', array( WPSEO_Frontend::get_instance(), 'title' ), 25, 3 );
    }
}

// Module Tag Meta data

function user_unit_join_module_tag($join) {
    global $wp_query, $wpdb;
    if (!empty($wp_query->query_vars['taxonomy']) && $wp_query->query_vars['taxonomy'] == 'module-tag') {
        $join .= "LEFT JOIN $wpdb->usermeta ON $wpdb->posts.ID = $wpdb->usermeta.meta_key ";
    }

    return $join;
}
function user_unit_where_module_tag($where){
  global $wp_query, $wpdb;
    $user_id = get_current_user_id();
    if (!empty($wp_query->query_vars['taxonomy']) && $wp_query->query_vars['taxonomy'] == 'module-tag') {
        $where .= "AND $wpdb->usermeta.user_id = $user_id";
    }
  return $where;
}
add_filter('posts_join', 'user_unit_join_module_tag');
add_filter('posts_where', 'user_unit_where_module_tag');
