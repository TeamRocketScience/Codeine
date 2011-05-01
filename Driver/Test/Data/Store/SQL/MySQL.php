<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: MySQL Driver Test
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 01.05.11
     * @time 18:13
     */

    self::Fn('Run', function ($Call)
    {
        $Results = array();
        $Methods = array('Open','Read');

        foreach ($Methods as $Method)
            $Results[$Method] =
                Code::Run(
                    array(
                        'N' => 'Test.Data.Store.SQL.MySQL',
                        'F' => $Method
                    )
                );

        return $Results;
    });


    self::Fn('Open', function ($Call)
    {
        return Data::Open('MySQL', Core::Kernel);
    });

    self::Fn('Read', function ($Call)
    {
        $Result = Data::Read(
            array(
                'Point' => 'Page',
                'ID'    => 'About'
            )
        );
        
        return is_array($Result);
    });
