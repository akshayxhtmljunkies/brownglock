<?php
$widget_title = wp_kses_post($instance['widget_title']);
$query = siteorigin_widget_post_selector_process_query($instance['posts']);
$the_query = new WP_Query($query);

?>


<?php if ($widget_title) { ?>
    <h3 class="widget-title">
        <span><?php echo $widget_title ?></span>
    </h3>
<?php } ?>


<div class="soua-main">
        <?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
        <div class="soua-accordion">
            <a class="soua-accordion-title"> <?php the_title(); ?></a>
            <div class="soua-accordion-content"><?php the_content();?></div>
        </div>
        <?php endwhile; ?>
</div> <!-- / accordion -->


