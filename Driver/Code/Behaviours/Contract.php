<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: 
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 17.04.11
     * @time 17:23
     */

    self::Fn('beforeRun', function ($Call)
    {
        $Call = $Call['Input'];
        
        $Contract = Code::Run(
            array(
                'N' => 'Code.Contract.Loader',
                'F' => 'Do',
                'Input' => $Call
            ), Core::Kernel
        );

        $Call['Contract'] = $Contract;

        return $Call;

    });

    self::Fn('afterRun', function ($Call)
    {
        return $Call['Input'];
    });
