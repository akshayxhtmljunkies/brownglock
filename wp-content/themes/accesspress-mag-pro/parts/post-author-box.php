<?php
/**
 * Managed the post's author box
 * 
 * @package Accesspress Mag Pro
 */
?>

<div class="author-metabox">
    <?php
        global $post;
        $author_id = $post->post_author;
        $author_avatar = get_avatar( $author_id, '106' );
        $author_nickname = get_the_author_meta( 'display_name' );                
    ?>
    <div class="author-avatar">
        <a class="author-image" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) );?>"><?php echo $author_avatar; ?></a>
    </div>
    <div class="author-desc-wrapper">                
        <a class="author-title" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) );?>"><?php echo esc_attr( $author_nickname ); ?></a>
        <div class="author-description"><?php echo get_the_author_meta('description');?></div>
        <a href="<?php echo esc_url( get_the_author_meta( 'user_url' ) );?>" target="_blank"><?php echo esc_url( get_the_author_meta( 'user_url' ) );?></a>
        <ul class="author-social-wrapper">
        <?php 
            global $apmag_user_social_array;
            foreach( $apmag_user_social_array as $icon_id => $icon_name ) {
                $author_social_link = get_the_author_meta( $icon_id );
                if( !empty( $author_social_link ) ) {
        ?>
                    <li><a href="<?php echo esc_url( $author_social_link )?>" target="_blank" title="<?php echo esc_attr( $icon_name )?>"><i class="fa fa-<?php echo esc_attr( $icon_id ); ?>"></i></a></li>
        <?php            
                }
            }
        ?>
        </ul>
    </div>
</div><!--author-metabox-->