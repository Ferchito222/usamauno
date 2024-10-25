<?php

namespace Sibas\Repositories\API;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Collection;

class ApiRepository
{

    protected $token = null;
    /**
     * @var Collection
     */
    protected $setting;


    /**
     * Get Authentication token
     *
     * @return bool
     */
    public function getAuthToken()
    {
        try {
            $uri = $this->getUri();

            $client = new Client([
                'base_uri' => $uri,
            ]);

            $response = $client->post('api/authenticate', [
                'headers'     => [ 'Content-Type' => 'application/x-www-form-urlencoded' ],
                'form_params' => [
                    'email'    => 'holly@coboser.com',
                    'password' => 'secret',
                ],
            ]);

            $data        = $response->getBody()->getContents();
            $data        = json_decode($data, true);
            $this->token = $data[ 'token' ];

            return true;
        } catch (RequestException $e) {
            $status  = 404;
            $request = $e->getRequest();
            $request->getBody()->getContents();

            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $status   = $response->getStatusCode();
            }
        }

        return false;
    }


    /**
     * Verify Authentication token
     *
     * @param string $token
     *
     * @return bool
     */
    public function verifyAuthToken($token)
    {
        try {
            $uri = $this->getUri();

            $client = new Client([
                'base_uri' => $uri,
            ]);

            $response = $client->get('api/authenticate', [
                'query' => [
                    'token' => $token,
                ],
            ]);

            $data = $response->getBody()->getContents();

            return true;
        } catch (RequestException $e) {
            $status  = 404;
            $request = $e->getRequest();
            $request->getBody()->getContents();

            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $status   = $response->getStatusCode();
            }
        }

        return false;
    }


    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }


    /**
     * @return bool
     */
    public function connection()
    {
        exec('ping -c4 ' . $this->setting['jwt_host'], $output, $status);

        return $status == 0 ? true : false;
    }


    /**
     * @return string
     */
    protected function getUri()
    {
        return str_replace(':host', $this->setting['jwt_host'], $this->setting['jwt_uri']);
    }


    public function getPath()
    {
        return $this->setting->has('jwt_path') ? $this->setting[ 'jwt_path' ] : '*';
    }


    /**
     * @param Collection $setting
     */
    public function setSetting($setting)
    {
        $this->setting = $setting;
    }
}