<?php

    /* OSWA Codeine
    * @author BreathLess
    * @type Codeine Core Module
    * @description: NextGen Code
    * @package Core
    * @subpackage Driver
    * @version 2.5
    * @date 07.04.11
    * @time 13.48
    */

    final class Code extends Core
    {
        private static $_Locked = false;

        protected static $_Stack = array();
        protected static $_Tree = array();
        protected static $_Contracts = array();
        protected static $_Functions = array();
        protected static $_Aliases = array();

        protected static $_LastCall = null;
        protected static $_Registration = array();

        public static $Counters = array('Call' => 0, 'On' => 0, 'On.Catched' => 0);

        protected static function _LockCode()
        {
            return self::$_Locked = !self::$_Locked;
        }

        public static function Initialize()
        {
            self::$_Stack = new SplStack();
            self::$_Contracts['Default'] =  Data::Read('Contract::Default', Core::Kernel);
        }

        public static function On($Event, $Data = array())
        {
            self::$Counters['On']++;

            if (is_array($Data))
                $Data['Event'] = $Event;

            $Hooks = Core::getOption('Hooks::' . $Event);

            if (!empty($Hooks))
            {
                self::$Counters['On.Catched']++;
                return self::Run(
                    array(
                         'N' => 'Code.Runners.Feed',
                         'F' => 'Run',
                         'Input' => $Hooks,
                         'Data' => $Data)
                    , Core::Kernel);
            }

            //else
            //    self::On('Code.Event.NotSpecified', array('NewEvent' => $Event));

            if (Trace)
                echo 'E: '.$Event.'<br/>';
        }

        public static function isValidCall($Call)
        {
            return is_array($Call) && isset($Call['N']);
        }

        protected static function _Route($Call)
        {
            $NewCall = null;

            // Для каждого определенного роутера...
            $Routers = Core::getOption('Core/Code::Drivers.Routers');

            foreach ($Routers as $Router)
            {
                // Пробуем роутер из списка...
                $NewCall = Code::Run(
                    array(
                         'N' => 'Code.Routers.'.$Router,
                         'F' => 'Route',
                         'Call' => $Call
                    ), Core::Kernel
                );

                // Если что-то получилось, то выходим из перебора
                if (self::isValidCall($NewCall))
                    break;
            }

            // Если ни один роутер не вернул результата
            if ($NewCall === null)
                self::On('Code.Routing.Failed', array('Call' => $Call));
            else
                $Call = $NewCall;
            
            return $Call;
        }

        public static function Fn($Function, $Code = null)
        {
            if (is_array($Function))
            {
                foreach ($Function as $cFn)
                    self::Fn($cFn, $Code);
            }
            else
            {
                if (null !== $Code)
                {
                    if (false !== $Code)
                        self::$_Functions[self::$_Registration][$Function] = $Code;
                    else
                        unset(self::$_Functions[self::$_Registration][$Function]);
                }
                else
                {
                    if (isset(self::$_Functions[self::$_Registration][$Function]))
                        return self::$_Functions[self::$_Registration][$Function];
                    else
                        return null;
                }
            }
        }

        protected static function _LoadSource($Call)
        {
            if (!isset($Call['Contract']) || !is_array($Call['Contract']))
                $Call['Contract'] = array();

            if (!isset($Call['Contract']['Engine']))
                $Call['Contract']['Engine'] = 'Default';

            switch ($Call['Contract']['Engine'])
            {
                case 'Code':
                    if (isset($Call['Contract']['Source']))
                        return eval('self::Fn(\'' . $Call['F'] . '\',
                                function ($Call) {' . $Call['Contract']['Source'] . '});');
                    else
                        self::On('Code.Source.InContractNotSpecified', $Call);
                break;

                case 'Data':
                    if (isset($Call['Contract']['Source']))
                        return eval('self::Fn(\'' . $Call['F'] . '\',
                                function ($Call) {' . Data::Read('Source', $Call['Contract']['Source']) . '});');
                    else
                        self::On('Code.Source.DataInContractNotSpecified', $Call);
                break;

                default:

                    if (isset($Call['Contract']['Source']))
                        $Filename = Data::Locate('Code', $Call['Contract']['Source']);
                    else
                        if (isset($Call['D']))
                            $Filename = Data::Locate('Code', $Call['N'] . '/' . $Call['D'] . '.php');
                        else
                            $Filename = Data::Locate('Code', $Call['N'] . '.php');

                    if (null !== $Filename)
                        return (include_once $Filename);
                    else
                    {
                        $Call['Filename'] =  array(
                            $Call['N'] . '.php',
                            $Call['N'] . '/' . $Call['D'] . '.php');

                        self::On('Code.LoadSource.Include.FileNotFound', $Call);
                    }
                break;
            }
        }

        protected static function _Do($Call)
        {
            $F = self::Fn($Call['F']);

            if (is_callable($F) or ($F instanceof Closure))
                return $F($Call);
            else
            {
                self::On('Code.Function.IsNotCallable', $Call);
                return null;
            }
        }

        /**
         * @description Главный метод Codeine, запускает код.
         * @param  $Call - запрос, ассоциативный массив или совместимый аргумент
         * @return mixed $Result - результат
         */

        public static function Run($Call, $Mode = Core::User)
        {
            self::$Counters['Call']++;

            if (!self::isValidCall($Call))
                $Call = self::_Route($Call);

            $Call['N'] = strtr($Call['N'], '.', DS);
            $Call['Mode'] = $Mode;
            
            self::$_Stack->push($Call);

            if (Trace)
                echo str_pad('', self::$_Stack->count(), "\t")
                     .'R: '.$Call['N'].'>'.$Call['F']."\n";

            if ($Mode == Core::User)
            {
                // Выполняем плагины поведения
                $Behaviours = Core::getOption('Core/Code::Drivers.Behaviours');
                foreach ($Behaviours as $Behaviour)
                    if (!isset($Call['No.'.$Behaviour]))
                        $Call = Code::Run(
                            array('N' => 'Code.Behaviours.'.$Behaviour,
                                  'F' => 'beforeRun',
                                  'Input' => $Call), Core::Kernel);
            }

            if (!isset($Call['Result']))
            {
                self::$_Registration = $Call['N'];

                // Если функции нет, подгружаем код
                if (self::Fn($Call['F']) === null)
                    self::_LoadSource($Call);

                // Выполняем!
                $Call['Result'] = self::_Do($Call);
            }
            
            if ($Mode == Core::User)
                foreach ($Behaviours as $Behaviour)
                    if (!isset($Call['No.'.$Behaviour]))
                        $Call = Code::Run(
                            array('N' => 'Code.Behaviours.'.$Behaviour,
                                  'F' => 'afterRun',
                                  'Input' => $Call), Core::Kernel);

            self::$_Stack->pop();
            return $Call['Result'];
        }

        protected static function _is($Key, $Contract)
        {
            if (isset($Contract[$Key]))
                return Core::Any($Contract[$Key]);
            else
                return false;
        }

        public static function Current($Call = null)
        {
            if (null !== $Call)
                return Core::mergeOptions(self::$_Stack[0], $Call);
            else
                return self::$_Stack[0];
        }

        public static function Parent($Call = null)
        {
            return self::$_Stack[1];
        }
    }
