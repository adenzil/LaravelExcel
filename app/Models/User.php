<?php

namespace App\Models;

use Mail;
use Illuminate\Support\Str;
use App\Mail\PasswordlessLoginMail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Models\UserLoginToken;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function token() {
        return $this->hasOne(UserLoginToken::class);
    }

    function register(array $request)
    {
        try {
            $user = new User;
            $user->name = $request['name'];
            $user->email = $request['email'];
            $user->save();
            return $user;
        } catch (\Exception $e) {
            throw new \Exception("Failed to register user");
        }
    }

    public function deleteToken() {
        $this->token()->delete();
    }

    public function createToken() {
        $this->deleteToken();
        $this->token()->create([
            'token' => Str::random(255)
        ]);

        return $this;
    }

    public function mailToken(array $options) {
        Mail::to($this)->send(new PasswordlessLoginMail($this, $options));
    }

}
