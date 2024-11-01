<?php

namespace Sebbmyr\Teams;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Teams connector
 */
class TeamsConnector
{
    private $webhookUrl;

    public function __construct($webhookUrl)
    {
        $this->webhookUrl = $webhookUrl;
    }

    /**
     * Sends card message as POST request
     *
     * @param  TeamsConnectorInterface $card
     * @param  int $curlOptTimeout by default = 10
     * @param  int $curlOptConnectTimeout by default = 3
     * @throws Exception
     */
    public function send(TeamsConnectorInterface $card, $curlOptTimeout = 10, $curlOptConnectTimeout = 3)
    {
        $json = wp_json_encode($card->getMessage());

        $response = wp_remote_post($this->webhookUrl, array(
            'timeout' => $curlOptTimeout,
            'headers' => array(
                'Content-Type' => 'application/json',
                'Content-Length' => strlen($json)
            ),
            'body' => $json
        ));

        if (is_wp_error($response)) {
            $error_message = esc_html($response->get_error_message());
            throw new \Exception( esc_html($error_message) );
        }

        $result = wp_remote_retrieve_body($response);

        if ($result !== "1") {
            throw new \Exception('Error response: ' . esc_html($result));
        }
    }

}
