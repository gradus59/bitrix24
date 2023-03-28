<?php

namespace GraDus59\Bitrix24\UserField\HbSearch;

use GraDus59\Bitrix24\Storage\HighLoad;
use GraDus59\Bitrix24\UserField\CustomType;
use GraDus59\Bitrix24\UserField\DataSearch;
use GraDus59\Bitrix24\UserField\FindFilter;
use GraDus59\Bitrix24\UserField\GetSettings;

class SelectList extends CustomType
{
    const USER_TYPE = 'HbSearch';
    const AJAX_URL = "/ajax/hbSearch.php";

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
        return parent::getMyDescription(
            self::USER_TYPE,
            "HB-search",
            __CLASS__
        );
    }

    public static function GetPublicViewHTML($arUserField, $arAdditionalParameters = array()): string
    {
        $settings = $arUserField['SETTINGS']['SETTINGS'];

        $resultPrint = self::getSelectArray(
            $arUserField['VALUE'],
            $settings['HB_ID'],
            $settings['HB_NAME']
        );

        return implode(';<br>',$resultPrint);
    }

    public static function GetPublicEditHTML($arUserField, $arAdditionalParameters = array()): string
    {
        $settings = $arUserField['SETTINGS']['SETTINGS'];
        $resultPrint = self::getSelectArray(
            $arUserField['VALUE'],
            $settings['HB_ID'],
            $settings['HB_NAME']
        );

        $jsonInit = DataSearch::jsonInit(
            $arUserField['FIELD_NAME'],
            $resultPrint,
            $arUserField['MULTIPLE'],
            $settings['HB_AJAX'],
            $settings['HB_LENGTH_SEARCH'],
            [
                "hbId" => $settings['HB_ID'],
                "fieldSearch" => $settings['HB_NAME'],
                "limit" => $settings['HB_TIME_STOP']
            ],
            $settings['HB_TIME_STOP']
        );

        return parent::getSelectListHTML($arUserField['FIELD_NAME'],$jsonInit);
    }

    public static function GetSettingsHTML($userField, ?array $additionalParameters, $varsFromForm): string
    {
        $parameterName = $additionalParameters["NAME"];
        $settings = $userField['SETTINGS'][$parameterName];
        $hbList = HighLoad::getObject()->getListArray();

        return GetSettings::elements([
            "HighLoad:" => GetSettings::arrayToSelect($parameterName."[HB_ID]",$hbList,$settings['HB_ID']),
            Lang::get("HB_TITLE") => GetSettings::toInputText($parameterName."[HB_NAME]",[
                "text" => "text",
                "value" => $settings['HB_NAME'] == "" ? "UF_NAME" : $settings['HB_NAME']
            ]),
            Lang::get("TIME_STOP") => GetSettings::toInputText($parameterName."[HB_TIME_STOP]",[
                "type" => "number",
                "value" => $settings['HB_TIME_STOP'] == "" ? 300 : $settings['HB_TIME_STOP']
            ]),
            Lang::get("AJAX") => GetSettings::toInputText($parameterName."[HB_AJAX]",[
                "type" => "text",
                "value" => $settings['HB_AJAX'] == "" ? parent::ajaxDir(self::AJAX_URL) : $settings['HB_AJAX']
            ]),
            Lang::get("HB_LENGTH_SEARCH") => GetSettings::toInputText($parameterName."[HB_LENGTH_SEARCH]",[
                "type" => "text",
                "value" => $settings['HB_LENGTH_SEARCH'] == "" ? 2 : $settings['HB_LENGTH_SEARCH']
            ]),
        ]);
    }

    private static function getSelectArray($value, $hbId, $hbName): array
    {
        $hb = HighLoad::getObject()->classById($hbId);

        $obFind = $hb->getList([
            "filter" => [
                "=ID" => is_array($value) ? $value : explode(',', $value)
            ]
        ]);

        $resultPrint = [];
        while ($resFind = $obFind->fetch())
            $resultPrint[$resFind['ID']] = $resFind[$hbName];

        return $resultPrint;
    }
}