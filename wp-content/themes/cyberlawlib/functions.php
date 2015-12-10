<?php
/**
 * Cyber Law Library functions and definitions
 *
 * @package cyberlawlib
 */
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('parent-style')
    );
}

add_filter(
    'publish_confirm_message',
    function($msg) {
        return "Just to check... Have you \n1) assigned this post to one category (and maybe 'Events') \n2) applied appropriate tags (source, country, topic) \n3) set a featured image?\n\nSure?";
    }
);

/**
 * Enforce minimum image upload size.
 */
add_filter('wp_handle_upload_prefilter','handle_upload_prefilter');

function handle_upload_prefilter($file) {
	$img=getimagesize($file['tmp_name']);
	$minimum = array('width' => '550', 'height' => '370');
	$width= $img[0];
	$height =$img[1];

// Bypass function for Admins
    if( current_user_can( 'manage_options' ) ) {
		return $file;
	}
	elseif ($width < $minimum['width'] ){
		return array("error"=>"Image dimensions are too small, please upload a larger version of the image. Minimum width is {$minimum['width']}px (>768px recommended). Uploaded image width is $width px.");
	} elseif ($height <  $minimum['height']){
		return array("error"=>"Image dimensions are too small, please upload a larger version of the image. Minimum height is {$minimum['height']}px (>480 recommended). Uploaded image width is $width px.");
	} else {
		return $file; 
	}
}
/**
 * Change login/register logo, title and url
 */
function the_url( $url ) {
    return get_bloginfo( 'url' );
}
add_filter( 'login_headerurl', 'the_url' );


function the_name( $name ) {
    return get_bloginfo( 'name' );
}
add_filter( 'login_header_title', 'the_name' );

//Redirect logged out users to home
add_action('wp_logout','go_home');
function go_home(){
  wp_redirect( home_url() );
  exit();
}

// changes the "Register For This Site" text on the Wordpress login screen (wp-login.php) //
function ik_change_login_message($message)
{
// change messages that contain 'Register'
if (strpos($message, 'Register') !== FALSE) {
	$newMessage = "Join the Cyber Law Library. Use your existing social login or enter a username, your email, and choose a secure password.";
		return '<p class="message register">' . $newMessage . '</p>';
	}
	else {
		return $message;
	}
}
 
// add our new function to the login_message hook
add_action('login_message', 'ik_change_login_message');

function accesspress_mag_word_count( $string, $limit ) {
    $string = strip_tags( $string );
    $string = strip_shortcodes( $string );
    $words = explode( ' ', $string );
    $excerpt = implode( ' ', array_slice( $words, 0, $limit ));
	return $excerpt . "..." ;
}

/*
 * Resize images dynamically using wp built in functions
 * Victor Teixeira
 *
 * php 5.2+
 *
 * Exemplo de uso:
 * 
 * <?php 
 * $thumb = get_post_thumbnail_id(); 
 * $image = vt_resize( $thumb, '', 140, 110, true );
 * ?>
 * <img src="<?php echo $image[url]; ?>" width="<?php echo $image[width]; ?>" height="<?php echo $image[height]; ?>" />
 *
 * @param int $attach_id
 * @param string $img_url
 * @param int $width
 * @param int $height
 * @param bool $crop
 * @return array
 */
if ( !function_exists( 'vt_resize') ) {
    function vt_resize( $attach_id = null, $img_url = null, $width, $height, $crop = false ) {

        // this is an attachment, so we have the ID
        if ( $attach_id ) {

            $image_src = wp_get_attachment_image_src( $attach_id, 'full' );
            $file_path = get_attached_file( $attach_id );

        // this is not an attachment, let's use the image url
        } else if ( $img_url ) {

            $file_path = parse_url( $img_url );
            $file_path = $_SERVER['DOCUMENT_ROOT'] . $file_path['path'];

            // Look for Multisite Path
            if(file_exists($file_path) === false){
                global $blog_id;
                $file_path = parse_url( $img_url );
                if (preg_match("/files/", $file_path['path'])) {
                    $path = explode('/',$file_path['path']);
                    foreach($path as $k=>$v){
                        if($v == 'files'){
                            $path[$k-1] = 'wp-content/blogs.dir/'.$blog_id;
                        }
                    }
                    $path = implode('/',$path);
                }
                $file_path = $_SERVER['DOCUMENT_ROOT'].$path;
            }
            //$file_path = ltrim( $file_path['path'], '/' );
            //$file_path = rtrim( ABSPATH, '/' ).$file_path['path'];

            $orig_size = getimagesize( $file_path );

            $image_src[0] = $img_url;
            $image_src[1] = $orig_size[0];
            $image_src[2] = $orig_size[1];
        }

        $file_info = pathinfo( $file_path );

        // check if file exists
        $base_file = $file_info['dirname'].'/'.$file_info['filename'].'.'.$file_info['extension'];
        if ( !file_exists($base_file) )
         return;

        $extension = '.'. $file_info['extension'];

        // the image path without the extension
        $no_ext_path = $file_info['dirname'].'/'.$file_info['filename'];

        $cropped_img_path = $no_ext_path.'-'.$width.'x'.$height.$extension;

        // checking if the file size is larger than the target size
        // if it is smaller or the same size, stop right here and return
        if ( $image_src[1] > $width ) {

            // the file is larger, check if the resized version already exists (for $crop = true but will also work for $crop = false if the sizes match)
            if ( file_exists( $cropped_img_path ) ) {

                $cropped_img_url = str_replace( basename( $image_src[0] ), basename( $cropped_img_path ), $image_src[0] );

                $vt_image = array (
                    'url' => $cropped_img_url,
                    'width' => $width,
                    'height' => $height
                );

                return $vt_image;
            }

            // $crop = false or no height set
            if ( $crop == false OR !$height ) {

                // calculate the size proportionaly
                $proportional_size = wp_constrain_dimensions( $image_src[1], $image_src[2], $width, $height );
                $resized_img_path = $no_ext_path.'-'.$proportional_size[0].'x'.$proportional_size[1].$extension;

                // checking if the file already exists
                if ( file_exists( $resized_img_path ) ) {

                    $resized_img_url = str_replace( basename( $image_src[0] ), basename( $resized_img_path ), $image_src[0] );

                    $vt_image = array (
                        'url' => $resized_img_url,
                        'width' => $proportional_size[0],
                        'height' => $proportional_size[1]
                    );

                    return $vt_image;
                }
            }

            // check if image width is smaller than set width
            $img_size = getimagesize( $file_path );
            if ( $img_size[0] <= $width ) $width = $img_size[0];

            // Check if GD Library installed
            if (!function_exists ('imagecreatetruecolor')) {
                echo 'GD Library Error: imagecreatetruecolor does not exist - please contact your webhost and ask them to install the GD library';
                return;
            }

            // no cache files - let's finally resize it
            $new_img_path = image_resize( $file_path, $width, $height, $crop );         
            $new_img_size = getimagesize( $new_img_path );
            $new_img = str_replace( basename( $image_src[0] ), basename( $new_img_path ), $image_src[0] );

            // resized output
            $vt_image = array (
                'url' => $new_img,
                'width' => $new_img_size[0],
                'height' => $new_img_size[1]
            );

            return $vt_image;
        }

        // default output - without resizing
        $vt_image = array (
            'url' => $image_src[0],
            'width' => $width,
            'height' => $height
        );

        return $vt_image;
    }
}