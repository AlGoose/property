<?php


namespace App;

use Illuminate\Contracts\Auth\Authenticatable;

class PasswordBroker extends \Illuminate\Auth\Passwords\PasswordBroker
{
    public function getToken(Authenticatable $user){
        return $this->tokens->create($user);
    }
}