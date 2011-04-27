<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: 
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 17.04.11
     * @time 17:37
     */

    self::Fn('Do', function ($Call)
    {
        $Call = $Call['Input'];

        // Прочитать контракт по умолчанию
        $Contract = array ($Call['F'] => array());

        // Прочитать контракт группы, если есть

        if (isset($Call['N']))
        {
            $GroupContract = Data::Read('Contract::'.$Call['N'], Core::Kernel);

            if ($GroupContract !== null)
            {
                if (isset($GroupContract[$Call['F']]))
                    $Contract = Core::mergeOptions($Contract, $GroupContract[$Call['F']]);
            }
        }

        // Прочитать контракт драйвера, если есть
        if (isset($Call['D']))
        {
            $DriverContract = Data::Read('Contract::'.$Call['N'].'/'.$Call['D'], Core::Kernel);

            if ($DriverContract !== null)
            {
                if (isset($DriverContract[$Call['F']]))
                    $Contract = Core::mergeOptions($Contract, $DriverContract[$Call['F']]);
            }
        }

        // Внедрить наследования
        // Внедрить примеси

        if (isset($Call['Override']))
            $Contract = Core::mergeOptions($Contract, $Call['Override']);

        return $Contract;
    });
