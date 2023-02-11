<?php

namespace GraDus59\Bitrix24\Crm\Src\Smart;

class Result
{
    private static $result;
    private static ?Result $instance = null;

    /**
     * @author < GraDus59 > Перебиковский Ярослав
     */
    final private function __construct()
    {
    }

    public static function getInstance(): ?Result
    {
        if (!self::$instance)
            self::$instance = new self();
        return self::$instance;
    }

    public function setResult($result)
    {
        self::$result = $result;
    }

    public function getCount(): int
    {
        return count(self::$result);
    }

    public function fetch()
    {
        $item = array_shift(self::$result);
        return $item->getData();
    }

    public function fetchAll(): array
    {
        $data = [];
        foreach (self::$result as $item)
            $data[] = $item->getData();

        return $data;
    }
}