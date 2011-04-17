<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Classic Front Controller
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 01.12.10
     * @time 14:39
     */

    self::Fn('Run', function ($Call)
    {
        $Interfaces = Core::getOption('Core/Code::Drivers.Inputs');

        foreach ($Interfaces as $Interface)
            if (Code::Run(
                    array(
                         'N'=>'System.Input.'.$Interface,
                         'F' => 'Detect')))
                break;

        $Input = Code::Run(array('N' => 'System.Input.'.$Interface, 'F' => 'Input'));

        return Code::Run(
            array(
                'N' => 'Code.Runners.Chain',
                'F' => 'Run',
                'Input' =>  array(
                                $Input,
                                array('N' => 'View.Render', 'F' => 'Do', 'D'=>'Codeine'),
                                array('N' => 'System.Output.HTTP', 'F' => 'Output')
                            )
            ));
    });
