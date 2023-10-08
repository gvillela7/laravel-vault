<?php

namespace Gvillela7\Vault\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class Vault
{

    public function __construct()
    {
        
    }

    private static function curl(): Client
    {
        return new Client();
    }
    private static function header($token): array
    {
        return [
            "X-Vault-Token" => $token,
            "X-Vault-Request" => true,
            "Content-Type" => "application/json"
        ];
    }
    public static function url(): string
    {
        return config('vault.url');
    }
    public function unseal($arguments)
    {
        $client = self::curl();
        $keys = explode(',', $arguments);
        foreach($keys as $key) {
            $json = json_encode([
                'key' => $key,
                'reset' => false,
                'migrate' => false
            ]);

            $vault = $client->put(self::url() . '/sys/unseal', [
                'headers' => self::header(null),
                'body' => $json
            ]);

            $result = json_decode($vault->getBody()->getContents());
            if ($result->sealed === false) {
                return true;
            }
        }
    }
    public static function setKey($token, $key, $value, $uri, $numberVersion = null)
    {
        $client = self::curl();
        $json = json_encode([
            'data' => [$key => $value],
            'options' => ['version' => $numberVersion]
        ]);
        $result = $client->put(self::url() . '/' . $uri, [
            'headers' =>  self::header($token),
            'body' => $json,
        ]);
        $data = json_decode($result->getBody()->getContents());
        return $data->data;
    }
    public static function getKey($uri, $token)
    {
        $client = self::curl();
        $result = $client->get(self::url() . '/' . $uri, [
            'headers' => self::header($token)
        ]);
        $data = json_decode($result->getBody()->getContents());
        return $data->data;
    }
}