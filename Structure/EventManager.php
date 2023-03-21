<?php

namespace GraDus59\Bitrix24\Structure;

use GraDus59\Bitrix24\Structure\Src\CheckingManager;

class EventManager extends \Bitrix\Main\EventManager
{
    public function addEventHandler(
        $module,
        $event,
        $callBack,
        $sort = 100,
        $file_path = false
    )
    {
        $class = $callBack[0];
        $method = $callBack[1];
        $parameters = [];
        $myCallBack = [];

        $checking = CheckingManager::getInstance($class,$method);

        $checking->checkClass();
        $checking->checkMethod();

        foreach ($checking->getParameters() as $parameter)
            $parameters[] = $parameter->name;

        if(count($parameters) == 1) {
            $parameter = $parameters[0];

            switch ($parameter) {
                case "event":
                    $myCallBack = function (\Bitrix\Main\Event $event) use ($class,$method)
                    {
                        return call_user_func_array(array($class, $method), [$event]);
                    };
                    break;
                case "arFields":
                    $myCallBack = function (&$arFields) use ($class,$method)
                    {
                        return call_user_func_array(array($class, $method), [$arFields]);
                    };
                    break;
            }
        } else {
            // тут пишем всякие гадости на новой клавиатуре
            //
        }



        parent::addEventHandler($module,$event,$myCallBack,$file_path,$sort);
    }
}