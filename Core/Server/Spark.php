<?php

    class Server
    {
        private static $_R = array();
        private static $_Paths = array();
        private static $_Shared = array();

        public static $Drivers;
        public static $REST;
        public static $Conf;
        public static $Domain;

        public static function Data($Data = null)
        {
            if (null !== $Data)
                return self::$_R = $Data;
            else
                return self::$_R;
        }

        public static function Path ($Key)
        {
            if (isset(self::$_Paths[$Key]))
                    $R = self::$_Paths[$Key].'/';
                else
                    $R = null;
            return $R;
        }

        public static function Arg ($Key, $Value = null)
        {
            if ($Value === null)
            {
                if (isset(self::$_R[$Key]) && !empty(self::$_R[$Key]))
                    $R = self::$_R[$Key];
                else
                    $R = null;

                return $R;
            }
            else
                return self::$_R[$Key] = $Value;
        }

        public static function Initialize()
        {
            Profiler::Go(__METHOD__);

            self::$_Paths = Core::$Conf['Paths'];
            self::$_Shared = Core::$Conf['Shared'];

            set_error_handler('Log::PHPError');
            register_shutdown_function('Server::Shutdown');

            Log::Initialize();
            Data::Initialize();
            Code::Initialize();

            View::Initialize();
            Application::Initialize();

            Code::Hook('Core', __CLASS__, 'afterInitialize');

            self::$REST = strtolower(self::$_R['REQUEST_METHOD']);
            // FIXME

            Profiler::Stop(__METHOD__);
        }

        public static function FatalHandler($Data)
        {
            if ((mb_strpos($Data,'Fatal error') !== false) and Core::$Conf['Options']['FatalCatch'] == true)
            {
                if (Client::$TrustIP)
                    $Data2 = $Data;
                else
                    $Data2 = file_get_contents(EngineShared.Layout.'/Errors/Fatal.html');
            }
                $Data2 = $Data;

            //if (mb_strpos(Server::Arg('HTTP_ACCEPT_ENCODING'), 'gzip') !== false)
            //    $Data = ob_gzhandler($Data, 9);

            // self::Shutdown();
            return $Data2;
        }

        public static function Shutdown ()
        {
            try
                {
                    Data::Shutdown();

                    if (!Core::$Crash)
                        Client::Shutdown();

                    if (Client::$TrustIP or isset(Core::$Conf['Options']['Debug']))
                    {
                        Profiler::Output();
                    }

                    ob_flush();
                }
            catch (WTF $e)
            {
                $e->Panic();
            }
        }

        public static function Locate ($Path, $Name)
        {
            $Name = self::Path($Path).$Name;

            if (file_exists(Root.$Name))
                $R = Root.$Name;
            elseif (file_exists(Engine.$Name))
                $R = Engine.$Name;
            elseif (file_exists(Engine.Shared.$Name))
                $R = EngineShared.$Name;

            foreach (self::$_Shared as $Shared)
                if (file_exists($Shared.'/'.$Name))
                {
                    $R = $Shared.'/'.$Name;
                    break;
                }

           if (isset($R))
               return $R;
           else
               return Log::Error ('Not located '.$Name);
        }
    }
