<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Ops Reporter
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 22.11.10
     * @time 3:50
     */

    self::Fn('Generate', function ($Call)
    {
        $Op = Profiler::Autotest();

        foreach ($Call['Results'] as $Source => $Result)
            {
                if (!isset ($Call['Ticks'][$Source][1]))
                    $Call['Ticks'][$Source][1] = self::Lap('Root');
                elseif ($Result == 0)
                    $Result = 0.000001;

                $Report[$Source] = array('S' => $Source, 'T' => round($Result/$Op));
            }

        return $Report;
    });