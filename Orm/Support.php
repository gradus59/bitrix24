<?php

namespace GraDus59\Bitrix24\Orm;

use Bitrix\Crm\ActivityTable;
use Bitrix\Crm\CompanyTable;
use Bitrix\Crm\ContactTable;
use Bitrix\Crm\DealTable;
use Bitrix\Crm\InvoiceTable;
use Bitrix\Crm\LeadTable;
use Bitrix\Crm\QuoteTable;
use Bitrix\Crm\RequisiteTable;
use Bitrix\Iblock\IblockTable;
use Bitrix\Sale\OrderTable;
use CCrmOwnerType;
use GraDus59\Bitrix24\Crm\Smart;

class Support
{
    public static function getClassOrmByType(string $type)
    {
        switch ($type):
            case CCrmOwnerType::LeadName:
                return LeadTable::class;
            case CCrmOwnerType::DealName:
                return DealTable::class;
            case CCrmOwnerType::ContactName:
                return ContactTable::class;
            case CCrmOwnerType::CompanyName:
                return CompanyTable::class;
            case CCrmOwnerType::InvoiceName:
                return InvoiceTable::class;
            case CCrmOwnerType::ActivityName:
                return ActivityTable::class;
            case CCrmOwnerType::QuoteName:
                return QuoteTable::class;
            case CCrmOwnerType::RequisiteName:
                return RequisiteTable::class;
            case CCrmOwnerType::OrderName:
                return OrderTable::class;
            case CCrmOwnerType::CommonDynamicName:
                return Smart::class;
            default:
                return false;
        endswitch;
    }

    public static function getIbIdByApiCode($api_code)
    {
        return self::getIbIdBy("API_CODE",$api_code)['ID'];
    }

    public static function getIbIdByCode($code)
    {
        return self::getIbIdBy("CODE",$code)['ID'];
    }

    public static function getIbIdBy($field,$value)
    {
        $parameters = [
            "select" => ["ID","CODE","API_CODE"],
            "filter" => [$field => $value]
        ];

        return IblockTable::getList($parameters)->fetch();
    }
}