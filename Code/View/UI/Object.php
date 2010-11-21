<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: 
     * @package Codeine
     * @subpackage Drivers
     * @version 
     * @date 18.11.10
     * @time 5:46
     */

    $Process = function ($Call)
    {      
        Data::Mount('Layout');
        $Output = Data::Read('Layout', array('ID'=>'Objects/'.$Call['Item']['Entity'].'/'.$Call['Item']['Plugin']));

        $Fusers = array('Key');
        
        foreach ($Fusers as $Fuser)
            $Output = Code::Run(array(
                           'F' => 'View/Fusers/Fusion',
                           'D' => $Fuser,
                           'Body' => $Output,
                           'Data' => $Call['Item']
                      ));
        
        return $Output;
    };