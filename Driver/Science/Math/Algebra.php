<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: 
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 18.03.11
     * @time 6:12
     */

    self::Fn('Add', function ($Call)
    {
        return $Call['A'] + $Call['B'];
    });

    self::Fn('SquareRoot', function ($Call)
    {
        return sqrt($Call['Input']);
    });
