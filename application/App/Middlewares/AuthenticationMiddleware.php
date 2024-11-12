<?php

namespace App\Middlewares;

class AuthenticationMiddleware
{

    private string $Type = "JWT";
    private array $User = [];
    private array $Permissions = [];

    public function execute(): bool
    {
        $Token = $_SERVER['HTTP_AUTHORIZATION'] ?? null;

        if ($Token === null) return false;

        $this->Type = str_replace("Bearer ", "", $Token);

        $parts = explode('.', $this->Type);
        if (count($parts) !== 3) return false;

        list($header, $payload, $signature) = $parts;

        $Header = json_decode($this->decode($header), true);
        $Payload = json_decode($this->decode($payload), true);
        $Signature = $this->decode($signature);

        if (!$Header || !$Payload || !$Signature) return false;

        if ($Header['alg'] !== 'HS256') return false;

        $DataToSign = $header . '.' . $payload;

        $Expected = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(hash_hmac('sha256', $DataToSign, getenv('SECRET_KEY'), true)));

        if (!$Expected == $signature) return false;

        if (isset($payload['exp']) && $payload['exp'] < time()) return false;

        $this->User = $Payload;

        return true;
    }

    private function decode($data): false|string
    {
        $remainder = strlen($data) % 4;
        if ($remainder) {
            $paddingLength = 4 - $remainder;
            $data .= str_repeat('=', $paddingLength);
        }
        return base64_decode(strtr($data, '-_', '+/'));
    }

    public function authenticate(string $Login, string $Type): string
    {

        $Header = [
            'alg' => 'HS256',
            'typ' => 'JWT'
        ];

        $Header = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($Header)));

        $PayLoad = [
            'id' => $Login,
            'type' => $Type
        ];

        $PayLoad = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($PayLoad)));

        $DataToSign = $Header . '.' . $PayLoad;

        $Signature = hash_hmac('sha256', $DataToSign, getenv('SECRET_KEY'), true);
        $Encoded = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($Signature));

        return $Header . '.' . $PayLoad . '.' . $Encoded;
    }

    public function checkPermission(mixed $Permission)
    {
        return in_array($Permission, $this->Permissions);
    }

    public function grantPermission($Permission)
    {
        return array_push($this->Permissions, $Permission);
    }

    public function revokePermission($Permission)
    {
        $index = array_search($Permission, $this->Permissions);
        unset($this->Permissions[$index]);
    }

    public function getPermissions()
    {
        return $this->Permissions;
    }

}