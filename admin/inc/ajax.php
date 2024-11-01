<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! defined( 'MSTP_PLUGIN_PATH' ) ) {
    define( 'MSTP_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}

require_once MSTP_PLUGIN_PATH . 'lib/php-microsoft-teams-connector/TeamsConnector.php';
require_once MSTP_PLUGIN_PATH . 'lib/php-microsoft-teams-connector/TeamsConnectorInterface.php';
require_once MSTP_PLUGIN_PATH . 'lib/php-microsoft-teams-connector/AbstractCard.php';
require_once MSTP_PLUGIN_PATH . 'lib/php-microsoft-teams-connector/Cards/HeroCard.php';


class TEAMS_PUBLISHER {

    private $channels = [];

    public function __construct()
    {
        $this->channels = get_option(TEAMS_PUBLISHER_CHANNEL_KEY, []);
    }

    /**
     * @param $channel
     * @return false|mixed
     */
    private function getUrl($channel) {
        foreach ($this->channels as $channels) {
            if ($channels['id'] === $channel) return $channels['url'];
        }
        return false;
    }

    /**
     * @param int $post_id
     * @param string $channel
     * @param $cardType
     * @return void
     * @throws Exception
     */
    public function publish(int $post_id, string $channel, $cardType = 'hero') : void {
        $post_id = intval($post_id);
        $url = $this->getUrl($channel);
        /* translators: %s: Id of the channel used */
        if (!$url) throw new Exception(esc_html(sprintf(__('Channel %s not found', 'teams-publisher'), $channel)));
        $post = get_post($post_id);
        /* translators: %s: Id of the post used */
        if (!$post) throw new Exception(esc_html(sprintf(__('Post %d not found', 'teams-publisher'),$post_id)));

        $connector = new \Sebbmyr\Teams\TeamsConnector($url);

        $settings = get_option( TEAMS_PUBLISHER_DEFAULT_SETTINGS, [
            'force_di' => false,
            'default_image' => false,
            'use_base64' => false
        ] );
        if (!isset($settings['force_di'])) $settings['force_di'] = false;
        if (!isset($settings['use_base64'])) $settings['use_base64'] = false;
        if (!isset($settings['default_image'])) $settings['default_image'] = false;
        $image = false;

        if ($settings['default_image']) {
            $image = $settings['default_image'];
        }
        if ($settings['force_di'] != 1) {
            $hasImage = get_the_post_thumbnail_url($post_id, 'full');
            if ($hasImage) {
                $image = $hasImage;
            }
        }
        $subtitle = "";
        $category_detail=get_the_category($post_id);
        foreach($category_detail as $cd){
            $subtitle = $cd->cat_name;
        }

        switch($cardType) {
            default:
                $card = new \Sebbmyr\Teams\Cards\HeroCard();
                ;
                if ($image) {

                    if ($settings['use_base64']) {
                        $site_url = get_site_url();

                        if (str_starts_with($image, $site_url)) {

                            $image = str_replace($site_url, ABSPATH, $image);
                            $type = pathinfo($image, PATHINFO_EXTENSION);

                            // Call globals
                            global $wp_filesystem;

                            // You have to require following file in the front-end only. In the back-end; its already included
                            require_once ABSPATH . 'wp-admin/includes/file.php'; // Pour les fichiers inclus dans wp-admin


                            // Initiate
                            WP_Filesystem();

                            $img = $wp_filesystem->get_contents($image);
                            $image = 'data:image/' . $type . ';base64,' . base64_encode($img);

                        } else {
                            $response = wp_remote_get($image);
                            if (!is_wp_error($response)) {
                                $body = wp_remote_retrieve_body($response);

                                // Get the content type
                                $content_type = wp_remote_retrieve_header($response, 'content-type');

                                // Check if we have a valid image mime type
                                if (in_array($content_type, ['image/jpeg', 'image/png', 'image/gif'])) {
                                    $image = 'data:' . $content_type . ';base64,' . base64_encode($body);
                                }
                            }
                        }
                    }

                    $card->setTitle(get_the_title($post))
                        ->setSubtitle($subtitle)
                        ->addImage($image)
                        ->setText(get_the_excerpt($post))
                        ->addButton("openUrl", "Read More", get_permalink($post));

                } else {
                    $card->setTitle(get_the_title($post))
                        ->setSubtitle($subtitle)
                        ->setText(get_the_excerpt($post))
                        ->addButton("openUrl", "Read More", get_permalink($post));
                }
                break;
        }
        $connector->send($card);
    }
}