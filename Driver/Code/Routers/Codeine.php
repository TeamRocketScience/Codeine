<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Codeine Router
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 10.11.10
     * @time 23:16
     */

     self::Fn('Route', function ($Call)
     {
        $Routed = array();

        if (mb_strpos($Call['Call'], '?') !== false)
        {
            list($Call['Call'], $Query) = explode('?', $Call['Call']);
            parse_str($Query, $Routed['Data']);
        }

        if (!is_string($Call['Call']))
            return $Call;
        
        $Call = explode ('/', mb_substr($Call['Call'],1));

        list ($Routed['F'], $Routed['Entity'], $Routed['ID']) = $Call;

        $CC = count($Call);
        
        if ($CC>3)
            for ($ic = 3; $ic < $CC; $ic+=2)
                if (isset($Call[$ic+1]))
                    $Routed[$Call[$ic]] = $Call[$ic+1];
                else
                    $Routed[$Call[$ic]] = true;

        $Routed['F'] = 'App/'.$Routed['F'].'::'.$Routed['F'];



      return $Routed;
    });