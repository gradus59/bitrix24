<?php
//TODO: split add method
//TODO: file save
//TODO: webform
//TODO: update method
//TODO: delete method

namespace GraDus59\Bitrix24\Crm\Activity;

class Call
{
    private ?Planer $planer = null;
    private $createdBy;

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

    public function add(array  $date = [], array  $duration = [])
    {
        $start = count($date['START']) == 0 ? date('d.m.Y H:i:s') : $date['START'];
        $end = count($date['END']) == 0 ? date('d.m.Y H:i:s') : $date['END'];
        $toUser = $date['TO_USER'] == true;

        $durationValue = array_key_exists('VALUE',$duration) ? $duration['VALUE'] : 60;
        $durationType = array_key_exists('TYPE',$duration) ? $duration['TYPE'] : Planer::MINUTE_TEXT;

        $this->planer->setDates($start,$end,$toUser);
        $this->planer->setDuration($durationValue,$durationType);

        $this->planer->setDefault();
        $data = $this->planer->getData();

        $return = \CrmActivityPlannerComponent::saveActivity($data,$this->createdBy,SITE_ID);

        return $return->isSuccess() ? $return->getData() : $return->getErrorMessages();
    }
}