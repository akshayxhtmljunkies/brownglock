<?php
/**
 * Options Page For Default Images
 *
 * @package   Quick_Featured_Images_Defaults
 * @author    Martin Stehle <m.stehle@gmx.de>
 * @license   GPL-2.0+
 * @link      http://wordpress.org/plugins/quick-featured-images/
 * @copyright 2014 
 */
#dambedei( $this->selected_rules );
// define some variables
$no_thumb_url = includes_url() . 'images/blank.gif';

// store recurring translations only once for more performance
$matches_label      = __( 'matches', 'quick-featured-images' );
$number_label       = __( 'No.', 'quick-featured-images' );

// WP core strings
$text = 'Choose Image';
$choose_image_label	= __( $text );
$text = 'Format';
$post_format_label	= _x( $text, 'post format' );
$text = 'Post Type';
$post_type_label	= __( $text );
$text = 'Taxonomy:';
$taxonomy_label		= __( $text );
$text = 'Action';
$action_label 	    = __( $text );
$text = 'Description';
$description_label  = __( $text );
$text = 'Image';
$image_label 	    = __( $text );
$text = 'Value';
$value_label 	    = __($text );
$text = 'Author';
$user_label 	    = __($text );
$text = '&mdash; Select &mdash;';
$first_option_label = __( $text );
$text = 'Featured Image';
$feat_img_label 	= __( $text );
$text = 'Category';
$category_label 	= _x( $text, 'taxonomy singular name' );
$text = 'Tag';
$tag_label 			= _x( $text, 'taxonomy singular name' );
$text = 'Post';
$post_label 	    = _x( $text, 'post type singular name' );
$text = 'Page';
$page_label 	    = _x( $text, 'post type singular name' );

// set parameters for term queries
$args = array( 
	'orderby'       => 'name', 
	'order'         => 'ASC',
	'hide_empty'    => false, 
	'hierarchical'  => true, 
 );

// get stored tags
$tags = get_tags( $args );

// get stored categories
$categories = get_categories( $args );

// get stored users
$users = get_users( array( 'orderby' => 'display_name' ) );

// get stored post types
$post_types = $this->get_custom_post_types_labels();

// get stored taxonomies
$custom_taxonomies = $this->get_custom_taxonomies_labels();
$custom_taxonomies_terms = array();
if ( $custom_taxonomies ) {
	foreach ( $custom_taxonomies as $key => $label ) {
		$options = array();
		$terms = get_terms( $key, $args );
		if ( is_wp_error( $terms ) ) {
			printf( '<p>%s<p>', $terms->get_error_message() );
			continue;
		}
		if ( 0 < count( $terms ) ) {
			foreach ( $terms as $term ) {
				$custom_taxonomies_terms[ $key ][ $term->term_id ] = $term->name;
			}
			if ( isset( $this->selected_custom_taxonomies[ $key ] ) ) {
				$selected_tax = $this->selected_custom_taxonomies[ $key ];
			} else {
				$selected_tax = '';
			}
		}
	}
}

