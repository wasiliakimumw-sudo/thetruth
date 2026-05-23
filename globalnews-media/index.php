<?php
get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <div class="content-area">
            <?php
            if (have_posts()) :
                while (have_posts()) :
                    the_post();
                    get_template_part('template-parts/content', get_post_type());
                endwhile;
                the_posts_pagination(array(
                    'mid_size'  => 2,
                    'prev_text' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg>',
                    'next_text' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>',
                ));
            else :
                get_template_part('template-parts/content', 'none');
            endif;
            ?>
        </div>
        <?php get_sidebar(); ?>
    </div>
</main>

<?php
get_footer();
