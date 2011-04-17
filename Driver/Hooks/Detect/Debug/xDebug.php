<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: 
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 17.04.11
     * @time 15:31
     */

    self::Fn('Do', function ($Call)
    {
        return isset($_GET['XDEBUG_SESSION_START']);
    });
