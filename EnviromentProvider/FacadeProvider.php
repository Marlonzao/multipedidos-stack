<?php
    namespace Multipedidos;

    class FacadeProvider {

        public static function provide()
        {
            $mocks   = config('mocks');
            $facades = [];

            foreach([
                'bind',
                'scoped',
                'singleton',
            ] as $bind) {
                $facades = array_merge($facades, array_keys($mocks[$bind]));
            }

            $facadeNames = array_map("self::publishClass", $facades);
            
            $result = array_combine($facadeNames, $facades);
            return $result;
        }

        public static function publishClass($facade)
        {
            $className = "{$facade}Facade";

            if(!class_exists($className))
                eval("class $className extends \Illuminate\Support\Facades\Facade { protected static function getFacadeAccessor() {return '$facade';} };");

            return $className;
        }
    }
