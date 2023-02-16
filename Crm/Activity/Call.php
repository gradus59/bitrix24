<?php

namespace GraDus59\Bitrix24\Crm\Activity;

use Bitrix\Crm\ActivityTable;

class Call
{
    private ?Planer $planer = null;
    private $createdBy;
    private $result;

    public function __construct()
    {
        \CModule::IncludeModule("crm");
        \CBitrixComponent::includeComponentClass('bitrix:crm.activity.planner');
    }

    public function setHead(
        string $subject,
        string $description,
        int $createdBy,
        int $responsibleId
    )
    {
        $this->planer = new Planer();
        $this->planer->setTitle($subject,$description);
        $this->planer->setResponsible($responsibleId);
        $this->createdBy = $createdBy;
        $this->planer->setType();
        $this->planer->setProvider();
        $this->planer->setDefault();
    }

    public function callCompleted()
    {
        $this->planer->setCompleted(true);
    }

    public function callImportant()
    {
        $this->planer->setImportant(true);
    }

    public function setNotify(array $notify)
    {
        $notifyValue = array_key_exists('VALUE',$notify) ? $notify['VALUE'] : 60;
        $notifyType = array_key_exists('TYPE',$notify) ? $notify['TYPE'] : Planer::MINUTE_TEXT;

        $this->planer->setNotify($notifyValue,$notifyType);
    }

    public function setDirection(string $direction)
    {
        $this->planer->setDirection($direction);
    }

    public function setRelations(int $dealId = 0,array $communications = [])
    {
        if(array_key_exists('ID',$communications))
            $this->planer->setCommunications($communications['ENTITY_NAME'],$communications['ID']);
        $this->planer->setDeal($dealId);
    }

    public function setDate(array  $date = [], array  $duration = [])
    {
        $start = count($date['START']) == 0 ? date('d.m.Y H:i:s') : $date['START'];
        $end = count($date['END']) == 0 ? date('d.m.Y H:i:s') : $date['END'];
        $toUser = $date['TO_USER'] == true;

        $durationValue = array_key_exists('VALUE',$duration) ? $duration['VALUE'] : 60;
        $durationType = array_key_exists('TYPE',$duration) ? $duration['TYPE'] : Planer::MINUTE_TEXT;

        $this->planer->setDates($start,$end,$toUser);
        $this->planer->setDuration($durationValue,$durationType);
    }

    public function setFiles(array $arFile)
    {
        $this->planer->setFiles($arFile);
    }

    public function add()
    {
        $data = $this->planer->getData();
        $return = \CrmActivityPlannerComponent::saveActivity($data,$this->createdBy,SITE_ID);

        if($return->isSuccess()) {
            $this->setResult($return->getData());
        }else{
            $this->setResult($return->getErrorMessages());
        }

        return $return->isSuccess();
    }

    public function update(int $id, array $data): \Bitrix\Main\ORM\Data\UpdateResult
    {
        unset($data['ID'], $data['PROVIDER_ID'], $data['PROVIDER_TYPE_ID']);
        $arData = $data;

        return ActivityTable::update($id,$arData);
    }

    public function delete(int $id): \Bitrix\Main\ORM\Data\DeleteResult
    {
        return ActivityTable::delete($id);
    }

    private function setResult($result)
    {
        $this->result = $result;
    }

    public function getResult()
    {
        return $this->result;
    }
}