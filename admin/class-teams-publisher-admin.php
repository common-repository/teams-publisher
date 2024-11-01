<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.cownected.com
 * @since      1.0.0
 *
 * @package    Ms_Teams_Publisher
 * @subpackage Ms_Teams_Publisher/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ms_Teams_Publisher
 * @subpackage Ms_Teams_Publisher/admin
 * @author     Thibaut Colson <hello@cownected.com>
 */
class Ms_Teams_Publisher_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;


    public function init() {

        global $pagenow;
            add_action( 'enqueue_block_editor_assets', function(){

                $inc = require_once 'blocks/sidebar/build/index.asset.php';
                $version = WP_DEBUG ? $inc['version'] : $this->version;

                wp_enqueue_script( $this->plugin_name.'-sidebar',  plugins_url( 'blocks/sidebar/build/index.js', __FILE__ ), $inc['dependencies'], $version, false);
                wp_set_script_translations( $this->plugin_name.'-sidebar', 'ms-team-publisher', plugin_dir_path( __FILE__ ) . 'languages' );

                wp_localize_script($this->plugin_name.'-sidebar', 'mstp_sidebar', array(
                    'channels' =>array_column( get_option(TEAMS_PUBLISHER_CHANNEL_KEY, []), 'id'),
                    'url' => get_admin_url(null, 'admin-ajax.php').'?action=publish_on_teams',
                    'url_settings' => get_admin_url().'options-general.php?page=teams-publisher-settings'
                    //'post_id' => intval($_GET['post'])
                ));
            });

        register_post_meta('post', TEAMS_PUBLISHER_LOGS, array(
            'show_in_rest' => array(
                'schema' => array(
                    'type' => 'array',
                    'items' => array(
                        'type' => 'object',
                        'properties' => array(
                            'date' => array(
                                'type' => 'string',
                            ),
                            'channel' => array(
                                'type' => 'string',
                            ),
                            'type' => array(

                                'type' => 'string',
                            ),
                            'message' => array(
                                'type' => 'string',
                            ),
                        ),
                    ),
                ),
            ),
            'single' => true,
            'type' => 'string',
        ));

    }
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

        add_action('admin_menu', [$this, 'admin_menu']);
        add_action('init', [$this, 'init']);

        add_action( 'wp_ajax_publish_on_teams', [$this, 'ajax'] );

        register_meta(
            'post',
            'mstp_channels',
            array(
                'type' => 'array',
                'single' => true,
                'show_in_rest' => array(
                    'schema' => array(
                        'type'  => 'array',
                        'items' => array(
                            'type' => 'string'
                        )
                    )
                )
            )
        );
        register_meta(
            'post',
            TEAMS_PUBLISHER_LOGS,
            array(
                'type' => 'array',
                'single' => true,
                'show_in_rest' => array(
                    'schema' => array(
                        'type'  => 'array',
                        'items' => array(
                            'type' => 'string'
                        )
                    )
                )
            )
        );

    }

    /**
     * @return void
     */
    public function admin_menu() {
        add_submenu_page('options-general.php', __('Teams Publish', 'teams-publisher'), __('Teams Publish', 'teams-publisher'), 'edit_posts', 'teams-publisher-settings', [$this, 'settings'] );

    }


    /**
     * Registers a text field setting for WordPress 4.7 and higher.
     **/
    public function settings() {
        $this->my_enqueue_media_uploader();
        require_once 'partials/teams-publisher-admin-display.php';
	}

    function my_enqueue_media_uploader() {
        wp_enqueue_media();
        wp_enqueue_script('my-media-uploader', plugin_dir_url( __FILE__ )  . 'js/my-media-uploader.js', array('jquery'), $this->version, true);
    }

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ms_Teams_Publisher_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ms_Teams_Publisher_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/teams-publisher-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function ajax($request) {

        require_once MSTP_PATH.'wp-content/plugins/teams-publisher/admin/inc/ajax.php';

        $posted = json_decode(file_get_contents("php://input"), true);
        $post_id = intval($posted['post_id']);
        $channels = array_map('sanitize_key', $posted['channels']);

        $logs = get_post_meta( $post_id, TEAMS_PUBLISHER_LOGS, true);
        if (!$logs) $logs = [];
        $index = sizeof($logs);

        $mstpublisher = new TEAMS_PUBLISHER();
        foreach ($channels as $channel) {
            $message = "Sent by ".wp_get_current_user()->display_name;
            $response = 'error';

            $logs[$index] = [
                "date" => current_time('mysql'),
                "type" => 'pending',
                "channel" => $channel,
                "message"=> "..."
            ];

            try {
                $mstpublisher->publish($post_id, $channel);
                $response = 'success';
            } catch (Exception $e) {
                $message .= " !! ".substr(wp_strip_all_tags($e->getMessage()), 0, 128);
            }

            $logs[$index]['type'] = $response;
            $logs[$index]['message'] = $message;
            $index++;
        }
        $logs = array_slice($logs, -10);

        update_post_meta( $post_id, TEAMS_PUBLISHER_LOGS, $logs);
        wp_die( wp_json_encode($logs) );
    }

    /**
     * @return void
     */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ms_Teams_Publisher_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ms_Teams_Publisher_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/teams-publisher-admin.js', array( 'jquery' ), $this->version, false, false );

	}

    public function execute_hooks() {

    }

}