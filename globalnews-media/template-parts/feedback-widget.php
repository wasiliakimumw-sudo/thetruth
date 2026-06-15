<?php
$post_id = get_the_ID();
$reactions = globalnews_get_reactions($post_id);
$user_reactions = globalnews_get_user_reactions($post_id);
$total = array_sum($reactions);
$helpful = get_post_meta($post_id, '_globalnews_helpful', true) ?: array('yes' => 0, 'no' => 0);
?>

<div class="article-feedback" data-post-id="<?php echo $post_id; ?>">
    <div class="feedback-reactions">
        <span class="feedback-label"><?php esc_html_e('React to this article', 'globalnews-media'); ?></span>
        <div class="reaction-buttons">
            <?php
            $emojis = array(
                'like' => array('👍', __('Like', 'globalnews-media')),
                'love' => array('❤️', __('Love', 'globalnews-media')),
                'wow'  => array('😮', __('Wow', 'globalnews-media')),
                'sad'  => array('😢', __('Sad', 'globalnews-media')),
                'fire' => array('🔥', __('Fire', 'globalnews-media')),
            );
            foreach ($emojis as $key => $emoji) :
                $count = isset($reactions[$key]) ? $reactions[$key] : 0;
                $active = in_array($key, $user_reactions, true) ? 'active' : '';
            ?>
                <button class="reaction-btn <?php echo $active; ?>" data-reaction="<?php echo $key; ?>" title="<?php echo esc_attr($emoji[1]); ?>" aria-label="<?php echo esc_attr($emoji[1]); ?>">
                    <span class="reaction-emoji"><?php echo $emoji[0]; ?></span>
                    <span class="reaction-count"><?php echo $count; ?></span>
                </button>
            <?php endforeach; ?>
        </div>
        <?php if ($total > 0) : ?>
            <span class="reaction-total"><?php printf(esc_html(_n('%d reaction', '%d reactions', $total, 'globalnews-media')), $total); ?></span>
        <?php endif; ?>
    </div>

    <div class="feedback-helpful">
        <span class="feedback-label"><?php esc_html_e('Was this article helpful?', 'globalnews-media'); ?></span>
        <div class="helpful-buttons">
            <button class="helpful-btn" data-type="yes" aria-label="<?php esc_attr_e('Yes', 'globalnews-media'); ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3H14zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/></svg>
                <span><?php esc_html_e('Yes', 'globalnews-media'); ?></span>
                <span class="helpful-count"><?php echo absint($helpful['yes']); ?></span>
            </button>
            <button class="helpful-btn" data-type="no" aria-label="<?php esc_attr_e('No', 'globalnews-media'); ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 15v4a3 3 0 0 0 3 3l4-9V2H5.72a2 2 0 0 0-2 1.7l-1.38 9a2 2 0 0 0 2 2.3H10zM17 2h3a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2h-3"/></svg>
                <span><?php esc_html_e('No', 'globalnews-media'); ?></span>
                <span class="helpful-count"><?php echo absint($helpful['no']); ?></span>
            </button>
        </div>
    </div>

    <div class="feedback-form-toggle">
        <button class="feedback-form-btn" type="button">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            <?php esc_html_e('Send feedback to editors', 'globalnews-media'); ?>
        </button>
    </div>

    <div class="feedback-form-wrapper" style="display:none;">
        <form class="feedback-form" method="post">
            <div class="feedback-form-field">
                <label for="feedback-type"><?php esc_html_e('Feedback type', 'globalnews-media'); ?></label>
                <select id="feedback-type" name="feedback_type">
                    <option value="general"><?php esc_html_e('General feedback', 'globalnews-media'); ?></option>
                    <option value="correction"><?php esc_html_e('Report a correction', 'globalnews-media'); ?></option>
                    <option value="suggestion"><?php esc_html_e('Suggest an improvement', 'globalnews-media'); ?></option>
                </select>
            </div>
            <div class="feedback-form-field">
                <label for="feedback-message"><?php esc_html_e('Your message', 'globalnews-media'); ?></label>
                <textarea id="feedback-message" name="feedback_message" rows="4" placeholder="<?php esc_attr_e('Share your thoughts about this article...', 'globalnews-media'); ?>"></textarea>
            </div>
            <div class="feedback-form-actions">
                <button type="submit" class="feedback-submit btn btn-primary"><?php esc_html_e('Send feedback', 'globalnews-media'); ?></button>
                <span class="feedback-form-msg"></span>
            </div>
        </form>
    </div>
</div>
