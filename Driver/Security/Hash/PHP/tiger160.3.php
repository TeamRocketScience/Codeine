<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: tiger160,3 Hash Extension Wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     */

    
    self::Fn('Get', function ($Call)
    {
        if (isset($Call['Key']))
            return hash('tiger160,3', $Call['Input'], $Call['Key']);
        else
            return hash('tiger160,3', $Call['Input']);
    });