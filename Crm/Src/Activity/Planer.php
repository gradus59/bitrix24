<?php

namespace GraDus59\Bitrix24\Crm\Src\Activity;

use Bitrix\Main\Type\DateTime;
use Bitrix\Crm\Activity\Provider;

class Planer
{
    public const PROVIDER_CALL = Provider\Call::ACTIVITY_PROVIDER_ID;
    public const PROVIDER_CALL_LIST = Provider\CallList::PROVIDER_ID;
    public const PROVIDER_TYPE_CALL = Provider\Call::ACTIVITY_PROVIDER_TYPE_CALL;
    public const PROVIDER_TYPE_CALL_LIST = Provider\CallList::TYPE_CALL_LIST;

    public const DIRECTION_INCOMING = "INCOMING";
    public const DIRECTION_OUTGOING = "OUTGOING";

    public const MINUTE_TEXT = "MINUTE";
    public const HOURS_TEXT = "HOURS";
    public const DAYS_TEXT = "DAYS";

    private const TYPE_CALL = 2;
    private const TYPE_CALL_LIST = 0;
    private const INCOMING_CALL = 1;
    private const OUTGOING_CALL = 2;
    private const MINUTE = 1;
    private const HOURS = 2;
    private const DAYS = 3;

    private array $data = [];

    public function setCompleted(bool $completed = false)
    {
        if($completed)
            $this->data['completed'] = "Y";
    }

    public function setImportant(bool $important = false)
    {
        if($important)
            $this->data['important'] = "Y";
    }

    public function setType(string $type = self::PROVIDER_TYPE_CALL)
    {
        switch($type):
            case self::PROVIDER_TYPE_CALL:
                $typeId = self::TYPE_CALL;
                break;
            case self::PROVIDER_TYPE_CALL_LIST:
                $typeId = self::TYPE_CALL_LIST;
                break;
            default:
                $typeId = 0;
                break;
        endswitch;

        $this->data['type'] = $typeId;
    }

    public function setProvider(string $type = self::PROVIDER_TYPE_CALL)
    {
        switch($type):
            case self::PROVIDER_TYPE_CALL:
                $providerId = self::PROVIDER_CALL;
                $providerTypeId = self::PROVIDER_TYPE_CALL;
                break;
            case self::PROVIDER_TYPE_CALL_LIST:
                $providerId = self::PROVIDER_CALL_LIST;
                $providerTypeId = self::PROVIDER_TYPE_CALL_LIST;
                break;
            default:
                $providerId = false;
                $providerTypeId = false;
                break;
        endswitch;

        $this->data['providerId'] = $providerId;
        $this->data['providerTypeId'] = $providerTypeId;
    }

    public function setDirection(string $type = self::DIRECTION_INCOMING)
    {
        switch ($type):
            case self::DIRECTION_INCOMING:
                $direction = self::INCOMING_CALL;
                break;
            case self::DIRECTION_OUTGOING :
                $direction = self::OUTGOING_CALL;
                break;
            default:
                $direction = 0;
        endswitch;

        $this->data['direction'] = $direction;
    }

    public function setDates(string $start, string $end, bool $toUser = false)
    {
        $this->data['startTime'] = $this->dateToFormat($start,$toUser);
        $this->data['endTime'] = $this->dateToFormat($end,$toUser);
    }

    public function setNotify($value = "" ,$type = self::MINUTE_TEXT)
    {
        switch ($type):
            case self::MINUTE_TEXT:
                $notifyType = self::MINUTE;
                break;
            case self::HOURS_TEXT:
                $notifyType = self::HOURS;
                break;
            case self::DAYS_TEXT:
                $notifyType = self::DAYS;
                break;
            default:
                $notifyType = 0;
                break;
        endswitch;

        $this->data['notifyValue'] = $value;
        $this->data['notifyType'] = $notifyType;
    }

    public function setDuration($value,$type = self::MINUTE_TEXT)
    {
        switch ($type):
            case self::MINUTE_TEXT:
                $durationType = self::MINUTE;
                break;
            case self::HOURS_TEXT:
                $durationType = self::HOURS;
                break;
            case self::DAYS_TEXT:
                $durationType = self::DAYS;
                break;
            default:
                $durationType = 0;
                break;
        endswitch;

        $this->data['durationValue'] = $value;
        $this->data['durationType'] = $durationType;
    }

    public function setTitle(string $subject, string $description = "",string $type = self::PROVIDER_TYPE_CALL)
    {
        switch($type):
            case self::PROVIDER_TYPE_CALL:
                $this->data['subject'] = $subject;
                $this->data['description'] = $description;
                break;
            case self::PROVIDER_TYPE_CALL_LIST:
                $this->data['callListSubject'] = $subject;
                $this->data['callListDescription'] = $description;
                break;
        endswitch;
    }

    public function setCommunications($entity,$id)
    {
        $this->data['communications'][0]['type'] = "";
        $this->data['communications'][0]['entityType'] = $entity;
        $this->data['communications'][0]['entityId'] = $id;
        $this->data['communications'][0]['entityTitle'] = ""; // title
        $this->data['communications'][0]['entityDescription'] = ""; // Компания у контакта
        $this->data['communications'][0]['value'] = "";
    }

    public function setCallListId($id)
    {
        $this->data['callListId'] = $id;
    }

    public function setDefault()
    {
        $this->data['ownerId'] = 0;
        $this->data['ownerType'] = "";
        $this->data['sessid'] = bitrix_sessid();
        $this->data['storageTypeID'] = 3;
        $this->data['useWebform'] = "N";
        $this->data['webformId'] = 1;
    }

    public function setDeal($id)
    {
        $this->data['dealId'] = $id;
    }

    public function setResponsible($id)
    {
        $this->data['responsibleId'] = $id;
    }

    public function setFiles(array $arFile)
    {
        foreach ($arFile as $fileId):
            $this->data['diskfiles'][] = $fileId;
        endforeach;
    }

    public function getData()
    {
        return $this->data;
    }

    private function dateToFormat($date, bool $toUser = false)
    {
        $formatDate = date(DateTime::getFormat(),strtotime($date));
        if($toUser)
            $formatDate = new DateTime($formatDate);
        return $formatDate;
    }
}