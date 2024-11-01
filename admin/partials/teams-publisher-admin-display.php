<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @param $channel
 * @return array|null
 */
function teams_publisher_sanitize_channel($channel) {
    if ((bool)wp_parse_url($channel['url'])) {
        return ['id' => sanitize_key($channel['id']), 'url' => sanitize_url($channel['url'])];
    }
    return null;
}

/**
 * @param $settings
 * @return array
 */
function teams_publisher_sanitize_settings($settings) {
    return [
            'default_image' => sanitize_url($settings['default_image']),
            //'force_di' => intval($settings['force_di']) > 0 ? 1 : 0,
            //'use_base64' => intval($settings['use_base64']) > 0 ? 1 : 0,
            'force_di' => isset($settings['force_di']) ? (intval($settings['force_di']) > 0 ? 1 : 0) : 0,
            'use_base64' => isset($settings['use_base64']) ? (intval($settings['use_base64']) > 0 ? 1 : 0) : 0,

    ];
}

if (isset($_POST['mstpa_nonce'])) {
    if ( wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['mstpa_nonce'] ) ) , 'ms-teams-publish-action' ) ) {

        $channels = isset($_POST['channels'])? array_map('teams_publisher_sanitize_channel', array_values($_POST['channels'])): [];

        $ids = array_column($channels, 'id');

        if (count($ids) !== count(array_unique($ids))) {
            ?><div class="notice notice-error"><p><?php esc_html_e('Error: duplicate ids !', 'teams-publisher') ?></p></div><?php
        } else {
            if ( update_option(TEAMS_PUBLISHER_CHANNEL_KEY, $channels) ) {
                ?><div class="notice notice-success"><p><?php esc_html_e('Channels saved', 'teams-publisher') ?></p></div><?php
            }
        }

        if ( update_option(TEAMS_PUBLISHER_DEFAULT_SETTINGS, teams_publisher_sanitize_settings($_POST['mstp_settings']))) {
            ?><div class="notice notice-success"><p><?php esc_html_e('Settings are saved', 'teams-publisher') ?></p></div><?php
        }
    } else {
        ?><div class="notice notice-error"><p><?php esc_html_e('Error: Nonce !', 'teams-publisher') ?></p></div><?php
    }
}
$channels = get_option(TEAMS_PUBLISHER_CHANNEL_KEY, []);
?>
<div class="wrap">
    <h1><?php esc_html_e('Teams Publisher', 'teams-publisher') ?></h1>
    <p><?php
        printf(
            /* translators: %s: URL of the documentation */
            esc_html__( 'Allow publishing of your contents into a Teams channel, to create the URL %s', 'teams-publisher' ),
            '<a target="_blank" href="https://learn.microsoft.com/en-us/microsoftteams/platform/webhooks-and-connectors/how-to/add-incoming-webhook?tabs=newteams%2Cdotnet">' . esc_html__( 'read this', 'teams-publisher' ) . '</a>'
        );
    ?>
    <form method="post" action="<?php echo esc_url( get_admin_url() ); ?>/options-general.php?page=teams-publisher-settings">
        <h2><?php esc_html_e('Channels', 'teams-publisher'); ?></h2>

        <input type="hidden" name="page" value="teams-publisher-settings">
        <?php wp_nonce_field( 'ms-teams-publish-action', 'mstpa_nonce', true, true); ?>
        <table id="teams-publisher-channel-table" class="form-table" role="presentation">
            <tbody>
            <?php
            foreach ($channels as $id => $channel) {
                ?>
                <tr>
                    <td class="col_id"><input pattern="[A-Za-z0-9_]+" readonly="readonly"  name="channels[<?php echo esc_attr( $id ); ?>][id]" placeholder="ID" type="text" id="channel_id_<?php echo esc_attr( $id ); ?>" value="<?php echo esc_attr( sanitize_text_field($channel['id']) ); ?>" class="widefat"></td>
                    <td class="col_url"><input required="true" name="channels[<?php echo esc_attr( $id ); ?>][url]"  placeholder="<?php esc_html_e('Channel url', 'teams-publisher'); ?>" type="url" id="channel_url_<?php echo esc_attr( $id ); ?>" value="<?php echo esc_attr( sanitize_text_field($channel['url']) ); ?>" class="widefat"></td>
                    <td>
                        <button class="button button-container remove_teams_channel" type="button">
                            <span class="dashicons dashicons-trash"></span>
                        </button>
                    </td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
        <button id="add_teams_channel" class="button button-container" data-id="<?php echo intval($id); ?>" type="button">
            <span class="dashicons dashicons-plus clickable"></span>
        </button>
        <hr/>
        <h2><?php esc_html_e('Settings', 'teams-publisher'); ?></h2>
        <?php
        $settings = get_option( TEAMS_PUBLISHER_DEFAULT_SETTINGS, [
            'force_di' => false,
            'default_image' => false,
            'use_base64' => false
        ] );
        if(! isset($settings['force_di'])){
            $settings['force_di'] = false;
        }
        if(! isset($settings['use_base64'])){
            $settings['use_base64'] = false;
        }

        ?>
        <table class="form-table" role="presentation">
            <tbody>
            <tr>
                <th >
                    <?php esc_html_e('Default image', 'teams-publisher'); ?>
                </th>
                <td>
                    <img id="mstp_settings_default_image_preview" src="<?php echo esc_attr($settings['default_image']); ?>" style="max-width: 200px; display: block; margin-top: 10px;" />
                    <input type="url" name="mstp_settings[default_image]" id="mstp_settings_default_image" value="<?php echo esc_attr( $settings['default_image'] ); ?>" class="widefat" />
                    <input id="upload_image_button" type="button" class="button" value="<?php esc_html_e('Upload Image', 'teams-publisher') ?>" />
                </td>
            </tr>
            <tr>
                <th >
                    <?php esc_html_e('Force default image', 'teams-publisher'); ?>
                </th>
                <td>
                    <?php
                    echo '<input type="checkbox" name="mstp_settings[force_di]" value="1" ' . checked( 1, esc_attr( $settings['force_di'] ), false ) . ' />';
                    ?>
                </td>
            </tr>
            <tr>
                <th >
                    <?php esc_html_e('Use base64 image', 'teams-publisher'); ?>
                </th>
                <td>
                    <?php
                    echo '<input type="checkbox" name="mstp_settings[use_base64]" value="1" ' . checked( 1, esc_attr( $settings['use_base64'] ), false ) . ' />';
                    ?>
                </td>
            </tr>
            </tbody>
        </table>

        <p class="submit">
            <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php esc_html_e('Save', 'teams-publisher') ?>"/>
        </p>
    </form>
</div>