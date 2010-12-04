<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Index
     * @description: Front Controller
     * @package Codeine
     * @subpackage Core
     * @version 5.0
     * @date 01.12.10
     * @time 14:39
     */
    
    try
    {
        defined('Root') || define('Root', __DIR__);

        include 'Core.php';

        Code::Run(array('F' => 'Code/Flow/Front::Run'));

    }
    catch (Exception $e)
    {
        echo $e->getMessage();
        Code::On('Front','Exception',
                 array('Message',$e->getMessage()));
    }