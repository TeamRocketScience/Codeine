<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: JSON Router
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 10.11.10
     * @time 23:16
     */

    self::Fn('Route', function ($Call)
    {
        if (is_string($Call['Call']) && $Routed = json_decode($Call['Call'], true))
            return $Routed;
        else
            return null;
    });
