<?php

namespace GraDus59\Bitrix24\Storage\Data;

class Find
{
    /**
     * @author < GraDus59 > Перебиковский Ярослав
     */
    public function __construct()
    {
    }

    /* Массив экранируемых значений */
    private static array $arrayEscapeChars = [ '(',')','{','}','[',']','\\','/' ];

    public static function getValuesBetweenInChars(string $string, string $charBefore, string $charAfter)
    {
        $inChars = 1;
        return self::getValuesBetweenChars($string,$charBefore,$charAfter)[$inChars];
    }

    public static function getValuesBetweenOutChars(string $string, string $charBefore, string $charAfter)
    {
        $outChars = 0;
        return self::getValuesBetweenChars($string,$charBefore,$charAfter)[$outChars];
    }

    public static function getValuesBetweenChars(string $string, string $charBefore, string $charAfter)
    {
        $match = self::getMatchBetween($charBefore,$charAfter);
        preg_match_all($match, $string, $matches);
        return $matches;
    }

    private static function getMatchBetween(string $betweenCharBefore, string $betweenCharAfter): string
    {
        self::setSlashes($betweenCharBefore);
        self::setSlashes($betweenCharAfter);
        return "#{$betweenCharBefore}(.*?){$betweenCharAfter}#";
    }

    private static function setSlashes(string &$value): void
    {
        $value = addslashes($value);
        if(in_array($value, self::$arrayEscapeChars))
            $value = '\\' . $value;
    }
}