<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Image Recognition Routing
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 10.11.10
     * @time 23:16
     */

    $Route = function ($Call)
    {
        return Code::Run(
            array('F'=>'Recognition/Image/Recognize', 'Value' => $Call['Call'])
        );
    };