<?php

namespace App\Models;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
class AdminResetPasswordNotification extends Admin
{
    public function __construct($token)
    {
        $this->token = $token;
    }

    // Implementar outros métodos conforme necessário
}
