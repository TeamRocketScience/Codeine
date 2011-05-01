<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: 
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 01.05.11
     * @time 18:19
     */

    self::Fn('Run', function ($Call)
    {
        $Items = array();
        
        $TestSuites = Code::Run(
            array(
                'N'=>'Code.Source.Enumerate',
                'F'=>'Drivers',
                'Namespace' => 'Test'
            )
        );

        foreach ($TestSuites as $TestSuite)
            $Results[$TestSuite] = Code::Run(array('N' => $TestSuite, 'F' => 'Run'));

        foreach ($Results as $TestSuite => $TestResults)
        {
            $Call['Items'][] =
                array('UI'=>'Heading', 'Level'=>2, 'Data' => $TestSuite);

            foreach ($TestResults as $TestCase => $TestResult)
            {
                $Class = $TestResult? 'Positive': 'Negative';
                
                $Call['Items'][$TestSuite.$TestCase] =
                    array('UI'=>'Badge',
                          'Class' => array($Class),
                          'Value' => $TestCase);

            }
        }

        return $Call;
    });
