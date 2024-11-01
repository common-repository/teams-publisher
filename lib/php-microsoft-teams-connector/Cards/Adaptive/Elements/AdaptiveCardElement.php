<?php

namespace Sebbmyr\Teams\Cards\Adaptive\Elements;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
interface AdaptiveCardElement
{
    public function getContent($version);
}
