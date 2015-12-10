<?php
/**
 * Register important functions for WPLMS
 *
 * @author      VibeThemes
 * @category    Admin
 * @package     Initialization
 * @version     2.0
 */

if ( ! isset( $content_width ) ) $content_width = 1170;


class WPLMS_Register{

    public static $instance;
    
    public static function init(){

        if ( is_null( self::$instance ) )
            self::$instance = new WPLMS_Register();

        return self::$instance;
    }

    private function __construct(){

        //Head Essentials
        add_action('wp_enqueue_scripts',array($this,'vibe_header_essentials'));

        // Image Sizes
        add_action( 'init', array($this,'add_image_sizes' ));
        add_filter( 'image_size_names_choose',array($this, 'wplms_custom_image_sizes' ));

        //User Roles
        add_action('init',array($this,'vibe_user_roles'));
        add_action( 'admin_init',array($this,'add_theme_caps'));

        //Admin bar
        add_action('after_setup_theme',array($this,'remove_admin_bar'));

        //Remove WooCommerce Styling
        add_filter( 'woocommerce_enqueue_styles', '__return_false' );

        // Admin Styles
        add_action("admin_enqueue_scripts",array($this,"wplms_enqueue_admin"));
        // Styles and Scripts
        add_action('wp_enqueue_scripts',array($this,'wplms_enqueue_head'));
        add_action('wp_enqueue_scripts',array($this,'wplms_force_enqueue_head'));
        //ENQUEUE SCRIPTS TO FOOTER
        add_action('wp_footer',array($this,'wplms_enqueue_footer'));

        //Theme Custmoizer  
        add_action("wp_head","print_customizer_style",99);
         
        //Register Sidebars
        add_action('widgets_init',array($this,'wplms_register_sidebars')); 
        
        // Re-set Google Fonts
        add_action( 'admin_init',array($this,'storegoogle_webfonts' ));
        //Set Vibe Menus
        add_action( 'init',array($this,'register_vibe_menus' ));
    }

    /*============================================*/
    /*=====  RESPONSIVE | FAVICON | HTML5  =======*/
    /*============================================*/
    function vibe_header_essentials(){
        $favicon = vibe_get_option('favicon');
        if(!isset($favicon))
            $favicon = VIBE_URL.'/images/favicon.png';

        $credits = vibe_get_option('credits');
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">
                <meta name="author" content="'.(isset($credits)?$credits:'vibethemes').'">
                <link rel="shortcut icon" href="'.$favicon.'" />
                <link rel="icon" type="image/png" href="'.$favicon.'">
                <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
                <!--[if lt IE 9]>
                  <script src="'.VIBE_URL.'/js/html5shiv.js"></script>
                  <script src="'.VIBE_URL.'/js/respond.min.js"></script>
                <![endif]-->';

    }
    /*============================================*/
    /*=====  REGISTER CUSTOM IMAGE SIZES  ========*/
    /*============================================*/
    function add_image_sizes() {
      add_image_size('mini', 120, 999);
      add_image_size('small', 310, 9999);
      add_image_size('medium', 460, 9999);
      add_image_size('big', 768, 9999);
    }

