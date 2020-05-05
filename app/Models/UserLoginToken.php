<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class UserLoginToken extends Model
{
    protected $table = 'users_login_tokens';

    protected $fillable = ['token'];

    const TOKEN_EXPIRY = 300;   // 5 minutes

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function getRouteKeyName() {
        return 'token';
    }

    public function isExpired() {
        return $this->created_at->diffInSeconds(Carbon::now()) > self::TOKEN_EXPIRY;
    }

    public function belongsToEmail($email) {
        return $this->user->email === $email;
    }

}
