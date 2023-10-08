<?php

namespace Gvillela7\Vault\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
class AuthVault
{

    private Client $client;
    private array $header = [];
    private string $vaultUrl;
    private string $username;
    private string $password;

    public function __construct()
    {
        $this->client = new Client();
        $this->vaultUrl = config('vault.url');
        $this->username = config('vault.username');
        $this->password = config('vault.password');
        $this->header = [
            "X-Vault-Request" => true,
            "Content-Type" => "application/json"
        ];
    }

    private function checkSealedVault(): bool
    {
        try {
            $user = $this->client->get($this->vaultUrl . '/sys/seal-status', [
                'headers' => $this->header
            ]);
            $vault = json_decode($user->getBody()->getContents());
            return $vault->sealed;
        } catch (ClientException $exception) {
            return $exception->getMessage();
        }
    }
    public function login()
    {
        $sealed = $this->checkSealedVault();
        if (!$sealed) {
            if ($this->username !== "" && $this->password !== "") {
                $json = json_encode([
                    'password' => $this->password,
                ]);
                try {
                    $user = $this->client->put($this->vaultUrl . '/auth/userpass/login/' . $this->username, [
                        'headers' => $this->header,
                        'body' => $json
                    ]);
                    $vault = json_decode($user->getBody()->getContents());
                    return $vault->auth;
                } catch (ClientException $exception) {
                    return $exception->getMessage();
                }
            } else {
                return 'username and password in config/vault.php must be filled in';
            }
        } else {
            return 'Vault Sealed';
        }
    }
}