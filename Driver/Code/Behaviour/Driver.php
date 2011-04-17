<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: 
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 07.04.11
     * @time 15:11
     */

    self::Fn('Select', function ($Call)
    {
        if (!isset($Call['Input']['D']))
            {
                if (isset ($Call['Input']['Contract']) && isset($Call['Input']['Contract']['Driver']) && null !== $Call['Input']['Contract']['Driver'])
                {
                    if (isset($Call['Input']['Contract']['Driver'][Environment]))
                        $Call['Input']['D'] = $Call['Input']['Contract']['Driver'][Environment];
                    else
                        $Call['Input']['D'] = $Call['Input']['Contract']['Driver']['Default'];
                }
                else
                    $Call['Input']['D'] = null;

                $Call['Input']['D'] = Core::Any($Call['Input']['D']);
            }
        
        return $Call['Input'];
    });
