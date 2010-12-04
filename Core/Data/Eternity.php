<?php
/*
 * Data API Implementation E9
 * Codeine5, 2010
 */

    final class Data extends Component
    {
        private   static $_Locked  = false;
        protected static $_Conf    = array();
        protected static $Data     = array();
        protected static $_Stores  = array();
        protected static $_Points  = array();

        protected static function _LockData ()
        {
            return self::$_Locked = !self::$_Locked;
        }

        private static function _Point2Storage($Point)
        {
            if (isset(self::$_Conf['Points'][$Point]))
            {
                if (isset(self::$_Conf['Points'][$Point]['Store']))
                    return self::$_Conf['Points'][$Point]['Store'];
                else
                    Code::On(__CLASS__, 'errDataStoreNotFound', $Point);
            }
            else
                Code::On(__CLASS__, 'errDataPointNotFound', $Point);
        }

        public static function Initialize()
        {
            self::$_Conf = self::_Configure(__CLASS__);
        }

        public static function Shutdown ()
        {
            
        }

        public static function Structure($Scope)
        {
            
        }

        public static function Mount($Point)
        {
            if (isset(self::$_Conf['Points'][$Point]['Ring']))
                $Mode = self::$_Conf['Points'][$Point]['Ring'];
            else
                $Mode = 2;
            
            self::Connect(self::_Point2Storage($Point), $Mode);
            return self::$_Points[$Point] = $Point;
        }

        public static function Unmount()
        {

        }
        
        public static function Connect ($Store, $Mode)
        {
            if (!isset(self::$_Stores[$Store]))
            {
                if ((self::$_Stores[$Store] =
                    Code::Run(array(
                               'F' => 'Data/Store/'.self::$_Conf['Stores'][$Store]['Type'].'::Connect',
                               'Point' => self::$_Conf['Stores'][$Store]
                        ),$Mode)) !== null)
                    Code::On(__CLASS__, 'errDataStoreConnectFailed', $Store);
            }
            else
                Code::On(__CLASS__, 'errDataStoreNotFound', $Store);

            return self::$_Stores[$Store];
        }

        protected static function _Route ($Call)
        {
            // Для каждого определенного роутера...
            foreach (self::$_Conf['Routers'] as $Router)
            {
                // Пробуем роутер из списка...
                $NewCall = Code::Run(
                    array(
                        'F'=> 'Data/Routers/'.$Router.'::Route',
                        'Input' => $Call
                    ), Code::Ring1
                );

                // Если что-то получилось, то выходим из перебора
                if (self::isValidCall($NewCall))
                    break;
            }

            // Если хоть один роутер вернул результат...
            if ($NewCall !== null)
                $Call = $NewCall;
            else
                Code::On(__CLASS__, 'errDataRoutingFailed', $Call);

            return $Call;
        }

        public static function Disconnect($Store)
        {
            return Code::Run(array(
                           'F' => 'Data/Store/'.self::$_Conf['Stores'][$Store]['Type'].'::Disconnect',
                           'Point' => self::$_Stores[$Store]
                      ), Code::Ring1);
        }

        protected static function _CRUD($Method, $Call, $Mode = Code::Ring2)
        {
            if (!self::isValidCall($Call))
                $Call = self::_Route($Call);

            if (!isset(self::$_Points[$Call['Point']]))
                self::Mount($Call['Point']);

            if (isset(self::$_Conf['Points'][$Call['Point']]['Prefix']))
                $Prefix = self::$_Conf['Points'][$Call['Point']]['Prefix'];
            else
                $Prefix = '';

            if (isset(self::$_Conf['Points'][$Call['Point']]['Postfix']))
                $Postfix = self::$_Conf['Points'][$Call['Point']]['Postfix'];
            else
                $Postfix = '';

            $Store = self::_Point2Storage($Call['Point']);

            return Code::Run(array(
                           'F' => 'Data/Store/'.self::$_Conf['Stores'][$Store]['Type'].'::'.$Method,
                           'Point' => self::$_Conf['Points'][$Call['Point']],
                           'Store' => self::$_Stores[$Store],
                           'Data' => $Call,
                           'Postfix' => $Postfix,
                           'Prefix' => $Prefix
                      ), $Mode);
        }

        public static function Create($Call, $Mode = Code::Ring2)
        {
            return self::_CRUD('Create', $Call, $Mode);
        }

        public static function Read($Call, $Mode = Code::Ring2)
        {
            $Result = self::_CRUD('Read', $Call, $Mode);
            
            if (isset(self::$_Conf['Points'][$Call['Point']]['Format']) && $Result !== null)
                $Result = Code::Run(array(
                              'F' => 'Data/Formats/'.
                                     self::$_Conf['Points'][$Call['Point']]['Format'].'::Decode',
                              'Input' => $Result
                                 ), $Mode);
            
            return $Result;
        }

        public static function __callStatic($Operation, $Arguments)
        {
            return self::_CRUD($Operation, $Arguments[0], isset($Arguments[1])? $Arguments[1]:Code::Ring2);
        }

        public static function Locate ($Path, $Name)
        {
            if (!is_array($Name))
                $Name = array($Name);

            foreach ($Name as $cName)
            {
                $cName = self::Path($Path).$cName;

                if (file_exists(Root.$cName))
                    $R = Root.$cName;
                elseif (file_exists(Engine.$cName))
                    $R = Engine.$cName;
                else
                    foreach (self::$_Conf['Shared'] as $Shared)
                        if (file_exists($Shared.DS.$cName))
                        {
                            $R = $Shared.DS.$cName;
                            break;
                        }
           }

           if (isset($R))
               return $R;
           else
               Code::On(__CLASS__, 'errLocateNotFound', $Path.':'.$Name);
        }

        public static function Path ($Key)
        {
            if (isset(self::$_Conf['Paths'][$Key]))
                return self::$_Conf['Paths'][$Key].'/';
            else
                return $Key.'/';
        }

        public static function isValidCall($Call)
        {
            return (is_array($Call) && isset($Call['Point']));
        }
    }
