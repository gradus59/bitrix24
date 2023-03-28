<?php

namespace GraDus59\Bitrix24\UserField;

use Bitrix\Main\EventManager;
use Bitrix\Main\Page\Asset;
use GraDus59\Bitrix24\Page\Support;
use GraDus59\Bitrix24\Storage\Data\BaseChange;
use GraDus59\Bitrix24\UserField\HbSearch\SelectList;

class Includes
{
    private const ASSETS_DIR = "/src/assets/";
    private const ASSET_CSS = "SelectList.css";
    private const ASSET_JS = "SelectList.js";

    private const EVENT_CRM_FIELD = "OnUserTypeBuildList";
    private const EVENT_ON_PROLOG = "OnProlog";

    public static function init()
    {
        self::initHbSearch();
    }

    private static function initHbSearch()
    {
        EventManager::getInstance()->addEventHandler(
            'main', self::EVENT_ON_PROLOG,
            [__CLASS__, 'HbSearch']
        );
    }

    public static function HbSearch()
    {
        EventManager::getInstance()->addEventHandler(
            'main', self::EVENT_CRM_FIELD,
            [SelectList::class, 'getUserTypeDescription']
        );

        $page = Support::getPage();

        $arPages = [
            BaseChange::inString($page,"/crm/contact/details/"),
            BaseChange::inString($page,"/crm/company/details/"),
            BaseChange::inString($page,"/crm/deal/details/"),
            BaseChange::inString($page,"/crm/lead/details/"),
        ];

        if(in_array(true,$arPages))
        {
            $asset = Asset::getInstance();
            $asset->addCss( self::assetsDir() . self::ASSET_CSS);
            $asset->addJs(self::assetsDir() . self::ASSET_JS);
        }
    }

    private static function assetsDir(): string
    {
        return str_replace($_SERVER['DOCUMENT_ROOT'], "",__DIR__) . self::ASSETS_DIR;
    }
}