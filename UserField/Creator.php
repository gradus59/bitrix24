<?php

namespace GraDus59\Thistle\UserField;

use GraDus59\Bitrix24\Storage\Data\BaseChange;

class Creator
{
    private const REPLACE_STR = "%class%";
    private const EDIT_FIELD_FILE = "/templates/system.field.edit";
    private const VIEW_FIELD_FILE = "/templates/system.field.view";

    public function createTemplate($class)
    {
        self::createEdit($class);
        self::createView($class);
    }

    private static function createEdit($class)
    {
        $className = BaseChange::getClassNameByNamespace($class);

        $path = "/local/templates/.default/components/bitrix/system.field.edit/" . $className;
        BaseChange::createDir($path);
        $template = self::getTemplate($class,self::EDIT_FIELD_FILE);
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . $path . "/" . "template.php",$template);
    }

    public static function createView($class)
    {
        $className = BaseChange::getClassNameByNamespace($class);

        $path = "/local/templates/.default/components/bitrix/system.field.view/". $className;
        BaseChange::createDir($path);
        $template = self::getTemplate($class,self::VIEW_FIELD_FILE);
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . $path . "/" . "template.php",$template);
    }

    private static function getTemplate($class,$templatePath)
    {
        $template = file_get_contents(__DIR__ . $templatePath);
        return str_replace(self::REPLACE_STR,$class,$template);
    }
}