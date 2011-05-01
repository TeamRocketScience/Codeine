<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Flat file
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 23.11.10
     * @time 20:49
     */

    self::Fn('Open', function ($Call)
    {
        return $Call['Options']['DSN'];
    });

    self::Fn('Close', function ($Call)
    {
        return true;
    });

    self::Fn('Read', function ($Call)
    {
        Code::On('Data.FS.Query',$Call);
        $Filename = Root.$Call['Link'].$Call['Options']['Scope'].'/'.$Call['Where']['ID'];
        
        if (is_readable($Filename))
            return file_get_contents($Filename);
        else
        {
            Code::On('Eee');
            return null;
        }
    });

    self::Fn('Create', function ($Call)
    {
        Code::On('Data.FS.Query',$Call);
        $Filename = Root.$Call['Link'].$Call['Options']['Scope'].'/'.$Call['ID'];

        return file_put_contents($Filename, $Call['Data']);
    });

    self::Fn('Update', function ($Call)
    {
        $Filename = Root.$Call['Link'].$Call['Options']['Scope'] . '/' . $Call['ID'];

        if (is_writeable($Filename))
            return file_put_contents($Filename, $Call['Data']);
        else
        {
            Code::On('Eee');
            return null;
        }
    });

    self::Fn('Delete', function ($Call)
    {
        Code::On('Data.FS.Query',$Call);
        $Filename = Root.$Call['Link'].$Call['Options']['Scope'].'/'.$Call['ID'];

        return unlink($Filename);
    });
