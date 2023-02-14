<?php

namespace GraDus59\Bitrix24\Crm\Activity;

class Call
{
    public function __construct()
    {
        \CModule::IncludeModule("crm");
        \CBitrixComponent::includeComponentClass('bitrix:crm.activity.planner');
    }

    public function add(
        string $subject,
        string $description,
        int $createdBy,
        int $responsibleId,
        string $providerType = "",
        string $direction = "",
        array  $notify = [],
        array  $date = [],
        array  $duration = [],
        int $dealId = 0,
        bool   $completed = false,
        bool   $important = false,
        array $communications = []
    )
    {
        $start = count($date['START']) == 0 ? date('d.m.Y H:i:s') : $date['START'];
        $end = count($date['END']) == 0 ? date('d.m.Y H:i:s') : $date['END'];
        $toUser = $date['TO_USER'] == true;

        $durationValue = array_key_exists('VALUE',$duration) ? $duration['VALUE'] : 60;
        $durationType = array_key_exists('TYPE',$duration) ? $duration['TYPE'] : Planer::MINUTE_TEXT;

        $notifyValue = array_key_exists('VALUE',$notify) ? $notify['VALUE'] : 60;
        $notifyType = array_key_exists('TYPE',$notify) ? $notify['TYPE'] : Planer::MINUTE_TEXT;

        $planer = new Planer();
        $planer->setCompleted($completed);
        $planer->setImportant($important);
        $planer->setType($providerType);
        $planer->setProvider($providerType);
        $planer->setDirection($direction);
        $planer->setDates($start,$end,$toUser);
        $planer->setNotify($notifyValue,$notifyType);
        $planer->setDuration($durationValue,$durationType);
        $planer->setTitle($subject,$description);
        if(array_key_exists('ID',$communications))
            $planer->setCommunications($communications['ENTITY_NAME'],$communications['ID']);
        $planer->setResponsible($responsibleId);
        $planer->setDeal($dealId);
        $planer->setDefault();

        $data = $planer->getData();

        return \CrmActivityPlannerComponent::saveActivity($data,$createdBy,SITE_ID);
    }
}