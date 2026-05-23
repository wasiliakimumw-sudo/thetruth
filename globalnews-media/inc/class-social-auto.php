<?php

class GlobalNews_SocialAuto {
    private static $instance = null;

    public static function instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('publish_post', array($this, 'on_publish_post'), 10, 2);
        add_action('globalnews_scheduled_social_share', array($this, 'process_social_share'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_menu', array($this, 'add_settings_page'), 20);
    }

    public function on_publish_post($post_id, $post) {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        if (wp_is_post_revision($post_id)) {
            return;
        }
        $title = get_the_title($post_id);
        $url = get_permalink($post_id);
        $excerpt = has_excerpt($post_id) ? get_the_excerpt($post_id) : wp_trim_words(strip_tags($post->post_content), 30);

        $this->post_to_facebook($title, $url, $excerpt, $post_id);
        $this->post_to_twitter($title, $url, $post_id);
        $this->post_to_linkedin($title, $url, $excerpt, $post_id);
        $this->post_to_telegram($title, $url, $excerpt, $post_id);

        do_action('globalnews_scheduled_social_share', $post_id);
    }

    public function register_settings() {
        register_setting('globalnews_social_settings', 'globalnews_facebook_page_id');
        register_setting('globalnews_social_settings', 'globalnews_facebook_access_token');
        register_setting('globalnews_social_settings', 'globalnews_twitter_api_key');
        register_setting('globalnews_social_settings', 'globalnews_twitter_api_secret');
        register_setting('globalnews_social_settings', 'globalnews_twitter_access_token');
        register_setting('globalnews_social_settings', 'globalnews_twitter_access_secret');
        register_setting('globalnews_social_settings', 'globalnews_linkedin_access_token');
        register_setting('globalnews_social_settings', 'globalnews_telegram_bot_token');
        register_setting('globalnews_social_settings', 'globalnews_telegram_chat_id');
        register_setting('globalnews_social_settings', 'globalnews_social_auto_enabled');
    }

    public function add_settings_page() {
        add_submenu_page(
            'options-general.php',
            __('Social Auto-Post', 'globalnews-media'),
            __('Social Auto-Post', 'globalnews-media'),
            'manage_options',
            'globalnews-social-settings',
            array($this, 'render_settings_page')
        );
    }

    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Social Media Auto-Posting', 'globalnews-media'); ?></h1>
            <form method="post" action="options.php">
                <?php settings_fields('globalnews_social_settings'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e('Enable Auto-Posting', 'globalnews-media'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="globalnews_social_auto_enabled" value="1" <?php checked(get_option('globalnews_social_auto_enabled', 0), 1); ?>>
                                <?php esc_html_e('Automatically share new posts to social media', 'globalnews-media'); ?>
                            </label>
                        </td>
                    </tr>
                    <tr><td colspan="2"><hr></td></tr>
                    <tr><th colspan="2"><h2><?php esc_html_e('Facebook', 'globalnews-media'); ?></h2></th></tr>
                    <tr>
                        <th scope="row"><label for="globalnews_facebook_page_id"><?php esc_html_e('Facebook Page ID', 'globalnews-media'); ?></label></th>
                        <td><input type="text" id="globalnews_facebook_page_id" name="globalnews_facebook_page_id" value="<?php echo esc_attr(get_option('globalnews_facebook_page_id', '')); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="globalnews_facebook_access_token"><?php esc_html_e('Facebook Access Token', 'globalnews-media'); ?></label></th>
                        <td><input type="password" id="globalnews_facebook_access_token" name="globalnews_facebook_access_token" value="<?php echo esc_attr(get_option('globalnews_facebook_access_token', '')); ?>" class="regular-text"></td>
                    </tr>
                    <tr><td colspan="2"><hr></td></tr>
                    <tr><th colspan="2"><h2><?php esc_html_e('X / Twitter', 'globalnews-media'); ?></h2></th></tr>
                    <tr>
                        <th scope="row"><label for="globalnews_twitter_api_key"><?php esc_html_e('API Key', 'globalnews-media'); ?></label></th>
                        <td><input type="password" id="globalnews_twitter_api_key" name="globalnews_twitter_api_key" value="<?php echo esc_attr(get_option('globalnews_twitter_api_key', '')); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="globalnews_twitter_api_secret"><?php esc_html_e('API Secret', 'globalnews-media'); ?></label></th>
                        <td><input type="password" id="globalnews_twitter_api_secret" name="globalnews_twitter_api_secret" value="<?php echo esc_attr(get_option('globalnews_twitter_api_secret', '')); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="globalnews_twitter_access_token"><?php esc_html_e('Access Token', 'globalnews-media'); ?></label></th>
                        <td><input type="password" id="globalnews_twitter_access_token" name="globalnews_twitter_access_token" value="<?php echo esc_attr(get_option('globalnews_twitter_access_token', '')); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="globalnews_twitter_access_secret"><?php esc_html_e('Access Secret', 'globalnews-media'); ?></label></th>
                        <td><input type="password" id="globalnews_twitter_access_secret" name="globalnews_twitter_access_secret" value="<?php echo esc_attr(get_option('globalnews_twitter_access_secret', '')); ?>" class="regular-text"></td>
                    </tr>
                    <tr><td colspan="2"><hr></td></tr>
                    <tr><th colspan="2"><h2><?php esc_html_e('LinkedIn', 'globalnews-media'); ?></h2></th></tr>
                    <tr>
                        <th scope="row"><label for="globalnews_linkedin_access_token"><?php esc_html_e('LinkedIn Access Token', 'globalnews-media'); ?></label></th>
                        <td><input type="password" id="globalnews_linkedin_access_token" name="globalnews_linkedin_access_token" value="<?php echo esc_attr(get_option('globalnews_linkedin_access_token', '')); ?>" class="regular-text"></td>
                    </tr>
                    <tr><td colspan="2"><hr></td></tr>
                    <tr><th colspan="2"><h2><?php esc_html_e('Telegram', 'globalnews-media'); ?></h2></th></tr>
                    <tr>
                        <th scope="row"><label for="globalnews_telegram_bot_token"><?php esc_html_e('Bot Token', 'globalnews-media'); ?></label></th>
                        <td><input type="password" id="globalnews_telegram_bot_token" name="globalnews_telegram_bot_token" value="<?php echo esc_attr(get_option('globalnews_telegram_bot_token', '')); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="globalnews_telegram_chat_id"><?php esc_html_e('Chat ID', 'globalnews-media'); ?></label></th>
                        <td><input type="text" id="globalnews_telegram_chat_id" name="globalnews_telegram_chat_id" value="<?php echo esc_attr(get_option('globalnews_telegram_chat_id', '')); ?>" class="regular-text"></td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
            <hr>
            <h2><?php esc_html_e('How it works', 'globalnews-media'); ?></h2>
            <p><?php esc_html_e('When you publish a new post, the theme will automatically attempt to share it to all configured social media platforms. Each platform requires its own API credentials which you can obtain from their respective developer portals.', 'globalnews-media'); ?></p>
            <ol>
                <li><?php esc_html_e('Facebook: Create a Facebook App, get a Page Access Token with publish_pages permission', 'globalnews-media'); ?></li>
                <li><?php esc_html_e('X/Twitter: Create a Twitter Developer App, generate API keys and Access Tokens', 'globalnews-media'); ?></li>
                <li><?php esc_html_e('LinkedIn: Create a LinkedIn App, request sharing permissions', 'globalnews-media'); ?></li>
                <li><?php esc_html_e('Telegram: Create a bot via @BotFather, add it to your channel/group as admin', 'globalnews-media'); ?></li>
            </ol>
            <p><em><?php esc_html_e('Note: Some platforms may require additional approval before auto-posting is allowed.', 'globalnews-media'); ?></em></p>
        </div>
        <?php
    }

    private function post_to_facebook($title, $url, $excerpt, $post_id) {
        if (!get_option('globalnews_social_auto_enabled', 0)) {
            return;
        }
        $page_id = get_option('globalnews_facebook_page_id', '');
        $access_token = get_option('globalnews_facebook_access_token', '');
        if (empty($page_id) || empty($access_token)) {
            return;
        }
        $thumbnail = has_post_thumbnail($post_id) ? get_the_post_thumbnail_url($post_id, 'full') : '';
        $message = $title . "\n\n" . $excerpt . "\n\n" . $url;
        $api_url = "https://graph.facebook.com/v18.0/{$page_id}/feed";
        $data = array(
            'message' => $message,
            'access_token' => $access_token,
            'link' => $url,
        );
        if ($thumbnail) {
            $data['picture'] = $thumbnail;
        }
        wp_remote_post($api_url, array(
            'body' => $data,
            'timeout' => 10,
            'blocking' => false,
        ));
    }

    private function post_to_twitter($title, $url, $post_id) {
        if (!get_option('globalnews_social_auto_enabled', 0)) {
            return;
        }
        $api_key = get_option('globalnews_twitter_api_key', '');
        $api_secret = get_option('globalnews_twitter_api_secret', '');
        $access_token = get_option('globalnews_twitter_access_token', '');
        $access_secret = get_option('globalnews_twitter_access_secret', '');
        if (empty($api_key) || empty($api_secret) || empty($access_token) || empty($access_secret)) {
            return;
        }
        if (!class_exists('Abraham\\TwitterOAuth\\TwitterOAuth')) {
            return;
        }
        try {
            $connection = new Abraham\TwitterOAuth\TwitterOAuth($api_key, $api_secret, $access_token, $access_secret);
            $hashtags = array();
            $categories = get_the_category($post_id);
            foreach ($categories as $cat) {
                $hashtags[] = '#' . str_replace(' ', '', $cat->name);
            }
            $hashtag_str = !empty($hashtags) ? ' ' . implode(' ', $hashtags) : '';
            $status = mb_substr($title, 0, 200) . ' ' . $url . $hashtag_str;
            $connection->post('statuses/update', array('status' => $status));
        } catch (Exception $e) {
            error_log('Twitter auto-post failed: ' . $e->getMessage());
        }
    }

    private function post_to_linkedin($title, $url, $excerpt, $post_id) {
        if (!get_option('globalnews_social_auto_enabled', 0)) {
            return;
        }
        $access_token = get_option('globalnews_linkedin_access_token', '');
        if (empty($access_token)) {
            return;
        }
        $thumbnail = has_post_thumbnail($post_id) ? get_the_post_thumbnail_url($post_id, 'full') : '';
        $author_id = get_option('globalnews_linkedin_author_id', '');
        if (empty($author_id)) {
            return;
        }
        $post_data = array(
            'author' => "urn:li:person:{$author_id}",
            'lifecycleState' => 'PUBLISHED',
            'specificContent' => array(
                'com.linkedin.ugc.ShareContent' => array(
                    'shareCommentary' => array(
                        'text' => $title . "\n\n" . $excerpt,
                    ),
                    'shareMediaCategory' => 'ARTICLE',
                    'media' => array(
                        array(
                            'status' => 'READY',
                            'description' => array('text' => $excerpt),
                            'originalUrl' => $url,
                            'title' => array('text' => $title),
                        ),
                    ),
                ),
            ),
            'visibility' => array(
                'com.linkedin.ugc.MemberNetworkVisibility' => 'PUBLIC',
            ),
        );
        if ($thumbnail) {
            $post_data['specificContent']['com.linkedin.ugc.ShareContent']['media'][0]['thumbnails'] = array(
                array('url' => $thumbnail),
            );
        }
        wp_remote_post('https://api.linkedin.com/v2/ugcPosts', array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $access_token,
                'Content-Type' => 'application/json',
                'X-Restli-Protocol-Version' => '2.0.0',
            ),
            'body' => json_encode($post_data),
            'timeout' => 10,
            'blocking' => false,
        ));
    }

