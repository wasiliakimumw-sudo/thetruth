<div class="headline-ticker">
    <div class="container">
        <div class="headline-ticker-inner">
            <div class="headline-ticker-label"><?php echo esc_html(globalnews_get_landing_setting('globalnews_ticker_label')); ?></div>
            <div class="headline-ticker-track-wrap">
                <div class="headline-ticker-track" id="headlineTickerTrack">
                    <?php
                    $headline_args = array(
                        'posts_per_page'      => 10,
                        'ignore_sticky_posts' => 1,
                    );
                    $headline_query = new WP_Query($headline_args);
                    if ($headline_query->have_posts()) :
                        while ($headline_query->have_posts()) : $headline_query->the_post(); ?>
                            <div class="headline-ticker-item">
                                <a href="<?php the_permalink(); ?>">
                                    <span class="headline-ticker-title"><?php the_title(); ?></span>
                                </a>
                            </div>
                        <?php endwhile;
                    endif;
                    wp_reset_postdata();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
