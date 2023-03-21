<?php

namespace GraDus59\Bitrix24\Structure\Src;

class CheckingManager
{
    private static ?CheckingManager $instance;
    private $class;
    private $method;
    private $parameters;

    protected function __construct($class,$method)
    {
        $this->class = $class;
        $this->method = $method;
    }

    public static function getInstance($class,$method): ?CheckingManager
    {
        if (!isset(self::$instance))
            $class = __CLASS__;
            self::$instance = new self($class,$method);

        return self::$instance;
    }

    public function checkClass()
    {
        try {
            $reflectionClass = new \ReflectionClass($this->class);
            $methodExist = $reflectionClass->getMethod($this->method);
        } catch (\Throwable $exception) {
            die($exception->getMessage());
        }
    }

    public function checkMethod()
    {
        try {
            $reflectionMethod = new \ReflectionMethod($this->class,$this->method);

            if(!$reflectionMethod->isStatic())
                throw new \Exception("Method " . $this->method . " should be static.");

            $parameters = $reflectionMethod->getParameters();
        } catch (\Throwable $exception) {
            die($exception->getMessage());
        }

        $this->parameters = $parameters;
    }

    public function getParameters()
    {
        return $this->parameters;
    }
}