    private function post_to_telegram($title, $url, $excerpt, $post_id) {
        if (!get_option('globalnews_social_auto_enabled', 0)) {
            return;
        }
        $bot_token = get_option('globalnews_telegram_bot_token', '');
        $chat_id = get_option('globalnews_telegram_chat_id', '');
        if (empty($bot_token) || empty($chat_id)) {
            return;
        }
        $thumbnail = has_post_thumbnail($post_id) ? get_the_post_thumbnail_url($post_id, 'full') : '';
        $message = "📰 <b>" . $title . "</b>\n\n" . $excerpt . "\n\n<a href=\"" . $url . "\">Read more →</a>";
        $api_url = "https://api.telegram.org/bot{$bot_token}/sendMessage";
        $data = array(
            'chat_id' => $chat_id,
            'text' => $message,
            'parse_mode' => 'HTML',
            'disable_web_page_preview' => false,
        );
        wp_remote_post($api_url, array(
            'body' => $data,
            'timeout' => 10,
            'blocking' => false,
        ));
        if ($thumbnail) {
            $photo_data = array(
                'chat_id' => $chat_id,
                'photo' => $thumbnail,
                'caption' => $title . "\n\n" . $url,
                'parse_mode' => 'HTML',
            );
            $photo_url = "https://api.telegram.org/bot{$bot_token}/sendPhoto";
            wp_remote_post($photo_url, array(
                'body' => $photo_data,
                'timeout' => 10,
                'blocking' => false,
            ));
        }
    }
}

GlobalNews_SocialAuto::instance();
