<?php

namespace Sibas\Http\Controllers\Auth;

use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Monolog\Logger;
use Sibas\Entities\City;
use Sibas\Entities\Guest;
use Sibas\Entities\Log;
use Sibas\Entities\User;
use Sibas\Http\Requests;
use Sibas\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Sibas\Repositories\LogRepository;
use \Firebase\JWT\JWT;

class AuthController extends Controller
{

    use RedirectsUsers;


    /**
     * @var LogRepository
     */
    protected $logRepository;


    public function __construct(LogRepository $logRepository)
    {
        $this->logRepository = $logRepository;
    }


    public function getLogin()
    {
        if (Auth::check()) {
            return redirect()->intended($this->redirectPath());
        }

        if (view()->exists('auth.authenticate')) {
            return view('auth.authenticate');
        }

        $cities = City::where('type_de', true)->lists('name', 'id');

        return view('auth.login', compact('cities'));
    }


    public function postLogin(Request $request)
    {
        $guest    = $request->has('guest');
        $validate = [
            'username' => 'required|max:140|alpha_num|exists:ad_users,username,active,1',
            'password' => 'required|max:60',
        ];

        if ($guest) {
            $validate = [
                'first_name' => 'required|max:140|alpha_space',
                'last_name'  => 'required|max:140|alpha_space',
                'email'      => 'required|max:140|email',
                'city'       => 'required|exists:ad_cities,id',
            ];
        }

        $this->validate($request, $validate);

        if ($guest) {
            $request->request->add([
                'username' => 'guest',
                'password' => 'Abrenet123',
            ]);
        }

        $credentials = $request->only('username', 'password');
        $queries     = [];

        DB::enableQueryLog();

        try {
            if (Auth::attempt($credentials, $request->has('remember'))) {
                if ($guest) {
                    $first_name = $request->get('first_name');
                    $last_name  = $request->get('last_name');
                    $email      = trim($request->get('email'));
                    $city       = $request->get('city');
                    $guest      = Guest::where('email', $email)->first();

                    if ($guest instanceof Guest) {
                        $guest->first_name = $first_name;
                        $guest->last_name  = $last_name;
                        $guest->ad_city_id = $city;
                        $guest->number     = $guest->number + 1;
                        $guest->ip         = $request->ip();
                        $guest->save();
                    } else {
                        $guest             = new Guest();
                        $guest->first_name = $first_name;
                        $guest->last_name  = $last_name;
                        $guest->email      = $email;
                        $guest->ad_city_id = $city;
                        $guest->number     = 1;
                        $guest->ip         = $request->ip();
                        $guest->save();
                    }

                    session([ 'guest' => $guest ]);
                }

                /*
                 * Log - user login
                 */
                $queries = DB::getQueryLog();
                $user    = auth()->user();
                $extra   = [
                    'user'       => $user->username,
                    'date_login' => Carbon::now(),
                ];

                $this->logRepository->setLog('login', $queries, $user, Logger::INFO, $extra);

                DB::disableQueryLog();

                $user->update([ 'attempt' => 0]);

                return redirect()->intended($this->redirectPath());
            }
        } catch (QueryException $e) {
            $queries = [
                'query'   => $e->getSql(),
                'message' => $e->getMessage(),
            ];
        }

        $message = 'El nombre de usuario o la contraseña son incorrectos';

        if (! $guest) {
            $u = User::where('username', $request->get('username'))->get();

            if ($u->count() === 1) {
                $user = $u->first();

                $attempt = $user->attempt + 1;

                if ($attempt === 3) {
                    $user->attempt     = 0;
                    $user->active      = 0;
                    $user->locked      = $user->locked + 1;
                    $user->locked_date = Carbon::now();
                } else {
                    $user->attempt = $attempt;
                }

                $user->save();

                $user = $user->fresh();

                if (! $user->active) {
                    $message = 'El usuario '
                               . $user->username
                               . ' no se encuentra habilitado <br> Excedió el numero máximo de intentos.';
                }
            }
        }

        DB::disableQueryLog();

        return redirect($this->loginPath())
            ->withInput($request->only('username', 'remember', 'guest'))
            ->withErrors([ 'username' => $message ]);
    }


    public function loginPath()
    {
        return property_exists($this, 'loginPath') ? $this->loginPath : '/auth/login';
    }


    public function getLogout()
    {
        if (Auth::check()) {
            /*
             * Log user logout
             */
            $user  = auth()->user();

            $this->logRepository->storeUserLogoutLog($user);
            /*
             * Log user logout
             */

            if (request()->session()->has('guest')) {
                request()->session()->forget('guest');
            }

            Auth::logout();
        }

        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }


    public function getCashierLogout()
    {
        request()->session()->forget('cashier');

        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }


    public function redirectPath()
    {
        if (Auth::user()->type->code === 'ADT') {
            return route('adm::');
        }

        return 'home';
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (!$this->isValidCredentials($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $token = $this->generateToken();

        return response()->json(compact('token'));
    }

    private function isValidCredentials($credentials)
    {
        $this->user = User::where('username', $credentials['username'])->first();

        if (!$this->user || !password_verify($credentials['password'], $this->user->password))
            return false;

        return true;
    }

    private function generateToken()
    {
        $issued_at = time();
        $token = [
            "iss" => 'issuer_claim',
            "iat" => $issued_at,
            "exp" => $issued_at + 3600,
            "email" => $this->user->email // Agregar datos adicionales al token si lo necesitas
        ];

        return JWT::encode($token, '3001', 'HS256');
    }
}
