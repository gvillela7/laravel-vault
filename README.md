# laravel-vault

## Description
 simple package to get information saved in HashiCorp Vault.
 We need a user created in the vault using the user/pass authentication method and enabling secret engine kv.
 We need a policy for the created user
``` 
path "secret/*" {
 capabilities = ["create", "update", "read", "list"]
 }
 ```
## Install
 ```
 composer require gvillela7/laravel-vault 
 
 php artisan vendor:publish --tag=vault-config
 ```
## Simple usage
### unseal vaul
One or more comma-separated keys
```
php artisan vault:unseal key1,key2
```
Set key in vault
```
$authVault = new AuthVault();
$auth = $authVault->login();
$token = $auth->client_token;

Vault::setKey($token, 'API_KEY', '0000000000', 'secret/data/github');
```
Get key saved in vault
```
$authVault = new AuthVault();
$auth = $authVault->login();
$token = $auth->client_token;

$data = Vault::getKey('secret/data/github', $token);
$data->data->API_KEY; // "0000000000"
   
```
