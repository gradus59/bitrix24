<?php

namespace GraDus59\Bitrix24\Page;

use Bitrix\Main\Context;

class Support
{
    public static function getPage(): ?string
    {
        $page = Context::getCurrent()->getRequest()->getRequestUri();
        return $page ? strtolower($page) : $page;
    }

    public static function reloadPage()
    {
        //TODO: перезагрузка страницы с бэка из событий или скриптов
    }
}