<?php

namespace GraDus59\Bitrix24\UserField\HbSearch;

use GraDus59\Bitrix24\Storage\HighLoad;
use GraDus59\Bitrix24\UserField\DataSearch;
use GraDus59\Bitrix24\UserField\FindFilter;

class SelectList
{
    const USER_TYPE = 'HbSearch';

    public static function ajaxItems($searchString, $hbId, $fieldSearch, $limit)
    {
        $items = [];
        $isIsset = $searchString != "";
        $isString = is_string($searchString);

        if(!$isIsset && !$isString)
            return json_encode($items);

        $searchString = htmlspecialchars($searchString);
        $obSearch =  HighLoad::getObject()->classById($hbId);
        $obFilter = new FindFilter($searchString,$fieldSearch);

        $obRes = $obSearch->getList([
            "filter" => $obFilter->getFilterByType(FindFilter::FULL_STRING)
        ]);
        DataSearch::setItems($obRes,$items,$fieldSearch);

        $obRes = $obSearch->getList([
            "filter" => $obFilter->getFilterByType([
                FindFilter::FULL_STRING,
                FindFilter::START_STRING,
                FindFilter::WORD_STRING
            ]),
            "order" => [$fieldSearch],
            "limit" => $limit
        ]);
        DataSearch::setItems($obRes,$items,$fieldSearch);

        return json_encode($items);
    }

    public static function getUserTypeDescription (): array
    {
        return [
            'PROPERTY_TYPE' => 'enumeration',
            'USER_TYPE'     => self::USER_TYPE,
            'DESCRIPTION'   => "HB-locations",
            'BASE_TYPE'     => \CUserTypeManager::BASE_TYPE_ENUM,
            'CLASS_NAME'    => __CLASS__,
            'USER_TYPE_ID'  => self::USER_TYPE,
            "EDIT_CALLBACK" => [__CLASS__, 'GetPublicEditHTML'],
            "VIEW_CALLBACK" => [__CLASS__, 'GetPublicViewHTML']
        ];
    }

    public static function GetSettingsHTML($arProperty, $strHTMLControlName, &$arPropertyFields)
    {

    }
}