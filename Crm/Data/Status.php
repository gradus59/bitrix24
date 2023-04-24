<?php

namespace GraDus59\Bitrix24\Crm\Data;

use Bitrix\Crm\Category\DealCategory;
use Bitrix\Main\Data\Cache;

class Status
{
    const CACHE_CATEGORY = "deal_categories";
    const TTL_DAY = 86400;

    public static function getDealCategory($id)
    {
        $categories = [];
        $cache = Cache::createInstance();
        $init = $cache->initCache(self::TTL_DAY, self::CACHE_CATEGORY);
        $start = $cache->startDataCache();

        if($init)
            $categories = $cache->getVars()[self::CACHE_CATEGORY];

        if($start)
            $cache->endDataCache([self::CACHE_CATEGORY => self::dealCategories()]);

        return $categories[$id];
    }

    public static function getDealStage($id)
    {
        $stages = \Bitrix\Crm\StatusTable::getList([
            'filter' => [
                'ENTITY_ID' => ['DEAL_STAGE%', 'DEAL_STAGE'],
                'STATUS_ID' => $id,
            ],
            'order'  => [
                'SORT' => 'ASC',
            ],
            'cache' => array(
                'ttl' => self::TTL_DAY,
                'cache_joins' => true,
            )
        ]);

        return $stages->fetch()['NAME'];
    }

    public static function getDealSource($id)
    {
        $stages = \Bitrix\Crm\StatusTable::getList([
            'filter' => [
                'ENTITY_ID' => ['SOURCE'],
                'STATUS_ID' => $id,
            ],
            'order'  => [
                'SORT' => 'ASC',
            ],
            'cache' => array(
                'ttl' => self::TTL_DAY,
                'cache_joins' => true,
            )
        ]);

        return $stages->fetch()['NAME'];
    }

    public static function getLeadStatus(){}

    private static function dealCategories(): array
    {
        $catTmp = [];

        $cats = DealCategory::getList([
            "filter" => []
        ])->fetchAll();

        foreach ($cats as $cat):
            $catTmp[$cat["ID"]] = $cat['NAME'];
        endforeach;

        return array_merge([
            0 => DealCategory::getDefaultCategoryName()
        ],$catTmp);
    }
}