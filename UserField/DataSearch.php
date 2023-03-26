<?php

namespace GraDus59\Bitrix24\UserField;

class DataSearch
{
    public static function setItems($obRes, &$items, $fieldSearch): array
    {
        while ($resHb = $obRes->fetch()) {
            $items[] = [
                "id" => (int)$resHb['ID'],
                "title" => $resHb[$fieldSearch]
            ];
        }

        return $items;
    }
}