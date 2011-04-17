<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Chain Runner
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 24.11.10
     * @time 0:30
     */

    self::Fn('Run', function ($Call)
    {
        $Result = Code::Run($Call['Input'][0]);

        unset($Call['Input'][0]);

        foreach ($Call['Input'] as $IX => $OneCall)
        {
            $OneCall['Input'] = $Result;
            $Result = Code::Run($OneCall, $Call['Mode']);
        }

        return $Result;
    });
