<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Profile Executor
     * @package Codeine
     * @subpackage Drivers
     * @version 0,1
     * @date 24.11.10
     * @time 3:04
     */

    self::Fn('Run', function ($Call)
    {
        // TODO Profiling
        return Code::Run($Call['Call']);
    });