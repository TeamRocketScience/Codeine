<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: 
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 25.02.11
     * @time 16:29
     */

    self::Fn('Catch', function ($Call)
    {
        require_once(Engine.'Library/PhpConsole.php');
        PhpConsole::start(true, true, dirname(__FILE__));
        
        return debug(json_encode($Call['Data']));
    });