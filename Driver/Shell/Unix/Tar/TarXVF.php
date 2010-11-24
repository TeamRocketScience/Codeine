<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: tar xvf command wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 21.11.10
     * @time 2:24
     */

    $Exec = function ($Call)
    {
        exec ('tar xvf '.$Call['Input'], $Output);
        return implode("\n", $Output);
    };