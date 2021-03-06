<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: mergeOptions Runner
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 24.11.10
     * @time 0:58
     */

    self::Fn('Run', function ($Call)
    {
        $Output = array();

        foreach ($Call['Call'] as $IX => $SingleCall)
            $Output[$IX] = self::Run($SingleCall, $Call['Mode']);

        $Output = array_merge($Output);
        return $Output;
    });
