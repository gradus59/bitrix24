<?php

namespace GraDus59\Bitrix24\Storage;

class Csv
{
    private static ?Csv $instance = null;
    private int $numRow = 0;
    private bool $biggestRow = false;
    private string $separator = ";";
    private $stream;
    private $title;

    public static function getInstance():Csv
    {
        if(self::$instance == null)
            self::$instance = new self();
        return self::$instance;
    }

    public function setSeparator($separator)
    {
        $this->separator = $separator;
    }

    public function notSetHeader()
    {
        $this->biggestRow = true;
    }

    public function read(string $path)
    {
        $stream = fopen($path, "r");
        if ($stream === false)
            die("Открыть файл по указанному пути '" . $path . "' не удалось");

        $this->stream = $stream;
        $this->setHeader();
    }

    public function Next()
    {
        $csv_row = $this->getRow();

        self::$instance->titleCompare($csv_row);

        if($this->biggestRow)
            return $csv_row;

        if($csv_row !== false)
            return array_combine($this->title,$csv_row);

        return false;
    }

    private function titleCompare($csv_row)
    {
        if( count($this->title) < count($csv_row) && !$this->biggestRow )
            die("[Ошибка присвоения заголовков] Строка (" . $this->numRow . ") содержит больше элементов, чем заголовки");
    }

    private function getRow()
    {
        $csv_row = fgetcsv($this->stream,null, $this->separator);
        if(!$csv_row)
            fclose($this->stream);

        $this->numRow++;

        return $csv_row;
    }

    private function setHeader()
    {
        $array = $this->getRow();
        $this->title = $array;
    }
}