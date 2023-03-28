<?php

namespace GraDus59\Bitrix24\UserField;

use Bitrix\Main\Web\Json;
use GraDus59\Bitrix24\Storage\Ajax;

class DataSearch
{
    public static function jsonInit(string $fieldName, array $initValues, string $multiple, string $ajaxUrl, array $ajaxData = [], int $timeout = 300)
    {
        $arOption = [];
        foreach ($initValues as $id => $title):
            $arOption[] = [
                "id" => $id,
                "title" => $title
            ];
        endforeach;

        $boolMultiple = $multiple == "Y";
        $inputNameMulti = $fieldName;
        if($boolMultiple)
            $inputNameMulti = $fieldName."[]";

        $options[Ajax::SESS_ID_KEY] = Ajax::getSessid();
        $options[Ajax::TOKEN_KEY] = Ajax::getToken();
        $options['selector'] = ".auto-complete." . $fieldName;
        $options['inputName'] = $inputNameMulti;
        $options['initValues'] = $arOption;
        $options['multiple'] = $boolMultiple;
        $options['ajaxUrl'] = $ajaxUrl;
        $options['ajaxData'] = $ajaxData;
        $options['timeout'] = $timeout;

        return Json::encode($options);
    }

    public static function setItems($obRes, &$items, $fieldSearch): array
    {

        while ($resHb = $obRes->fetch()) {
            $elementId = (int)$resHb['ID'];
            $found_key = array_search(
                $elementId,
                array_column($items, "id")
            );
            if($found_key !== false)
                continue;

            $items[] = [
                "id" => $elementId,
                "title" => $resHb[$fieldSearch]
            ];
        }

        return $items;
    }
}