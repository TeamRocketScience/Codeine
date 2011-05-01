<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: 
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 01.05.11
     * @time 18:56
     */

    self::Fn('Run', function ($Call)
    {
        $Results = array();
        $Methods = array('Open','Create', 'Read', 'Update', 'Delete');

        foreach ($Methods as $Method)
            $Results[$Method] =
                Code::Run(
                    array(
                        'N' => 'Test.Data.Store.File.Flat',
                        'F' => $Method
                    )
                );

        return $Results;
    });

    self::Fn('Open', function ($Call)
    {
        return Data::Mount('Data', Core::Kernel);
    });

    self::Fn('Create', function ($Call)
    {
        return Data::Create(
            array(
                'Point' => 'Data',
                'ID' => 'test.file',
                'Data' => 'Hello, world!'
            )
        );
    });

    self::Fn('Read', function ($Call)
    {
        return 'Hello, world!' == Data::Read(
            array(
                'Point' => 'Data',
                'Where' =>
                    array('ID' => 'test.file')
            )
        );
    });

    self::Fn('Update', function ($Call)
    {
        return 4 == Data::Update(
            array(
                'Point' => 'Data',
                'ID' => 'test.file',
                'Data' => 'WTF?'
            )
        );
    });

    self::Fn('Delete', function ($Call)
    {
        return Data::Delete(
            array(
                'Point' => 'Data',
                'ID' => 'test.file'
            )
        );
    });
