<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Dumper
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 16.11.10
     * @time 3:54
     */

    self::Fn('Do', function ($Call)
    {
        return var_export($Call['Input'], true);
    });