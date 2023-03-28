<?php

namespace GraDus59\Bitrix24\Storage;

class Ajax
{
    private const TOKEN = "10jYtetJSy802sko0918498u8kajIJSJDijas9";
    public const TOKEN_KEY = "token";
    public const SESS_ID_KEY = "sessid";

    public static function getRequest($checkToken = false)
    {
        $request = \Bitrix\Main\Application::getInstance()
            ->getContext()
            ->getRequest();

        $data = \Bitrix\Main\Web\Json::decode($request->getInput());
        $sessid = $data[self::SESS_ID_KEY];
        $token = $data[self::TOKEN_KEY];
        unset($data[self::SESS_ID_KEY], $data[self::TOKEN_KEY]);

        if(!$checkToken)
            return $data;

        if($sessid != self::getSessid())
            $data = [
                "error" => "session_id is not correct!"
            ];

        if($token != self::getToken())
            $data = [
                "error" => "token is not correct!"
            ];

        if(array_key_exists('error',$data))
            die(json_encode($data));

        return $data;
    }

    public static function getToken(): string
    {
        return md5(self::TOKEN);
    }

    public static function getSessid()
    {
        return bitrix_sessid();
    }
}