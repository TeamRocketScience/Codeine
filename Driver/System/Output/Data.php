<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Data Output
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 16.11.10
     * @time 3:42
     */

    self::Fn('Output', function ($Call)
    {
        return Data::Create('Output', array('ID'=>$Call['Call'], 'Value'=>$Call['Input']));
    });