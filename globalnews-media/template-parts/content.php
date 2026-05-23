<?php
/**
 * Template part for displaying posts
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('content-card'); ?>>
    <a href="<?php the_permalink(); ?>" class="content-card-link">
        <?php if (has_post_thumbnail()) : ?>
            <div class="content-card-thumb">
                <?php the_post_thumbnail('globalnews-grid', array('loading' => 'lazy')); ?>
            </div>
        <?php endif; ?>
        <div class="content-card-body">
            <?php echo globalnews_category_badge(); ?>
            <h2 class="content-card-title"><?php the_title(); ?></h2>
            <p class="content-card-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
            <?php echo globalnews_post_meta(); ?>
        </div>
    </a>
</article>
