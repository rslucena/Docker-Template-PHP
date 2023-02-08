<?php

namespace App\Middlewares;

use App\Types\AuthType;

class AuthenticationMiddleware
{

    private string $Type = "";
    private array $User = [];
    private array $Permissions = [];

    public function authenticate($Login, string $Type ):bool {
        $this->User = $Login;
        $this->Mode = $Type;
        return !empty($this->User) && !empty($this->Mode);
    }

    public function checkPermission(mixed $Permission) {
        return in_array($Permission, $this->Permissions);
    }

    public function grantPermission($Permission) {
        array_push($this->Permissions, $Permission);
    }

    public function revokePermission($Permission) {
        $index = array_search($Permission, $this->Permissions);
        unset($this->Permissions[$index]);
    }

    public function getPermissions() {
        return $this->Permissions;
    }

}