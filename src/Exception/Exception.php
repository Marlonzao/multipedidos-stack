<?php

class MultipedidosException extends \Exception
{
    public $alertMethod;

    public static function e($message, $code = 500)
    {
        return new class ($message, $code){
            public $message, $previous = null, $alertMethod = 'toaster', $code = 500;
        
            public function __construct($message, $code)
            {
                [$this->message, $this->code] = [$message, $code]; 
            }

            private function setLastError(Exception $e)
            {
                $this->previous = $e;
            }

            private function setErrorCode($code)
            {
                $this->code = $code;
            }

            private function setAlertMethod($alertMethod)
            {
                $this->alertMethod = $alertMethod;
            }

            public function throw()
            {
                if(env('APP_ENV') == 'local' && !is_null($this->previous)) throw $this->previous;
                throw new \MultipedidosException($this->message, $this->code, $this->alertMethod, $this->previous);
            }

            public function __call($name, $arguments)
            {
                $this->{$name}(...$arguments);
                return $this;
            }
        };
    }

    public function __construct($message, $code = 500, $alertMethod = 'toaster', Exception $previous = null)
    {
        $this->alertMethod = $alertMethod;
        parent::__construct($message, $code, $previous);
    }

    public function getAlertMethod()
    {
        return $this->alertMethod;
    }    
}
