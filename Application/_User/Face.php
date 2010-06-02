<?php

if (Client::$Authorized)
    {
        $Applications = new Collection('_Application');
        $Applications->Query('=Faceable=True');

        $FaceTypes = $Applications->Names;
        
        if (in_array(self::$Mode, $FaceTypes))
                {
                    $Face = new Object(self::$Mode, self::$ID);

                    if ($Face->Get('Owner') == Client::$UID)
                        Client::$User->Set('Face', (string) $Face);
                    else
                        throw new WTF (403);

                    Client::$User->Save();
                }

                foreach ($FaceTypes as $FaceType)
                    {
                        $Faces = new Collection($FaceType, '=Owner='.Client::$UID);
                        $Faces->Load();
                        
                        foreach ($Faces->_Items as $Item)
                        {
                            if ((string) $Item == Client::$User->Get('Face'))
                                $BFS = 'Objects/'.$FaceType.'/'.$FaceType.'_Selected';
                            else
                                $BFS = 'Objects/'.$FaceType.'/'.$FaceType.'_Select';

                            Page::AddBuffered(Page::Fusion ($BFS, $Item));
                        }

                    }

                Page::Flush();
    }
    else
        Client::Redirect(Host.'web/Gate/Step1');