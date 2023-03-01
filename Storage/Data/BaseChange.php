<?php

namespace GraDus59\Bitrix24\Storage\Data;

class BaseChange
{
    /**
     * @author < GraDus59 > Перебиковский Ярослав
     */
    public function __construct()
    {
    }

    public static function toArray($value, string $separator = ";")
    {
        if(is_array($value))
            return $value;
        if(is_object($value))
            return array_map('toArray',(array) $value);
        return explode($separator,$value);
    }

    public static function toString($array, string $separator = ";"): string
    {
        if(is_array($array))
            return implode($separator,$array);
        return $array;
    }

    public static function inString(string $string, string $sub_string): bool
    {
        if( strpos($string,$sub_string) !== false )
            return true;
        return false;
    }

    public static function trimVal($value)
    {
        if(!is_array($value))
            return trim($value);
        return array_map("trim",$value);
    }

    public static function putUserPrefix($userId)
    {
        if(!is_array($userId))
            return self::inString($userId,"user_") ? $userId : "user_" . $userId;
        return array_map("putUserPrefix",$userId);
    }

    public static function removeUserPrefix($userId)
    {
        if(!is_array($userId))
            return intval(ltrim(trim($userId),'user_'));
        return array_map("removeUserPrefix",$userId);
    }

    public static function toBool($value, array $template = [true,false])
    {
        $whiteListBoolValues = [
            "positive" => [
                'Y',
                1,
                '1',
                true,
                'true',
                'TRUE',
                'Да',
                'Yes',
            ],
            "negative" => [
                'N',
                0,
                '0',
                false,
                'false',
                'FALSE',
                'Нет',
                'No',
                "",
                NULL
            ]
        ];

        if( in_array($value,$whiteListBoolValues['positive'], true) ) return $template[0];
        if( in_array($value,$whiteListBoolValues['negative'], true) ) return $template[1];
        if( is_array($value) )
            return empty($value) ? $template[1] : $template[0];

        return (bool) $value;
    }

    public static function getClassNameByNamespace(string $classPath)
    {
        $explode = explode('\\',$classPath);
        return end($explode);
    }

    public static function getIdByEntityId(string $entityId)
    {
        $arEntityId = explode('_',$entityId);
        return end($arEntityId);
    }

    public static function shiftArrayItem(array &$array, $key)
    {
        if($key === false)
            return array_shift($array);
        $retrieval = $array[$key];
        unset( $array[$key] );
        return $retrieval;
    }

    public static function shiftArrayItems(array &$array, array $keys): array
    {
        $retrievals = [];
        foreach ($keys as $key)
            $retrievals[$key] = self::shiftArrayItem($array, $key);
        return $retrievals;
    }

    public static function createDir($path)
    {
        $needPath = $_SERVER['DOCUMENT_ROOT'] . $path;
        if(!is_dir($needPath))
            mkdir($needPath,BX_DIR_PERMISSIONS,true);
    }
}