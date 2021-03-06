<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Cache Executor
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 24.11.10
     * @time 1:37
     */

    self::Fn('Run', function ($Call)
    {
        $CID = Code::Run(array(
                              'N' => 'Data.CacheID',
                              'F' => 'Do',
                              'Input' => $Call['Call']
                         ));

        if (null !== ($Cached = Data::Read(
                array('Point' => 'CodeCache',
                      'Where'=>
                        array('ID' => $CID)))))
        {
            Code::On('onCodeCacheHit', $Call);
            return $Cached;
        }
        else
        {
            $Result = Code::Run($Call['Call'], Core::User);
            Data::Create(array(
                              'Point' => 'CodeCache',
                              'ID' => $CID,
                              'Data' => $Result
                         ));
            Code::On('onCodeCacheMiss', $Call);
            return $Result;
        }
    });
