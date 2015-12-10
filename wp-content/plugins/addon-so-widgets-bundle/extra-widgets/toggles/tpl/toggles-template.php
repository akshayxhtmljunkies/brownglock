<?php
$widget_title =  wp_kses_post($instance['widget_title']);
?>


<?php if ($widget_title) { ?>
    <h3 class="widget-title">
        <span><?php echo $widget_title ?></span>
    </h3>
<?php } ?>


<div class="soua-main">
    <?php foreach( $instance['toggle_repeater'] as $i => $toggle_repeater ) : ?>
    <div class="soua-accordion">
        <a class="soua-accordion-title"><?php echo $toggle_repeater['toggle_title'] ?></a>
        <div class="soua-accordion-content"> <?php echo $toggle_repeater['toggle_content'] ?></div>
    </div>
    <?php endforeach; ?>
</div> <!-- / accordion -->

