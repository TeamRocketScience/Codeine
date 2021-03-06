<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: MCrypt Codeine Driver Generator 
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 21.11.10
     * @time 3:27
     */

    self::Fn('Generate', function ($Call)
    {
        $algos = mcrypt_list_algorithms();
        $modes = mcrypt_list_modes();

        foreach ($algos as $algo)
        {
            $algo2 = strtoupper(str_replace('-','_',$algo));
            $Functions = array();

            foreach ($modes as $mode)
            {
                $mode = strtoupper($mode);
                $Functions['Encrypt'.$mode] = 'return mcrypt_encrypt(MCRYPT_'.$algo2.', $Call[\'Key\'], $Call[\'Input\'], MCRYPT_MODE_'.$mode.');';

                $Functions['Decrypt'.$mode] = 'return mcrypt_decrypt(MCRYPT_'.$algo2.', $Call[\'Key\'], $Call[\'Input\'], MCRYPT_MODE_'.$mode.');';
            }
  // TODO DATA Write
            file_put_contents(Engine.Data::Path('Code').'Security/Cipher/MCrypt/'.$algo.'.php', Code::Run(array(
                           'N' => 'Code.Source.Driver',
                           'F' => 'Generate',
                           'Description'=> $algo2.' MCrypt Wrapper',
                           'Version' => '5.0',
                           'Functions' => $Functions
                      )));
        }
    });