// print jQuery for pulldowns
?>
<script type="text/javascript">
jQuery( document ).ready( function( $ ){

/*
 * build arrays of options
 */
 var options = new Array();
<?php
// build post type options
$key = 'post_type';
printf( 'options[ \'%s\' ] = new Array();', $key );
print "\n";
printf( 'options[ \'%s\' ].push( \'<option value="">%s</option>\' );', $key, $first_option_label );
print "\n";
printf( 'options[ \'%s\' ].push( \'<option value="%s">%s</option>\' );', $key, 'post', $post_label );
print "\n";
printf( 'options[ \'%s\' ].push( \'<option value="%s">%s</option>\' );', $key, 'page', $page_label );
print "\n";
foreach ( $post_types as $name => $label ) {
	printf( 'options[ \'%s\' ].push( \'<option value="%s">%s</option>\' );', $key, esc_attr( $name ), esc_html( $label ) );
	print "\n";
}

// build tag options
$key = 'post_tag';
printf( 'options[ \'%s\' ] = new Array();', $key );
print "\n";
printf( 'options[ \'%s\' ].push( \'<option value="">%s</option>\' );', $key, $first_option_label ); 
print "\n";
foreach ( $tags as $tag ) {
	printf( 'options[ \'%s\' ].push( \'<option value="%d">%s</option>\' );', $key, absint( $tag->term_id ), esc_html( $tag->name ) );
	print "\n";
}

// build category options
$key = 'category';
printf( 'options[ \'%s\' ] = new Array();', $key );
print "\n";
printf( 'options[ \'%s\' ].push( \'<option value="">%s</option>\' );', $key, $first_option_label );
print "\n";
foreach ( $categories as $category ) {
	printf( 'options[ \'%s\' ].push( \'<option value="%d">%s</option>\' );', $key, absint( $category->term_id ), esc_html( $category->name ) );
	print "\n";
}

// build custom taxonomy options
if ( $custom_taxonomies_terms ) {
	foreach ( array_keys( $custom_taxonomies_terms ) as $key ) {
		printf( 'options[ \'%s\' ] = new Array();', $key );
		print "\n";
		printf( 'options[ \'%s\' ].push( \'<option value="">%s</option>\' );', $key, $first_option_label );
		print "\n";
 		foreach ( $custom_taxonomies_terms[ $key ] as $term_id => $term_name ) {
			printf( 'options[ \'%s\' ].push( \'<option value="%d">%s</option>\' );', $key, absint( $term_id ), esc_html( $term_name ) );
			print "\n";
		}
	}
} // if ( custom_taxonomies_terms )

// build user options
$key = 'user';
printf( 'options[ \'%s\' ] = new Array();', $key );
print "\n";
printf( 'options[ \'%s\' ].push( \'<option value="">%s</option>\' );', $key, $first_option_label );
print "\n";
foreach ( $users as $user ) {
	printf( 'options[ \'%s\' ].push( \'<option value="%d">%s</option>\' );', $key, absint( $user->ID ), esc_html( $user->display_name ) );
	print "\n";
}
?>
	 /*
	 * Options changes
	 */
	 $( '.selection_rules' ).live( 'change', function() {
		// get number of row
		var row_number = this.id.match( /[0-9]+/ );
		// set selector names
		var selector_taxonomy = '#taxonomy_' + row_number;
		var selector_matchterm = '#matchterm_' + row_number;
		// change 'value' selection on change of 'taxonomy' selection
		$( selector_taxonomy + ' option:selected' ).each( function() {
			$( selector_matchterm ).html( options[ $( this ).val() ].join( '' ));
		} );
	} )
} )
</script>

<h3><?php _e( 'Default featured images for future posts', 'quick-featured-images' ); ?></h3>
<div id="qfi_page_description">
	<p><?php echo $this->get_page_description(); ?>. <?php _e( 'Define the rules to use images as default featured images automatically every time a post is saved.', 'quick-featured-images' ); ?></p>
	<p><?php _e( 'To use a rule choose the image and set both the taxonomy and the value. A rule which is defined only partially will be ignored.', 'quick-featured-images' ); ?></p>
</div>

<?php 
if ( ! current_theme_supports( 'post-thumbnails' ) ) {
?>
<h3 style="margin-bottom:0"><?php _e( 'Notice', 'quick-featured-images' ); ?></h3>
<div class="qfi-failure">
	<p><?php _e( 'The current theme does not support featured images. Anyway you can use this plugin. The effects are stored and will be visible in a theme which supports featured images.', 'quick-featured-images' ); ?></p>
</div>
<?php 
}
?>

<form method="post" action="">
	<table class="widefat">
		<thead>
			<tr>
				<th class="num"><?php echo $number_label; ?></th>
				<th><?php echo $image_label; ?></th>
				<th><?php echo $description_label; ?></th>
				<th><?php echo $action_label; ?></th>
			</tr>
		</thead>
		<tbody>
			<tr id="row_1" class="alternate">
				<td class="num">1</td>
				<td>
					<?php printf( '<img src="%s" alt="%s" width="80" height="80" />', plugins_url( 'assets/images/overwrite-image.jpg' , dirname( __FILE__ ) ), __( 'An image overwrites an existing image', 'quick-featured-images' ) ); ?><br />
				</td>
				<td>
					<p>
<?php
$key = 'overwrite_automatically';
$attr_checked = checked( isset( $this->selected_rules[ $key ] ), '1', false );
printf( '<label for="%s"><input type="checkbox" %s value="1" id="%s" name="%s">%s</label>', $key, $attr_checked, $key, $key, __( 'Activate to automatically overwrite an existing featured image while saving a post', 'quick-featured-images' ) );
?>
					</p>
					<p class="description"><?php _e( 'If activated the rule is used automatically while saving a post to overwrite an existing featured image with the new one based on the following rules. Do not use this if you want to keep manually set featured images.', 'quick-featured-images' ); ?></p>
				</td>
				<td>
				</td>
			</tr>
			<tr id="row_2">
				<td class="num">2</td>
				<td>
					<?php printf( '<img src="%s" alt="%s" width="80" height="80" />', plugins_url( 'assets/images/first-content-image.gif' , dirname( __FILE__ ) ), __( 'Text with images in WordPress editor', 'quick-featured-images' ) ); ?><br />
				</td>
				<td>
