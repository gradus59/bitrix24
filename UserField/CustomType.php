<?php

namespace GraDus59\Bitrix24\UserField;

use Bitrix\Main\UserField\Types\BaseType;

abstract class CustomType extends BaseType
{
    public static function getMyDescription (string $USER_TYPE, string $DESCRIPTION, string $CLASS): array
    {
        return [
            'PROPERTY_TYPE' => 'N',
            'USER_TYPE'     => $USER_TYPE,
            'DESCRIPTION'   => $DESCRIPTION,
            'BASE_TYPE'     => \CUserTypeManager::BASE_TYPE_INT,
            'CLASS_NAME'    => $CLASS,
            'USER_TYPE_ID'  => $USER_TYPE,
            "EDIT_CALLBACK" => [$CLASS, 'GetPublicEditHTML'],
            "VIEW_CALLBACK" => [$CLASS, 'GetPublicViewHTML']
        ];
    }

    abstract static function GetPublicViewHTML($arUserField, $arAdditionalParameters = array()): string;

    abstract static function GetPublicEditHTML($arUserField, $arAdditionalParameters = array()): string;

    public static function getDbColumnType(): string
    {
        return 'text';
    }

    public static function getLength ($property, $value)
    {
        return 0;
    }

    public static function convertToDb ($property, $value)
    {
        return $value;
    }

    public static function convertFromDb ($property, $value)
    {
        return $value;
    }

    public static function PrepareSettings($arFields): array
    {
        return $arFields;
    }

    public static function OnBeforeSave($arUserField, $value)
    {
        return $value == 0 ? "" : $value;
    }

    protected static function getSelectListHTML(string $fieldName, string $jsonInit): string
    {
        return
            "<div class=\"auto-complete {$fieldName}\">
                <div class=\"auto-complete__input\">
                    <input class=\"auto-complete__input-field\" size=\"1\" type=\"text\">
                </div>
                <div class=\"auto-complete__search\"></div>
            </div>" .
            "<script> 
                new AutoComplete({$jsonInit});
            </script>";
    }

    protected static function ajaxDir($ajax): string
    {
        return str_replace($_SERVER['DOCUMENT_ROOT'], "",__DIR__) . $ajax;
    }
}