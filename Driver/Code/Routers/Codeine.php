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

        if (!is_string($Call['Call']))
            return null;
         
        if (mb_strpos($Call['Call'], '?') !== false)
        {   
            list($Call['Call'], $Query) = explode('?', $Call['Call']);
            parse_str($Query, $Routed);
        }

        if (mb_strpos($Call['Call'], '.') !== false)
            list($Call['Call'], $Routed['Format']) = explode('.', $Call['Call']);

        if (mb_strpos($Call['Call'], '/') !== false)
        {
            $Call = explode ('/', $Call['Call']);

            $CC = count($Call);

            if ($CC >= 3)
                list ($Routed['Action'], $Routed['Entity'], $Routed['ID']) = $Call;
            else
                return null;

            if ($CC>3)
                for ($ic = 3; $ic < $CC; $ic+=2)
                    if (isset($Call[$ic+1]))
                        $Routed[$Call[$ic]] = $Call[$ic+1];
                    else
                        $Routed[$Call[$ic]] = true;

            $Routed['N'] = 'Entity.'.$Routed['Action'];
            $Routed['F'] = $Routed['Action'];
        }

      $Routed['Data'] = $_POST;

      return $Routed;
    });
