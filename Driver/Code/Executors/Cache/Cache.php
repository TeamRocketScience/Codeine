<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Cache Executor
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 24.11.10
     * @time 1:37
     */

    self::Fn('Run', function ($Call)
    {
        $CID = Code::Run(array(
                              'F' => 'Data/CacheID/Do',
                              'Input' => $Call['Call']
                         ));

        if (null !== ($Cached = Data::Read(
                array('Point' => 'CodeCache',
                      'Where'=>
                        array('ID' => $CID)))))
        {
            Code::Hook(__CLASS__, 'onCodeCacheHit', $Call);
            return $Cached;
        }
        else
        {
            $Result = Code::Run($Call['Call'], Code::Internal);
            Data::Create(array(
                              'Point' => 'CodeCache',
                              'ID' => $CID,
                              'Data' => $Result
                         ));
            Code::Hook(__CLASS__, 'onCodeCacheMiss', $Call);
            return $Result;
        }
    });