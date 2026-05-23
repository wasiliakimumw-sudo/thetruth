<?php
/**
 * Comments Template
 */

if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area">
    <?php if (have_comments()) : ?>
        <h3 class="comments-title">
            <?php
            $comment_count = get_comments_number();
            printf(
                esc_html(_nx('%d Comment', '%d Comments', $comment_count, 'comments title', 'globalnews-media')),
                number_format_i18n($comment_count)
            );
            ?>
        </h3>

        <?php the_comments_navigation(); ?>

        <ol class="comment-list">
            <?php
            wp_list_comments(array(
                'style'       => 'ol',
                'short_ping'  => true,
                'avatar_size' => 60,
                'callback'    => 'globalnews_comment_callback',
            ));
            ?>
        </ol>

        <?php the_comments_navigation(); ?>

        <?php if (!comments_open()) : ?>
            <p class="no-comments"><?php esc_html_e('Comments are closed.', 'globalnews-media'); ?></p>
        <?php endif; ?>
    <?php endif; ?>

    <?php
    comment_form(array(
        'title_reply_before' => '<h3 id="reply-title" class="comment-reply-title">',
        'title_reply_after'  => '</h3>',
        'class_submit'       => 'submit btn btn-primary',
        'comment_field'      => '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="8" placeholder="' . esc_attr__('Write your comment...', 'globalnews-media') . '" required></textarea></p>',
        'fields'             => array(
            'author' => '<p class="comment-form-author"><input id="author" name="author" type="text" placeholder="' . esc_attr__('Name *', 'globalnews-media') . '" size="30" required></p>',
            'email'  => '<p class="comment-form-email"><input id="email" name="email" type="email" placeholder="' . esc_attr__('Email *', 'globalnews-media') . '" size="30" required></p>',
            'url'    => '<p class="comment-form-url"><input id="url" name="url" type="url" placeholder="' . esc_attr__('Website', 'globalnews-media') . '" size="30"></p>',
        ),
    ));
    ?>
</div>

<?php
function globalnews_comment_callback($comment, $args, $depth) {
    $tag = ('div' === $args['style']) ? 'div' : 'li';
    ?>
    <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class(empty($args['has_children']) ? '' : 'parent'); ?>>
        <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
            <div class="comment-author-avatar">
                <?php echo get_avatar($comment, $args['avatar_size']); ?>
            </div>
            <div class="comment-content-wrapper">
                <div class="comment-meta">
                    <span class="comment-author-name"><?php comment_author_link(); ?></span>
                    <span class="comment-date">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        <?php printf(esc_html__('%s ago', 'globalnews-media'), human_time_diff(get_comment_time('U'), current_time('timestamp'))); ?>
                    </span>
                </div>
                <?php if ('0' === $comment->comment_approved) : ?>
                    <p class="comment-awaiting-moderation"><?php esc_html_e('Your comment is awaiting moderation.', 'globalnews-media'); ?></p>
                <?php endif; ?>
                <div class="comment-text"><?php comment_text(); ?></div>
                <div class="comment-actions">
                    <?php
                    comment_reply_link(array_merge($args, array(
                        'reply_text' => esc_html__('Reply', 'globalnews-media') . ' <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>',
                        'depth'      => $depth,
                        'max_depth'  => $args['max_depth'],
                    )));
                    edit_comment_link(esc_html__('Edit', 'globalnews-media'));
                    ?>
                </div>
            </div>
        </article>
    <?php
}