<?php
$key = 'use_first_image_as_default';
$attr_checked = checked( isset( $this->selected_rules[ $key ] ), '1', false );
?>
					<?php printf( '<label for="%s"><input type="checkbox" %s value="1" id="%s" name="%s">%s</label>', $key, $attr_checked, $key, $key, __( 'Activate to automatically use the first content image if available in the media library as featured image while saving a post', 'quick-featured-images' ) ); ?>
					<p class="description"><?php _e( 'If activated the rule is used automatically while saving a post to set the first content image - if available in the media library - as the featured image of the post. If the post has no content images the next rules will be applied.', 'quick-featured-images' ); ?></p>
				</td>
				<td>
				</td>
			</tr>
<?php
$c = 3;
if ( isset( $this->selected_rules[ 'rules' ] ) ) {
	foreach ( $this->selected_rules[ 'rules' ] as $rule ) {
		// only consider valid values
		if ( '0' == $rule[ 'id' ] ) continue;
		if ( '' == $rule[ 'taxonomy' ] ) continue;
		if ( '' == $rule[ 'matchterm' ] ) continue;
		// alternate row color
		if( 0 != $c % 2 ) { // if c is odd
			$row_classes = ' class="alternate"';
		} else {
			$row_classes = '';
		}
		$r_id = absint( $rule[ 'id' ] );
?>
			<tr id="row_<?php echo $c; ?>"<?php echo $row_classes; ?>>
				<td class="num"><?php echo $c; ?></td>
				<td>
					<input type="hidden" value="<?php echo $r_id; ?>" name="rules[<?php echo $c; ?>][id]" id="image_id_<?php echo $c; ?>">
					<img src="<?php echo wp_get_attachment_thumb_url( $r_id ); ?>" alt="<?php echo $feat_img_label; ?>" id="selected_image_<?php echo $c; ?>" class="attachment-thumbnail" style="width:80px; display: block;">
				</td>
				<td>
					<input type="button" name="upload_image_<?php echo $c; ?>" value="<?php echo $choose_image_label; ?>" class="button imageupload" id="upload_image_<?php echo $c; ?>"><br />
					<label for="taxonomy_<?php echo $c; ?>"><?php echo $taxonomy_label; ?></label><br />
					<select name="rules[<?php echo $c; ?>][taxonomy]" id="taxonomy_<?php echo $c; ?>" class="selection_rules">
						<option value=""><?php echo $first_option_label; ?></option>
<?php
		$key = $rule[ 'taxonomy' ];
		printf( '<option value="%s"%s>%s</option>', 'post_type', selected( 'post_type' == $key, true, false ), $post_type_label );
		print "\n";
		printf( '<option value="%s"%s>%s</option>', 'category', selected( 'category' == $key, true, false ), $category_label );
		print "\n";
		printf( '<option value="%s"%s>%s</option>', 'post_tag', selected( 'post_tag' == $key, true, false ), $tag_label );
		print "\n";
		printf( '<option value="%s"%s>%s</option>', 'user', selected( 'user' == $key, true, false ), $user_label );
		print "\n";
		if ( $custom_taxonomies_terms ) {
			foreach ( $custom_taxonomies as $key => $label ) {
				if ( $key and $label ) { // ommit empty or false values
					printf( '<option value="%s"%s>%s</option>', esc_attr( $key ), selected( $key == $rule[ 'taxonomy' ], true, false ), esc_html( $label ) );
					print "\n";
				}
			}
		}
?>
					</select><br />
					<?php echo $matches_label; ?>:<br />
					<label for="matchterm_<?php echo $c; ?>"><?php echo $value_label; ?></label><br />
					<select name="rules[<?php echo $c; ?>][matchterm]" id="matchterm_<?php echo $c; ?>">
						<option value=""><?php echo $first_option_label; ?></option>
<?php
		switch( $rule[ 'taxonomy' ] ) {
			case 'post_type':
				printf( '<option value="%s"%s>%s</option>', 'post', selected( 'post' == $rule[ 'matchterm' ], true, false ), $post_label );
				print "\n";
				printf( '<option value="%s"%s>%s</option>', 'page', selected( 'page' == $rule[ 'matchterm' ], true, false ), $page_label );
				print "\n";
				foreach ( $post_types as $key => $label ) {
					printf( '<option value="%s"%s>%s</option>', esc_attr( $key ), selected( $key == $rule[ 'matchterm' ], true, false ), esc_html( $label ) );
					print "\n";
				}
				break;
			case 'post_tag':
				foreach ( $tags as $tag ) {
					printf( '<option value="%d"%s>%s</option>', absint( $tag->term_id ), selected( $tag->term_id == $rule[ 'matchterm' ], true, false ), esc_html( $tag->name ) );
					print "\n";
				}
				break;
			case 'category':
				foreach ( $categories as $category ) {
					printf( '<option value="%d"%s>%s</option>', absint( $category->term_id ), selected( $category->term_id == $rule[ 'matchterm' ], true, false ), esc_html( $category->name ) );
					print "\n";
				}
				break;
			case 'user':
				foreach ( $users as $user ) {
					printf( '<option value="%d"%s>%s</option>', absint( $user->ID ), selected( $user->ID == $rule[ 'matchterm' ], true, false ), esc_html( $user->display_name ) );
					print "\n";
				}
				break;
			default: // custom taxonomy
				if ( $custom_taxonomies_terms ) {
					foreach ( $custom_taxonomies_terms[ $rule[ 'taxonomy' ] ] as $term_id => $term_name ) {
						printf( '<option value="%d"%s>%s</option>', absint( $term_id ), selected( $term_id == $rule[ 'matchterm' ] ), esc_html( $term_name ) );
						print "\n";
					}
				}
		} // switch()
?>
					</select>
				</td>
				<td><input type="button" name="remove_rule_<?php echo $c; ?>" value="X" class="button remove_rule" id="remove_rule_<?php echo $c; ?>"></td>
			</tr>
<?php
		$c = $c + 1;
	} // foreach()
} else {
	// show default taxonomy rule row
?>
			<tr id="row_<?php echo $c; ?>" class="alternate">
				<td class="num"><?php echo $c; ?></td>
				<td>
					<input type="hidden" value="0" name="rules[<?php echo $c; ?>][id]" id="image_id_<?php echo $c; ?>">
					<img src="<?php echo $no_thumb_url; ?>" alt="<?php echo $feat_img_label; ?>" id="selected_image_<?php echo $c; ?>" />
				</td>
				<td>
					<input type="button" name="upload_image_<?php echo $c; ?>" value="<?php echo $choose_image_label; ?>" class="button imageupload" id="upload_image_<?php echo $c; ?>" /><br />
					<label for="taxonomy_<?php echo $c; ?>"><?php echo $taxonomy_label; ?></label><br />
					<select name="rules[<?php echo $c; ?>][taxonomy]" id="taxonomy_<?php echo $c; ?>" class="selection_rules">
						<option value=""><?php echo $first_option_label; ?></option>
<?php
		printf( '						<option value="%s">%s</option>', 'post_type', $post_type_label );
		print "\n";
		printf( '						<option value="%s">%s</option>', 'category', $category_label );
		print "\n";
		printf( '						<option value="%s">%s</option>', 'post_tag', $tag_label );
		print "\n";
		printf( '						<option value="%s">%s</option>', 'user', $user_label );
		print "\n";
		if ( $custom_taxonomies_terms ) {
			foreach ( $custom_taxonomies as $key => $label ) {
				if ( $key and $label ) { // ommit empty or false values
					printf( '<option value="%s"%s>%s</option>', esc_attr( $key ), selected( $key == $rule[ 'taxonomy' ], true, false ), esc_html( $label ) );
					print "\n";
				}
			}
		}
?>
					</select><br />
					<?php echo $matches_label; ?>:<br />
					<label for="matchterm_<?php echo $c; ?>"><?php echo $value_label; ?></label><br />
					<select name="rules[<?php echo $c; ?>][matchterm]" id="matchterm_<?php echo $c; ?>">
						<option value=""><?php echo $first_option_label; ?></option>
					</select>
				</td>
				<td><input type="button" name="remove_rule_<?php echo $c; ?>" value="X" class="button remove_rule" id="remove_rule_<?php echo $c; ?>"></td>
			</tr>
<?php
} // if( rules )
?>
			<tr id="template_row">
				<td class="num">XX</td>
				<td>
					<input type="hidden" value="0" name="rules[XX][id]" id="image_id_XX">
					<img src="<?php echo $no_thumb_url; ?>" alt="<?php echo $feat_img_label; ?>" id="selected_image_XX">
				</td>
				<td>
					<input type="button" name="upload_image_XX" value="<?php echo $choose_image_label; ?>" class="button imageupload" id="upload_image_XX"><br />
					<label for="taxonomy_XX"><?php echo $taxonomy_label; ?></label><br />
					<select name="rules[XX][taxonomy]" id="taxonomy_XX" class="selection_rules">
						<option value=""><?php echo $first_option_label; ?></option>
						<option value="post_type"><?php echo $post_type_label; ?></option>
						<option value="category"><?php echo $category_label; ?></option>
						<option value="post_tag"><?php echo $tag_label; ?></option>
						<option value="user"><?php echo $user_label; ?></option>
