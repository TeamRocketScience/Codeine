<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: tar xvf command wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 21.11.10
     * @time 2:24
     */

    self::Fn('Exec', function ($Call)
    {
        return passthru('tar xvf '.$Call['Input']);
    });