<?php

namespace GraDus59\Bitrix24\Storage;

class Curl
{
    private static ?Curl $instance;

    private string $url = "";
    private string $requestType = "";
    private $auth;
    private $data;
    private $response;

    public function __construct()
    {
        $this->setRequestType("POST");
        $this->setData("{}");
    }

    public static function getInstance(): ?Curl
    {
        if (!self::$instance)
            self::$instance = new self();
        return self::$instance;
    }

    public function sent()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->getUrl(),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $this->getRequestType(),
            CURLOPT_POSTFIELDS => $this->getData(),
            CURLOPT_HTTPHEADER => array(
                $this->auth,
                'Content-Type: application/json'
            ),
        ));

        $this->setResponse(curl_exec($curl));
        curl_close($curl);
        return $this->getResponse();
    }

    public function basicAuth(string $login,string $password): static
    {
        $this->auth = 'Authorization: Basic ' . base64_encode(
                $login . ":" . $password
            );
        return $this;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;
        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setResponse($response): static
    {
        $this->response = $response;
        return $this;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function setRequestType(string $requestType): static
    {
        $type = strtolower($requestType);
        $this->requestType = match($type)
        {
            "post"  => "POST",
            "get"   => "GET",
            default => "GET"
        };
        return $this;
    }

    public function getRequestType(): string
    {
        return $this->requestType;
    }

    public function setData($data): static
    {
        $this->data = $data;
        return $this;
    }

    public function getData()
    {
        return $this->data;
    }
}