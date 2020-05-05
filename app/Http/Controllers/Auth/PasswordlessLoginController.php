<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;

use App\Models\User;
use App\Models\UserLoginToken;

class PasswordlessLoginController extends Controller
{
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function index() {
        return view('auth.passwordless');
    }

    public function sendToken(Request $request) {
        $this->validate($request, [
            'email' => 'required|email|max:255|exists:users,email'
        ]);

        $user = User::where('email', $request->get('email'))->firstOrFail();

        if($user instanceOf User) {
            $user->createToken()->mailToken([
                'remember' => $request->has('remember'),
                'email' => $user->email
            ]);
        }

        return redirect()->to('/login/passwordless')->with('success', 'Link sent to email');
    }

    public function validateToken(Request $request, UserLoginToken $token) {

        $token->delete();

        if($token->isExpired()) {
            return redirect()->to('/login/passwordless')->with('error', 'Link has expired');
        }

        if(!$token->belongsToEmail($request->email)) {
            return redirect()->to('/login/passwordless')->with('error', 'Invalid link');
        }

        Auth::login($token->user, $request->remember);

        return redirect()->to('/home');

    }
}
