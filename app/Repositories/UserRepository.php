<?php

namespace Sibas\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Sibas\Entities\User;

class UserRepository extends BaseRepository
{

    /**
     * @param User  $user
     * @param array $profiles
     *
     * @return array
     */
    public function getUsersByUserProfile(User $user, array $profiles = [])
    {
        $data = [];

        $users = $user->retailerUser->retailer->users;

        foreach ($users as $user) {
            foreach ($user->profile as $profile) {
                if (in_array($profile->slug, $profiles, true) !== false) {
                    $data[] = $user;
                }

                break;
            }
        }

        return $data;
    }


    /**
     * @param int $retailer_id
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUsersByRetailer($retailer_id)
    {
        return User::with('agency', 'city')
            ->whereHas('retailerUser.retailer', function ($q) use ($retailer_id) {
                $q->where('ad_retailers.id', $retailer_id);
            })
            ->whereNotIn('username', [ 'admin', 'guest' ])
            ->get();
    }


    /**
     * @param string $retailer_id
     * @param string $position
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUsersByPosition($retailer_id, $position = '')
    {
        return User::with('agency', 'city')
            ->whereHas('retailerUser.retailer', function ($q) use ($retailer_id) {
                $q->where('ad_retailers.id', $retailer_id);
            })
            ->whereNotIn('username', [ 'admin', 'guest' ])
            ->where('position', 'like', '%' . $position . '%')
            ->orderBy('username', 'asc')
            ->get();
    }


    /**
     * @param Model|User $user
     * @param Request    $request
     * @param string     $option
     *
     * @return bool
     */
    public function updateOption($user, $request, $option)
    {
        $success = true;

        try {
            switch ($option) {
                case 'pwd':
                    $user->update([
                        'password' => bcrypt($request->get('password_new')),
                    ]);
                    break;
                case 'agy':
                    $user->update([
                        'ad_agency_id' => $request->get('agency'),
                    ]);
                    break;
                case 'tel':
                    $user->update([
                        'phone_number' => $request->get('phone_number'),
                    ]);
                    break;
            }
        } catch (QueryException $e) {
            $this->errors = $e->getMessage();
            $success      = false;
        }

        return $success;
    }


    /**
     * Get User by username
     *
     * @param $retailer_id
     * @param $username
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getUserByUsername($retailer_id, $username)
    {
        return User::select([
            'username as id',
            'full_name as name',
        ])
            ->with('agency', 'city')
            ->whereHas('retailerUser.retailer', function ($q) use ($retailer_id) {
                $q->where('ad_retailers.id', $retailer_id);
            })
            ->whereNotIn('username', [ 'admin', 'guest' ])
            ->where('username', $username)
            ->orderBy('full_name', 'asc')
            ->get();
    }


    /**
     * Get Users by agency
     *
     * @param $retailer_id
     * @param $agency_id
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getUsersByAgency($retailer_id, $agency_id)
    {
        return User::select([
            'username as id',
            'full_name as name',
        ])
            ->with('agency', 'city')
            ->whereHas('retailerUser.retailer', function ($q) use ($retailer_id) {
                $q->where('ad_retailers.id', $retailer_id);
            })
            ->whereHas('agency', function ($q) use ($agency_id) {
                $q->where('id', $agency_id);
            })
            ->whereNotIn('username', [ 'admin', 'guest' ])
            ->orderBy('full_name', 'asc')
            ->get();
    }

    public function getUserPublic()
    {
        
        return User::with(
            'agency', 
            'city',
            'permissions'
            )
            ->whereHas('permissions', function ($q){
                $q->where('slug', 'RPRIN');
            })
            ->whereNotIn('username', [ 'admin', 'guest' ])
            ->where('active', true)
            ->get();
    }

    public function getUserPublicById($user_id)
    {
        return User::with(
            'agency', 
            'city',
            'permissions'
            )
            ->whereHas('permissions', function ($q){
                $q->where('slug', 'RPRIN');
            })
            ->whereNotIn('username', [ 'admin', 'guest' ])
            ->where('id', $user_id)
            ->get();
    }


    /**
     * Get Users by city
     *
     * @param $retailer_id
     * @param $city_id
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getUsersByCity($retailer_id, $city_id)
    {
        return User::select([
            'username as id',
            'full_name as name',
        ])
            ->with('agency', 'city')
            ->whereHas('retailerUser.retailer', function ($q) use ($retailer_id) {
                $q->where('ad_retailers.id', $retailer_id);
            })
            ->whereHas('city', function ($q) use ($city_id) {
                $q->where('id', $city_id);
            })
            ->whereNotIn('username', [ 'admin', 'guest' ])
            ->orderBy('full_name', 'asc')
            ->get();
    }


    public function getAllUsers($retailer_id)
    {
        return User::select([
            'username as id',
            'full_name as name',
        ])
            ->with('agency', 'city')
            ->whereHas('retailerUser.retailer', function ($q) use ($retailer_id) {
                $q->where('ad_retailers.id', $retailer_id);
            })
            ->whereHas('agency', function ($q) {
                $q->whereNotNull('id');
            })
            ->whereHas('city', function ($q) {
                $q->whereNotNull('id');
            })
            ->whereNotIn('username', [ 'admin', 'guest' ])
            ->orderBy('full_name', 'asc')
            ->get();
    }


    /**
     * @param array $profiles
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getUsersByProfile(array $profiles = [])
    {
        $users = User::with(['profile'])
            ->whereHas('profile', function ($q) use ($profiles) {
                $q->whereIn('slug', $profiles);
            })
            ->where('active', true)
            ->get();

        return $users;
    }
}