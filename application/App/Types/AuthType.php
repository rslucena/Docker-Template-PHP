<?php

namespace App\Types;

enum AuthType:string{

    const NONE = "None";
    const USERNAME_PASSWORD = "UserPass";
    const JWT = "Token";

}