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

        /**
         * @description Создаёт событие, и запускает его обработчики
         * @static
         * @param  $Event - имя события
         * @param array $Data - дополнительные аргументы
         * @return mixed|null
         */
        public static function On($Event, $Data = array())
        {
            self::$Counters['On']++;

            if (is_array($Data))
                $Data['Event'] = $Event;

            $Hooks = Core::getOption('Hooks::' . $Event);

            if ($Hooks !== null)
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

            //echo $Event.'<br/>';
        }

        /**
         * @description Проверяет, является ли аргумент корректным вызовом.
         * @static
         * @param  $Call
         * @return bool
         */
        public static function isValidCall($Call)
        {
            return is_array($Call) && isset($Call['N']);
        }

        /**
         * @static
         * @param  $Call
         * @param int $Mode
         * @return null
         */

        public static function LoadContract($Call)
        {
            if (!isset(self::$_Contracts[$Call['N']][$Call['F']]))
            {
                // Прочитать контракт по умолчанию
                $Contract = array($Call['F'] => self::$_Contracts['Default']);

                // Прочитать контракт группы, если есть

                if (isset($Call['G']))
                {
                    $GroupContract = Data::Read('Contract::'.$Call['N'],Core::Kernel);

                    if ($GroupContract !== null)
                    {
                        if (isset($GroupContract[$Call['F']]))
                            $Contract = Core::mergeOptions($Contract, $GroupContract[$Call['F']]);
                    }
                }

                // Прочитать контракт драйвера, если есть
                if (isset($Call['D']))
                {
                    $DriverContract = Data::Read('Contract::'.$Call['N'].'/'.$Call['D'], Core::Kernel);

                    if ($DriverContract !== null)
                    {
                        if (isset($DriverContract[$Call['F']]))
                            $Contract = Core::mergeOptions($Contract, $DriverContract[$Call['F']]);
                    }
                }

                // Внедрить наследования
                // Внедрить примеси

                self::$_Contracts[$Call['N']][$Call['F']] = $Contract;
            }
            else
                $Contract = self::$_Contracts[$Call['N']][$Call['F']];

            if (isset($Call['Override']))
                $Contract = Core::mergeOptions($Contract, $Call['Override']);

            $Call['Contract'] = $Contract;

            return $Call;
        }

        protected static function _Route($Call)
        {
            $NewCall = null;

            // Для каждого определенного роутера...
            $Routers = Core::getOption('Core/Code::Routers');

            foreach ($Routers as $Router)
            {
                // Пробуем роутер из списка...
                $NewCall = Code::Run(
                    array(
                         'N' => 'Code.Routers',
                         'F' => 'Route',
                         'D' => $Router,
                         'Call' => $Call
                    ), Core::Kernel
                );

                // Если что-то получилось, то выходим из перебора
                if (self::isValidCall($NewCall))
                    break;
            }

            // Если хоть один роутер вернул результат...
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
                        self::$_Functions[self::$_Registration['N']][$Function] = $Code;
                    else
                        unset(self::$_Functions[self::$_Registration['N']][$Function]);
                }
                else
                {
                    if (isset(self::$_Functions[self::$_Registration['N']][$Function]))
                        return self::$_Functions[self::$_Registration['N']][$Function];
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

            if (self::$_Stack->count() > Core::getOption('Core/Code::Limit.NestedCalls'))
                self::On('Code.NestedCalls.Overflow', $Call);

            $Return = null;

            if (!self::isValidCall($Call))
                $Call = self::_Route($Call);

            $Call['N'] = strtr($Call['N'], '.', DS);
            $Call['Mode'] = $Mode;
            
            self::$_Stack->push($Call);

            // Загружаем контракт
            if ($Mode == Core::User)
            {
                $Call = self::LoadContract($Call);
                // Выбираем драйвер
                $Call = Code::Run(
                    array('N' => 'Code.Behaviour.Driver',
                          'F' => 'Select',
                          'Input' => $Call), Core::Kernel);
            }

            self::$_Registration = $Call['N'];

            // Если функции нет, подгружаем код
            if (self::Fn($Call['F']) === null)
                self::_LoadSource($Call);

            // Выполняем!
            $Return = self::_Do($Call);

            self::$_Stack->pop();
            return $Return;
        }

        protected static function _is($Key, $Contract)
        {
            if (isset($Contract[$Key]))
                return Core::Any($Contract[$Key]);
            else
                return false;
        }

        // FIXME Behaviour!
        protected static function _DetermineDriver($Call)
        {
            // Если в контракте указана политика драйверов, и драйвер не указан напрямую.

            if (!isset($Call['D']))
            {
                if (isset ($Call['Contract']) && isset($Call['Contract']['Driver']) && null !== $Call['Contract']['Driver'])
                {
                    if (isset($Call['Contract']['Driver'][Environment]))
                        $Call['D'] = $Call['Contract']['Driver'][Environment];
                    else
                        $Call['D'] = $Call['Contract']['Driver']['Default'];
                }
                else
                    $Call['D'] = null;
            }

            $Call['D'] = Core::Any($Call['D']);

            return $Call;
        }

        protected static function _FilterCall($Call)
        {
            if (isset($Call['Contract']['Arguments']))
            {
                foreach ($Call['Contract']['Arguments'] as $Name => $Node)
                {
                    if (isset($Node['Filter']))
                    {
                        if (!isset($Call[$Name]))
                        {
                            $Call[$Name] = self::Run(
                                array(
                                     'Calls' => array_merge($Call[$Name], $Node['Filter'])),
                                Core::Kernel, 'Chain');
                        }
                    }
                }
            }

            return $Call;
        }

        protected static function _CheckCall($Call)
        {
            // Валидация
            if (isset($Call['Contract']['Arguments']))
            {
                foreach ($Call['Contract']['Arguments'] as $Name => $Node)
                {
                    if (!isset($Call[$Name]))
                    {
                        self::_Check(null, $Node);
                    }
                    else
                    {
                        self::_Check($Call[$Name], $Node);
                    }
                }
            }
        }

        public static function Trace($Index = null)
        {
            $StackArray = array();
            foreach (self::$_Stack as $Stack)
                $StackArray[] = $Stack;

            if ($Index == null)
            {
                return $StackArray;
            }
            elseif (isset($StackArray[$Index]))
            {
                return $StackArray[$Index];
            }
            else
            {
                return null;
            }
        }

        public static function Current($Call = null)
        {
            if (null !== $Call)
            {
                return Core::mergeOptions(self::$_Stack[0], $Call);
            }
            else
            {
                return self::$_Stack[0];
            }
        }

        public static function Parent($Call = null)
        {
            return self::$_Stack[1];
        }
    }
