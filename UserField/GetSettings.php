<?php

namespace GraDus59\Bitrix24\UserField;

class GetSettings
{
    public static function elements(array $arElements): string
    {
        $settings = "";

        foreach ($arElements as $title => $value):
            $settings .=
            '<tr>
                <td>'.$title.'</td>
                <td>'.$value.'</td>
            </tr>';
        endforeach;

        return $settings;
    }

    public static function element(string $title, string $value): string
    {
        return
            '<tr>
                <td>'.$title.'</td>
                <td>'.$value.'</td>
            </tr>';
    }

    public static function arrayToSelect(string $name, array $array, $selected): string
    {
        $select = '<select size="5" name="'.$name.'">';
        $selectOption = $selected == 0 || $selected == "" ? "selected" : "";
        $options = '<option '.$selectOption.' value="">Не выбрано</option>';

        foreach ($array as $id => $text):
            $selectOption = "";
            if($id == $selected)
                $selectOption = "selected";
            $options .= '<option '.$selectOption.' value="'.$id.'">'.$text.'</option>';
        endforeach;

        $select .= $options;
        $select .= '/select>';

        return $select;
    }

    public static function toInputText(string $name, array $attributes): string
    {
        $strAtr = "";

        foreach ($attributes as $attr => $val):
            $strAtr .= $attr . "=\"{$val}\"";
        endforeach;

        return '<input name="'.$name.'" '.$strAtr.'>';
    }
}