<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: 
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 01.05.11
     * @time 18:45
     */

    self::Fn('Run', function ($Call)
    {
        $Results = array();
        $Methods = array('2+2=4');

        foreach ($Methods as $Method)
            $Results[$Method] =
                Code::Run(
                    array(
                        'N' => 'Test.Science.Math.Algebra',
                        'F' => $Method
                    )
                );

        return $Results;
    });

    self::Fn('2+2=4', function ($Call)
    {
        return 4 == Code::Run(
            array(
                'N' => 'Science.Math.Algebra',
                'F' => 'Add',
                'A' => 2,
                'B' => 2
            ));
    });
