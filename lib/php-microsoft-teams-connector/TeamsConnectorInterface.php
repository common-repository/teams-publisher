<?php

namespace Sebbmyr\Teams;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

interface TeamsConnectorInterface
{

    /**
     * Returns message card array
     *
     * @return array
     */
    public function getMessage();
}