<?php
if ( $custom_taxonomies_terms ) {
	foreach ( $custom_taxonomies as $key => $label ) {
		if ( $key and $label ) { // ommit empty or false values
			printf( '<option value="%s">%s</option>', esc_attr( $key ), esc_html( $label ) );
			print "\n";
		}
	}
}
?>
					</select><br />
					<?php echo $matches_label; ?>:<br />
					<label for="matchterm_XX"><?php echo $value_label; ?></label><br />
					<select name="rules[XX][matchterm]" id="matchterm_XX">
						<option value=""><?php echo $first_option_label; ?></option>
					</select>
				</td>
				<td><input type="button" name="remove_rule_XX" value="X" class="button remove_rule" id="remove_rule_XX"></td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<th class="num"><?php echo $number_label; ?></th>
				<th><?php echo $image_label; ?></th>
				<th><?php echo $description_label; ?></th>
				<th><?php echo $action_label; ?></th>
			</tr>
		</tfoot>
	</table>
<?php 
submit_button( __( 'Add rule', 'quick-featured-images' ), 'secondary', 'add_rule_button' );
submit_button();
wp_nonce_field( 'save_default_images', 'knlk235rf' );
?>
	<input type="hidden" id="placeholder_url" name="placeholder_url" value="<?php echo $no_thumb_url; ?>" />
	<input type="hidden" id="confirmation_question" name="confirmation_question" value="<?php _e( 'Are you sure to remove this rule?', 'quick-featured-images' ); ?>" />
