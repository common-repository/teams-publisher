<?php

namespace Sebbmyr\Teams\Cards\Adaptive\Actions;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
interface AdaptiveCardAction
{
    public function getContent($version);
}