    function wplms_custom_image_sizes( $sizes ) {
        $custom_sizes = array(
            'big' => 'Big Size',
            'small' => 'Small Size',
            'mini' => 'Extra small/Mini'
        );
        return array_merge( $sizes, $custom_sizes );
    }
    /*============================================*/
    /*===========  REMGISTER CUSTOM USER ROLES  ============*/
    /*============================================*/
    function vibe_user_roles(){
      $teacher_capability=array(
          'delete_posts'=> true,
          'delete_published_posts'=> true,
          'edit_posts'=> true,
          'manage_categories' => true,
          'edit_published_posts'=> true,
          'publish_posts'=> true,
          'read' => true,
          'upload_files'=> true,
          'unfiltered_html'=> true,
          'level_1' => true
          );
      $student_capability=array(
          'read'
          );
      
          add_role( 'student', __('Student','vibe'), $student_capability );
          add_role( 'instructor', __('Instructor','vibe'),$teacher_capability);      
    }
    function add_theme_caps() {
        // gets the author role
        $role = get_role( 'instructor' );
        $role->add_cap( 'unfiltered_html' ); 
    }
    /*============================================*/
    /*===========  REMOVE DEFAULT BUDDYPRESS ADMIN BAR  ============*/
    /*============================================*/
    function remove_admin_bar() {
        if (!current_user_can('edit_posts')) {
          show_admin_bar(false);
        }
    }
    /*============================================*/
    /*=========  REGISTER ADMIN SCRIPTS  =========*/
    /*============================================*/
    function wplms_enqueue_admin(){
        wp_enqueue_style( 'admin-css', VIBE_URL .'/css/admin.css' );
        wp_enqueue_script( 'admin-js', VIBE_URL .'/js/vibe_admin.js');
    }
    /*============================================*/
    /*========  REGISTER FRONT SCRIPTS  ==========*/
    /*============================================*/
    //ENQUEUE SCRIPTS TO HEAD
    function wplms_enqueue_head() {
        global $vibe_options;
        
        if( ! is_admin() ){

         $protocol = is_ssl() ? 'https' : 'http';
         /*=== Enqueing Google Web Fonts =====*/
         $font_string='';
         $google_fonts=vibe_get_option('google_fonts');
         if(isset($google_fonts) && is_array($google_fonts)){
            $font_weights = array();
            $font_subsets = array();
            foreach($google_fonts as $font){

              $font_var = explode('-',$font);

              if(is_array($font_weights[$font_var[0]])){
                if(!in_array($font_var[1],$font_weights[$font_var[0]]))
                  $font_weights[$font_var[0]][] = $font_var[1];
              }else{
                $font_weights[$font_var[0]] = array($font_var[1]);
              }

              if(is_array($font_subsets[$font_var[0]])){
                if(!in_array($font_var[2],$font_subsets[$font_var[0]]))
                $font_subsets[$font_va[0]][]=$font_var[2];
              }else{
                $font_subsets[$font_va[0]]= array($font_var[2]);
              }

            }
            foreach($font_weights as $font_name => $font_weight){
              $strings[$font_name] = implode(',',$font_weight);
            }
            foreach ($font_subsets as $font_name => $font_subset) {
               $strings[$font_name] .='&subset='. implode(',',$font_subset);
            }
            if(is_array($strings)){
              foreach($strings as $key => $str){
                if($key){
                 $key = str_replace(' ','+',$key);
                 $font_string .= $key.':'.$str.'|';
                }
              }
            }
            
            $font_string .='Oswald:600'; // Used for price display, hard coded in the Theme
            $query_args = apply_filters('vibe_font_query_args',array(
            'family' => $font_string
            ));
            wp_enqueue_style('google-webfonts',
            esc_url(add_query_arg($query_args, "$protocol://fonts.googleapis.com/css" )),
            array(), null);

         } // End Google Fonts
        
          wp_enqueue_style('twitter_bootstrap', VIBE_URL.'/css/bootstrap.css');
           //wp_enqueue_style( 'fonticons-css', VIBE_URL .'/css/fonticons.css' ); //Added in Bootstrap
           //wp_enqueue_style( 'animation-css', VIBE_URL .'/css/animate.css' );//Added in Bootstrap
           //wp_enqueue_style( 'progress-css', VIBE_URL .'/css/nprogress.css' );//Added in Bootstrap
          wp_enqueue_style( 'search-css', VIBE_URL .'/css/chosen.css' );
          if(function_exists('bp_is_active')){
            wp_enqueue_style( 'buddypress-css', VIBE_URL .'/css/buddypress.css', array(),'0.2' );
          }
          if ( in_array( 'bbpress/bbpress.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )){
            wp_enqueue_style( 'bbpress-css', VIBE_URL .'/css/bbpress.css' );
          }
          wp_enqueue_style( 'style-css', VIBE_URL .'/css/style.css',array(),'0.1' );
         
         $layout=vibe_get_option('layout');
         if(isset($layout) && $layout){
            wp_enqueue_style( 'boxed-style-css', VIBE_URL .'/css/boxed.css' ); 
         }

         if ( is_rtl() ){
            wp_enqueue_style( 'bootstrap-rtl-css', $protocol.'://cdnjs.cloudflare.com/ajax/libs/bootstrap-rtl/3.1.1/css/bootstrap-rtl.min.css' );
            wp_enqueue_style( 'rtl-css', VIBE_URL .'/css/rtl.css' );
          }

         if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {     
           wp_deregister_style( 'woocommerce_chosen_styles' );
           wp_enqueue_style( 'woocommerce-css', VIBE_URL .'/css/woocommerce.css' );
          }
         
          if ( in_array( 'sfwd-lms/sfwd_lms.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )){
            wp_enqueue_style( 'learndash-css', VIBE_URL .'/css/learndash.css' );
          }

          wp_enqueue_style('theme-css', get_stylesheet_uri(), 'twitter_bootstrap');
          wp_enqueue_script( 'jquery' );
          wp_enqueue_script( 'bootstrap', VIBE_URL.'/js/bootstrap.min.js');
          wp_enqueue_script( 'nprogress-js', VIBE_URL.'/js/nprogress.js');
          echo '<script type="javascript/text">
                NProgress.start();
             </script>';  
        }
    } 
    function wplms_enqueue_footer() {
        if(!is_admin()){ 
          wp_enqueue_script( 'modernizr', VIBE_URL.'/js/modernizr.custom.js');
          wp_enqueue_script( 'flexslider-js', VIBE_URL.'/js/jquery.flexslider-min.js');
          wp_enqueue_script( 'chosen', VIBE_URL.'/js/chosen.jquery.js');
          wp_enqueue_script( 'sidebareffects', VIBE_URL.'/js/sidebarEffects.js');
          //wp_enqueue_script( 'jquery-cookie', VIBE_URL.'/js/jquery.cookie.js'); // Added in BuddyPress
          wp_enqueue_script( 'knob-js', VIBE_URL .'/js/jquery.knob.js' ); 
          
          if(function_exists('bp_is_active')){
            wp_enqueue_script( 'buddypress-js', VIBE_URL .'/js/buddypress.js',array('jquery'),'0.1');
          }

          $params = array(
              'accepted'            => __( 'Accepted', 'vibe' ),
              'close'               => __( 'Close', 'vibe' ),
              'comments'            => __( 'comments', 'vibe' ),
              'leave_group_confirm' => __( 'Are you sure you want to leave this group?', 'vibe' ),
              'mark_as_fav'          => __( 'Favorite', 'vibe' ),
              'my_favs'             => __( 'My Favorites', 'vibe' ),
              'rejected'            => __( 'Rejected', 'vibe' ),
              'remove_fav'          => __( 'Remove Favorite', 'vibe' ),
              'show_all'            => __( 'Show all', 'vibe' ),
              'show_all_comments'   => __( 'Show all comments for this thread', 'vibe' ),
              'show_x_comments'     => __( 'Show all %d comments', 'vibe' ),
              'unsaved_changes'     => __( 'Your profile has unsaved changes. If you leave the page, the changes will be lost.', 'vibe' ),
              'view'                => __( 'View', 'vibe' ),
              'too_short'           => __( 'Too short', 'vibe' ),
              'weak'                => __( 'Weak', 'vibe' ),
              'good'                => __( 'Good', 'vibe' ),
              'strong'              => __( 'Strong', 'vibe' ),
          );
          // localise
          wp_localize_script( 'buddypress-js', 'BP_DTheme', $params );
          wp_enqueue_script( 'custom', VIBE_URL.'/js/custom.js');
          $translation_array = array( 
            'wplms_woocommerce_validate' => __( 'Please fill in all the required fields (indicated by *)','vibe' )
          );
          wp_localize_script( 'custom', 'wplms_strings', $translation_array );
        }
    } 
    function wplms_force_enqueue_head(){
      $page_id = vibe_get_option('take_course_page');
      if(is_page($page_id) || is_singular('quiz')){
        wp_enqueue_style( 'wp-mediaelement' );
        wp_enqueue_script( 'wp-mediaelement' );
        if(class_exists('Vc_Base')){
          Vc_base::frontJsRegister();
        }
      }
    }

    /*============================================*/
    /*===========  REGISTER MENUS  ============*/
    /*============================================*/
    function register_vibe_menus() {
      register_nav_menus(
          array(
              'top-menu' => __( 'Top Menu','vibe' ),
              'main-menu' => __( 'Main Menu','vibe' ),
              'mobile-menu' => __( 'Mobile Menu','vibe' ),
              'footer-menu' => __( 'Footer Menu','vibe' )
             )
            );
      }

      /*============================================*/
      /*===========  REGISTER SIDEBARS  ============*/
      /*============================================*/
      function wplms_register_sidebars(){
      if(function_exists('register_sidebar')){     
          register_sidebar( array(
          'name' => 'MainSidebar',
          'id' => 'mainsidebar',
          'before_widget' => '<div id="%1$s" class="widget %2$s">',
          'after_widget' => '</div>',
          'before_title' => '<h4 class="widget_title">',
          'after_title' => '</h4>',
              'description'   => __('This is the global default widget area/sidebar for pages, posts, categories, tags and archive pages','vibe')
        ) );
          register_sidebar( array(
              'name' => 'Course Sidebar',
              'id' => 'coursesidebar',
              'before_widget' => '<div class="widget">',
              'after_widget' => '</div>',
              'before_title' => '<h4 class="widget_title">',
              'after_title' => '</h4>',
              'description'   => __('This is the default widget area/sidebar shown in course pages and free units','vibe')
          ) );
          register_sidebar( array(
            'name' => 'SearchSidebar',
            'id' => 'searchsidebar',
            'before_widget' => '<div class="widget">',
            'after_widget' => '</div>',
            'before_title' => '<h4 class="widget_title">',
            'after_title' => '</h4>',
                'description'   => __('This is the widget area/sidebar shown in search results page.','vibe')
          ) );

          register_sidebar( array(
              'name' => 'Shop',
              'id' => 'shopsidebar',
              'before_widget' => '<div class="widget">',
              'after_widget' => '</div>',
              'before_title' => '<h4 class="widget_title">',
              'after_title' => '</h4>',
              'description'   => __('This is the widget area/sidebar shown in the shop page ','vibe')
          ) );
          
          register_sidebar( array(
              'name' => 'Product',
              'id' => 'productsidebar',
              'before_widget' => '<div class="widget">',
              'after_widget' => '</div>',
              'before_title' => '<h4 class="widget_title">',
              'after_title' => '</h4>',
              'description'   => __('This is the default widget area/sidebar shown in single product pages and product categories','vibe')
          ) );

           register_sidebar( array(
              'name' => 'Buddypress',
              'id' => 'buddypress',
              'before_widget' => '<div class="widget">',
              'after_widget' => '</div>',
              'before_title' => '<h4 class="widget_title">',
              'after_title' => '</h4>',
              'description'   => __('This is the default widget area/sidebar shown in buddypress pages like : All Activity, All Groups, All members, All courses, All blogs','vibe')
          ) );

           register_sidebar( array(
              'name' => 'Checkout',
              'id' => 'checkout',
              'before_widget' => '<div class="widget">',
              'after_widget' => '</div>',
              'before_title' => '<h4 class="widget_title">',
              'after_title' => '</h4>',
              'description'   => __('This is the default widget area/sidebar shown in Checkout pages below coupons.','vibe')
          ) );

      $topspan=vibe_get_option('top_footer_columns');
      if(!isset($topspan)) {$topspan = 'col-md-3 col-sm-6';}
           register_sidebar( array(
              'name' => 'Top Footer Sidebar',
              'id' => 'topfootersidebar',
              'before_widget' => '<div class="'.$topspan.'"><div class="footerwidget">',
              'after_widget' => '</div></div>',
              'before_title' => '<h4 class="footertitle">',
              'after_title' => '</h4>',
              'description'   => __('Top Footer widget area / sidebar','vibe')
          ) );

      $bottomspan=vibe_get_option('bottom_footer_columns');
      if(!isset($bottomspan)) {$bottomspan = 'col-md-4 col-md-4';}
           register_sidebar( array(
              'name' => 'Bottom Footer Sidebar',
              'id' => 'bottomfootersidebar',
              'before_widget' => '<div class="'.$bottomspan.'"><div class="footerwidget">',
              'after_widget' => '</div></div>',
              'before_title' => '<h4 class="footertitle">',
              'after_title' => '</h4>',
              'description'   => __('Bottom Footer widget area / sidebar','vibe')
          ) );

           $sidebars=vibe_get_option('sidebars');
          if(isset($sidebars) && is_array($sidebars)){ 
              foreach($sidebars as $sidebar){ 
                  register_sidebar( array(
              'name' => $sidebar,
              'id' => $sidebar,
              'before_widget' => '<div class="widget"><div class="inside">',
              'after_widget' => '</div></div>',
              'before_title' => '<h4 class="widgettitle">',
              'after_title' => '</h4>',
                  'description'   => __('Custom sidebar, created from Sidebar Manager','vibe')
            ) );
            }
          }
       }
    } 

    /*==== Reset Google Fonts ====*/
    function storegoogle_webfonts(){
        $google_webfonts=get_option('google_webfonts');
            if(!isset($google_webfonts) || $google_webfonts ==''){
                $api_key = vibe_get_option('google_fonts_api_key');
                if(isset($api_key) && $api_key){
                  //Call jSon format save in $fonts
                  $url='https://www.googleapis.com/webfonts/v1/webfonts?key='.$api_key;       
                  $fonts = wp_remote_retrieve_body( wp_remote_get($url));

                  add_option( 'google_webfonts', "$fonts",'', 'no');
                }else{
                  $url='http://api.vibethemes.com/fonts.php';       
                  $fonts = wp_remote_retrieve_body( wp_remote_get($url));
                  $fonts=(string)$fonts;
                  add_option( 'google_webfonts', "$fonts",'', 'no');
                }
            }
    } 
}

WPLMS_Register::init();

