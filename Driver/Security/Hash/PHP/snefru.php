<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: snefru Hash Extension Wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     */

    
    self::Fn('Get', function ($Call)
    {
        if (isset($Call['Key']))
            return hash('snefru', $Call['Input'], $Call['Key']);
        else
            return hash('snefru', $Call['Input']);
    });