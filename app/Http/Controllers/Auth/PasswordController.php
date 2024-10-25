<?php

namespace Sibas\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Sibas\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class PasswordController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    protected $subject;


    /**
     *
     * Create a new password controller instance.
     */
    public function __construct()
    {
        $this->subject = 'Aquí tienes el enlace para restablecer tu contraseña';

        $this->middleware('guest');
    }


    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function postEmail(Request $request)
    {
        $this->validate($request, [ 'email' => 'required|max:140|email' ]);

        $response = Password::sendResetLink($request->only('email'), function (Message $message) {
            $message->subject($this->getEmailSubject());
        });

        switch ($response) {
            case Password::RESET_LINK_SENT:
                return redirect()->back()->with('status', 'OK');

            case Password::INVALID_USER:
                return redirect()->back()->withErrors([ 'email' => trans($response) ]);
        }
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function postReset(Request $request)
    {
        $this->validate($request, [
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        $credentials = $request->only('email', 'password', 'password_confirmation', 'token');
        
        $user = (auth()->user() == null)?\Sibas\Entities\User::where('email',$credentials['email'])->first():auth()->user();
        
        if($user != null && \Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password))
                return redirect()->back()->withInput($request->only('email'))->withErrors([ 'email' => 'La Contraseña no puede ser igual a la anterior' ]);
                
        $response = Password::reset($credentials, function ($user, $password) {
            $this->resetPassword($user, $password);
        });

        switch ($response) {
            case Password::PASSWORD_RESET:
                return redirect($this->redirectPath())->with('status', trans($response));

            default:
                return redirect()->back()->withInput($request->only('email'))->withErrors([ 'email' => trans($response) ]);
        }
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword $user
     * @param  string                                      $password
     *
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $user->password        = bcrypt($password);
        $user->password_change = true;
        $user->password_date   = date('Y-m-d H:i:s');

        $user->save();

        Auth::login($user);
    }


}
