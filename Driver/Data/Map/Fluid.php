<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Fluid Schema (ex SQL3D)
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 15.11.10
     * @time 23:52
     */

    self::Fn('afterRead', function ($Call)
    {
        $Data = array();

        foreach ($Call['Input'] as $Row)
            $Data[$Row['ID']][$Row['K']][] = $Row['V'];

        foreach ($Data as &$Rows)
            foreach ($Rows as $Key => &$Value)
                if (count($Value) == 1)
                    $Value = $Value[0];

        return $Data;
    });
