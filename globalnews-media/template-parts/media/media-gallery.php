<section class="media-gallery-section section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><span>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                <?php echo esc_html(globalnews_get_landing_setting('globalnews_media_gallery_title')); ?>
            </span></h2>
        </div>

        <div class="media-gallery-grid">
            <?php
            $media_query = new WP_Query(array(
                'posts_per_page' => 6,
                'post_type'      => array('video', 'audio'),
                'ignore_sticky_posts' => 1,
            ));
            if ($media_query->have_posts()) :
                while ($media_query->have_posts()) : $media_query->the_post();
                    $format = get_post_type() === 'audio' ? 'audio' : 'video';
            ?>
                <article class="media-card <?php echo 'media-' . esc_attr($format); ?>">
                    <a href="<?php the_permalink(); ?>">
                        <div class="media-thumb-wrapper">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('globalnews-featured', array('loading' => 'lazy')); ?>
                            <?php else : ?>
                                <img src="<?php echo esc_url(globalnews_fallback_thumbnail(get_the_ID(), '600x400')); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" style="width:100%;height:100%;object-fit:cover;">
                            <?php endif; ?>
                            <div class="media-icon">
                                <?php if ($format === 'audio') : ?>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55C7.79 13 6 14.79 6 17s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/></svg>
                                <?php else : ?>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                                <?php endif; ?>
                            </div>
                        </div>
                        <h3 class="media-title"><?php the_title(); ?></h3>
                        <span class="media-meta"><?php echo get_the_date(); ?> &middot; <?php echo strtoupper($format); ?></span>
                    </a>
                </article>
            <?php endwhile; endif; wp_reset_postdata(); ?>
        </div>

    </div>
</section>
