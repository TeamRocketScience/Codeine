<?php

  self::$Object = new Object(self::$Name, Client::$Language);
  
  if (self::$Object->Get('Owner') == Client::$UID or self::$Object->Get('Owner') == null)
      {
        self::$Object->Add(Server::Arg('Token'), Server::Arg('Text'));
        self::$Object->Inc('Words', 1);
        if (Client::$Language != 'ru_RU')
          {
              $Russian = new Object(self::$Name, 'ru_RU');
              self::$Object->Set('Percent', round(self::$Object->Get('Words')/$Russian->Get('Words')*100));
          }
        self::$Object->Save ();


        View::Body(Server::Arg('Text'));
      }