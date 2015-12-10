<?php
/**
 * AccessPress Mag Custom Widgets
 *
 * @package AccessPress Mag
 */
 
/**
 * Include helper functions that display widget fields in the dashboard
 *
 * @since Accesspress Widget Pack 1.0
 */
get_template_part( 'inc/widgets/widget', 'fields' );

/**
 * Random posts
 *
 * @since accesspress Widget Pack 1.0
 */
get_template_part( 'inc/widgets/widget', 'random-posts' );

/**
 * Latest posts
 *
 * @since accesspress Widget Pack 1.0
 */
get_template_part( 'inc/widgets/widget', 'latest-posts' );

/**
 * Article Contributors
 *
 * @since accesspress Widget Pack 1.0
 */
get_template_part( 'inc/widgets/widget', 'contributors' );