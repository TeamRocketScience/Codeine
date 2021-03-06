<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Candy passwords
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 04.12.10
     * @time 15:24
     */

    self::Fn('Get', function ($Call)
    {
        $length = $Call['Length'];

        $vowels = array('a', 'e', 'u');
        $cons = array('b', 'c', 'd', 'g', 'h', 'j', 'k','m', 'n', 'p', 'r', 's', 't', 'u', 'v', 'w', 'tr',
        'cr', 'br', 'fr', 'th', 'dr', 'ch', 'ph', 'wr', 'st', 'sp', 'sw', 'pr', 'sl', 'cl');

        $num_vowels = count($vowels);
        $num_cons = count($cons);

        for($i = 0; $i < $length; $i++)
            $password .= $cons[rand(0, $num_cons - 1)] . $vowels[rand(0, $num_vowels - 1)];

        return strtoupper(substr($password, 0, $length));
    });