<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Start Finalizing Router
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 10.11.10
     * @time 23:16
     */

    self::Fn('Route', function ($Call)
    {
        return Core::$Conf['Options']['Core']['Start'];
    });