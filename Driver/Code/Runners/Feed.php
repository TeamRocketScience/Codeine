<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Feed Runner
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 24.11.10
     * @time 1:26
     */

    self::Fn('Run', function ($Call)
    {
        $Output = array();

        foreach ($Call['Input'] as $IX => $OneCall)
            $Output[$IX] = Code::Run(
                            Core::mergeOptions(array('Data'=>$Call['Data']), $OneCall),
                                $Call['Mode']);

        return $Output;
    });