</form>

<h4><?php _e( 'Additional rules in the premium version', 'quick-featured-images' ); ?></h4>
<ol>
	<li><?php _e( 'The first content image can be also an image from an external server to set it as automated featured image', 'quick-featured-images' ); ?></li>
	<li><?php _e( 'Match with a search string in post title', 'quick-featured-images' ); ?></li>
	<li><?php _e( 'Match with a selected post format', 'quick-featured-images' ); ?></li>
	<li><?php _e( 'User defined order of rules', 'quick-featured-images' ); ?></li>
</ol>
<p class="qfi_ad_for_pro"><?php _e( 'Get the premium version', 'quick-featured-images' ); ?> <a href="http://www.quickfeaturedimages.com<?php _e( '/', 'quick-featured-images' ); ?>">Quick Featured Images Pro</a>.</p>

<h4><?php _e( 'How the rules work', 'quick-featured-images' ); ?></h4>
<p><?php _e( 'Every time you save a post the post get the featured image if one of the following rules match a property of the post. You can also set rules for pages and all other current post types which support featured images.', 'quick-featured-images' ); ?></p>
<p><?php _e( 'Regardless of the order in the list the rules are applied in the following order until a rule and a property of the post fit together:', 'quick-featured-images' ); ?></p>
<ol>
	<li><?php _e( 'found first content image. If not then...', 'quick-featured-images' ); ?></li>
	<li><?php _e( 'matched custom taxonomy. If not then...', 'quick-featured-images' ); ?></li>
	<li><?php _e( 'matched tag. If not then...', 'quick-featured-images' ); ?></li>
	<li><?php _e( 'matched category. If not then...', 'quick-featured-images' ); ?></li>
	<li><?php _e( 'matched author. If not then...', 'quick-featured-images' ); ?></li>
	<li><?php _e( 'matched post type. If not then...', 'quick-featured-images' ); ?></li>
	<li><?php _e( 'no featured image.', 'quick-featured-images' ); ?></li>
</ol>
<p><?php _e( 'Bear in mind that if two or more rules with the same taxonomy would fit to the post it is unforeseeable which image will become the featured image.', 'quick-featured-images' ); ?></p>
