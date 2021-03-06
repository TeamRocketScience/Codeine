<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Driver for "Point::ID" scheme
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 24.11.10
     * @time 0:08
     */

    self::Fn('Route', function ($Call)
    {
        if (is_string ($Call['Input']) && mb_strpos($Call['Input'],'::'))
        {
            $Output = array('Where' => array());
            list($Output['Point'], $Output['Where']['ID']) = explode('::',$Call['Input']);
        }
        else
            $Output = null;

        return $Output;
    });