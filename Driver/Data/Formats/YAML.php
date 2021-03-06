<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: YAML PECL Wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 14.11.10
     * @time 16:08
     */

    self::Fn('Decode', function ($Call)
    {
        return yaml_parse($Call['Value']);
    });

    self::Fn('Encode', function ($Call)
    {
        return yaml_emit($Call['Value']);
    });

