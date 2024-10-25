<?php

namespace Sibas\Repositories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Sibas\Entities\Log;
use Sibas\Entities\User;
use Monolog\Logger;

class LogRepository
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var Log
     */
    protected $document;

    /**
     * @var string
     */
    protected $error = '';


    /**
     * Save Log
     *
     * @param string     $message
     * @param array      $queries
     * @param Model|User $user
     * @param int        $level
     * @param array      $extra
     */
    public function setLog($message, $queries, $user, $level, $extra = [])
    {
        try {
            $this->id = date('U');
            $data     = [
                'data' => $queries,
            ];

            $this->document             = new Log();
            $this->document->id         = $this->id;
            $this->document->message    = $message;
            $this->document->ad_user_id = $user->id;
            $this->document->ip         = request()->ip();
            $this->document->data       = $data;
            $this->document->level      = $level;
            $this->document->level_name = Logger::getLevelName($level);
            $this->document->channel    = 'application';
            $this->document->extra      = $extra;

            $this->document->save();
        } catch (QueryException $e) {
            $this->error = $e->getMessage();
        }
    }


    /**
     * Get Log by id
     *
     * @param int $logId
     *
     * @return bool
     */
    public function getLog($logId)
    {
        try {
            $this->document = Log::with('user')->where('id', $logId)->first();

            if ($this->document instanceof Log) {
                return true;
            }
        } catch (QueryException $e) {
            $this->error = $e->getMessage();
        }

        return false;
    }


    /**
     * Log user logout
     *
     * @param Model|User $user
     */
    public function storeUserLogoutLog($user)
    {
        $log = Log::where('ad_user_id', $user->id)
            ->where('message', 'login')
            ->get()
            ->last();

        if ($log instanceof Log) {
            $extra = $log->extra;

            if (key_exists('date_login', $extra)) {
                $date_login  = new Carbon($extra['date_login']['date'], $extra['date_login']['timezone']);
                $date_logout = Carbon::now();

                $time = $date_logout->diffInHours($date_login) . gmdate(':i:s', $date_logout->diffInSeconds($date_login));

                $extra['date_logout']  = $date_logout;
                $extra['session_time'] = $time;
            }

            $log->extra = $extra;
            $log->save();
        }
    }


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return Log
     */
    public function getDocument()
    {
        return $this->document;
    }
}