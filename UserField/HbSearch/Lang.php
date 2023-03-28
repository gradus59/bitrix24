<?php

namespace GraDus59\Bitrix24\UserField\HbSearch;

class Lang
{
    public static function get($code, $lang = LANGUAGE_ID)
    {
        $lang = strtolower($lang);
        return self::$lang($code);
    }

    private static function ru(string $code)
    {
        $LANG['HB_TITLE'] = "Код поля по которому будет осуществляться поиск:";
        $LANG['TIME_STOP'] = "Задержка перед поиском(мс.):";
        $LANG['AJAX'] = "Путь к обработчику:";
        $LANG['HB_LENGTH_SEARCH'] = "Не искать если в строке символов менее чем:";

        return $LANG[$code];
    }

    private static function en(string $code)
    {
        $LANG['HB_TITLE'] = "The code of the field by which the search will be performed:";
        $LANG['TIME_STOP'] = "Delay before search(ms.):";
        $LANG['AJAX'] = "Path to the handler:";
        $LANG['HB_LENGTH_SEARCH'] = "Do not search if the string of characters is less than:";

        return $LANG[$code];
    }
}