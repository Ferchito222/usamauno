<?php

namespace Sibas\Repositories\Adm;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Sibas\Entities\Adm\UserWalletAssignment;
use Sibas\Entities\Agency;
use Sibas\Entities\City;
use Sibas\Entities\Company;
use Sibas\Entities\De\Header;
use Sibas\Entities\Permission;
use Sibas\Entities\Profile;
use Sibas\Entities\Retailer;
use Sibas\Entities\Retailer\RetailerUserProduct;
use Sibas\Entities\RetailerCityAgency;
use Sibas\Entities\RetailerUser;
use Sibas\Entities\User;
use Sibas\Entities\UserPermission;
use Sibas\Entities\UserProfile;
use Sibas\Entities\UserType;
use Sibas\Repositories\BaseRepository;

class UserAdmRepository extends BaseRepository
{

    protected $user;

    /**
     * @param Model|User $auth
     *
     * @return array
     */
    public function getDataUser($auth)
    {
        $user_types = UserType::orderBy('id', 'asc');

        if ($auth->type->code === 'OPT') {
            $user_types->where('code', '!=', 'OPT')
                ->where('code', '!=', 'ADT');
        }

        $user_types       = $user_types->get();
        $user_profiles    = Profile::orderBy('id', 'asc')->get();
        $user_permissions = Permission::select(['id', 'name', 'model AS codename'])->where('active', true)->orderBy('name', 'asc')->get();
        $cities           = City::where('type_de', true)->orWhere('type_re', true)->orderBy('id', 'asc')->get();
        $agencies         = Agency::select('ad_agencies.id', 'ad_agencies.name', 'ad_cities.slug as city_slug')
            ->leftJoin('ad_retailer_city_agencies', 'ad_agencies.id', '=', 'ad_retailer_city_agencies.ad_agency_id')
            ->leftJoin('ad_retailer_cities', 'ad_retailer_cities.id', '=', 'ad_retailer_city_agencies.ad_retailer_city_id')
            ->leftJoin('ad_cities', 'ad_cities.id', '=', 'ad_retailer_cities.ad_city_id')
            ->get();

        $retailer  = Retailer::with([
            'retailerProducts' => function ($q) {
                $q->with([ 'companyProduct.product' ])
                    ->where('type', 'MP')
                    ->where('active', true);
            }
        ])->get();

        $products  = $retailer->first()->retailerProducts->lists('companyProduct.product.name', 'companyProduct.product.id');
        $companies = Company::orderBy('slug', 'asc')->lists('name', 'id');

        $data = [
            'retailer'         => $retailer->lists('name', 'id'),
            'products'         => $products,
            'companies'        => $companies,
            'user_types'       => $user_types,
            'user_profiles'    => $user_profiles,
            'user_permissions' => $user_permissions,
            'cities'           => $cities,
            'agencies'         => $agencies,
        ];

        return $data;
    }


    /**
     * @param $id
     *
     * @return bool
     */
    public function getUserById($id)
    {
        $this->model = User::with([
            'retailerUser.retailer',
            'retailerUser.products',
            'type',
            'profile',
            'permissions',
            'agency',
            'city',
        ])->where('id', $id)->first();

        if ($this->model instanceof User) {
            return true;
        }

        return false;
    }


    /**
     * @param bool $export
     *
     * @return mixed
     */
    public function getUsers($export = false)
    {
        $user  = auth()->user();
        $users = User::with([ 'city', 'agency', 'profile', 'permissions', 'retailerUser.products' ])
            ->whereNotIn('username', [ 'admin', 'guest' ])
            ->where('id', '!=', $user->id)
            ->orderBy('username', 'asc');

        if ($user->type->code === 'OPT') {
            $users->where('email', 'like', '%@bnb%')
                  ->whereHas('type', function ($q) {
                      $q->where('code', 'UST');
                  });
        }

        if ($user instanceof User) {
            $users->where('id', '!=', $user->id);
        }

        if ($export) {
            $query = request()->get('query');

            if (! empty($query)) {
                $users->where(function ($q) use ($query) {
                    $q->Where('username', 'like', '%' . $query . '%')
                      ->orWhere('full_name', 'like', '%' . $query . '%')
                      ->orWhere('email', 'like', '%' . $query . '%')
                      ->orWhereHas('profile', function ($q) use ($query) {
                          $q->where('name', 'like', '%' . $query . '%');
                      })
                      ->orWhereHas('city', function ($q) use ($query) {
                          $q->where('name', 'like', '%' . $query . '%');
                      })
                      ->orWhereHas('agency', function ($q) use ($query) {
                          $q->where('name', 'like', '%' . $query . '%');
                      });
                });
            }
        }

        $users = $users->get();

        if (! $export) {
            $api = collect([
                'users' => collect([]),
            ]);

            $users->each(function ($u) use ($api) {
                $api['users']->push([
                    'idx'                => $u->idc,
                    'userName'           => $u->username,
                    'fullName'           => $u->full_name,
                    'email'              => $u->email,
                    'type'               => $u->type->id,
                    'cityIdx'            => $u->city->id,
                    'cityName'           => $u->city->name,
                    'agencyIdx'          => $u->agency ? $u->agency->id : null,
                    'agencyName'         => $u->agency ? $u->agency->name : null,
                    'additionalAgencies' => $u->additional_agencies,
                    'phoneNumber'        => $u->phone_number,
                    'position'           => $u->position,
                    'active'             => $u->active,
                    'passwordChange'     => $u->password_change,
                    'profileIdx'         => $u->profile->first()->id,
                    'profileSlug'        => $u->profile->first()->slug,
                    'profileName'        => $u->profile->first()->name,
                    'retailer'           => $u->retailerUser->retailer->id,
                    'products'           => $u->retailerUser->products->pluck('id'),
                    'companies'          => $u->retailerUser->companies,
                    'permissionIdx'      => $u->permissions->pluck('id'),
                    'permissionNames'    => $u->permissions->pluck('name'),
                ]);
            });

            return $api;
        }

        return $users;
    }


    /**
     * @return Collection
     */
    public function getUsersByAssignmentWallet()
    {
        $user = auth()->user();

        $data = User::with([
                'headersDe.details.client',
                'headersDe' => function ($q) {
                    $q->where('type', 'I')
                        //->where('issued', false)
                        ->where('canceled', false);
                },
            ])
            ->whereHas('profile', function ($q) {
                $q->where('slug', 'SEP');
            })
            ->whereNotIn('username', [ 'admin', 'guest' ])
            ->where('id', '!=', $user->id)
            ->orderBy('full_name', 'asc');

        if ($user->type->code === 'OPT') {
            $data->where('email', 'like', '%bnb.com.bo%');
        }

        $data = $data->get();

        $users = collect([]);
        $headers = [];

        $data->map(function ($item, $key) use ($users, &$headers) {
            $users->push([
                'id'       => $item->idc,
                'fullName' => $item->full_name,
                'headers'  => $item->headersDe->pluck('idc'),
            ]);

            $item->headersDe->map(function ($header) use (&$headers) {
                $headers[$header->idc] = [
                    'prefix'      => $header->prefix,
                    'issueNumber' => $header->issue_number,
                    'clients'     => $header->details->pluck('client.full_name'),
                ];
            });

            return false;
        });

        return compact('users', 'headers');
    }


    /**
     * @param Request $request
     * @param string  $user_type
     *
     * @return bool
     */
    public function userCreate(Request $request, $user_type)
    {
        try {
            $data = $request->all();
            $id   = date('U');

            $password = bcrypt($data['password']);

            $this->user = User::create([
                'id'                  => $id,
                'username'            => $data['username'],
                'password'            => $password,
                'full_name'           => $data['fullName'],
                'email'               => $data['email'],
                'position'            => $data['position'],
                'phone_number'        => $data['phoneNumber'],
                'password_change'     => false,
                'ad_city_id'          => $data['city'],
                'ad_agency_id'        => empty($data['agency']) ? null : $data['agency'],
                'additional_agencies' => $data['additionalAgencies'],
                'ad_user_type_id'     => $data['userType'],
                'active'              => true,
            ]);

            $companies = $request->has('companies') ? $data['companies'] : [];

            $this->user->retailerUser()->create([
                'ad_retailer_id' => $data['retailer'],
                'ad_user_id'     => $id,
                'companies'      => $companies,
                'active'         => true,
            ]);

            $user_products = [];

            foreach ($data[ 'products' ] as $product) {
                array_push($user_products, [
                    'ad_retailer_user_id' => $this->user->retailerUser->id,
                    'ad_product_id'       => $product,
                ]);
            }

            $this->user->retailerUser->userProducts()->createMany($user_products);

            $user_profile = UserProfile::create([
                'ad_user_id'    => $id,
                'ad_profile_id' => $data['profile'],
                'active'        => true,
            ]);

            $permissions = [];

            foreach ($data['permissions'] as $permission) {
                array_push($permissions, [
                    'ad_permission_id' => $permission,
                    'active'           => true,
                ]);
            }

            $user_permissions = $this->user->userPermissions()->createMany($permissions);

            $this->user = $this->user->fresh([
                'city',
                'agency',
                'profile',
                'permissions',
                'retailerUser.products',
            ]);

            return true;
        } catch (QueryException $e) {
            $this->errors = $e->getMessage();
        }

        return false;
    }


    /**
     * @param Request $request
     *
     * @return bool
     */
    public function userEdit(Request $request)
    {
        try {
            $this->user = $this->model;
            $data = $request->all();

            $this->user->update([
                'username'            => $data['username'],
                'full_name'           => $data['fullName'],
                'email'               => $data['email'],
                'position'            => $data['position'],
                'phone_number'        => $data['phoneNumber'],
                'ad_city_id'          => $data['city'],
                'ad_agency_id'        => empty($data['agency']) ? null : $data['agency'],
                'additional_agencies' => $data['additionalAgencies'],
                'ad_user_type_id'     => $data['userType'],
            ]);

            $companies = $request->has('companies') ? $data['companies'] : [];

            $this->user->retailerUser()->update([
                'ad_retailer_id' => $data['retailer'],
                'companies'      => json_encode($companies),
                'active'         => true,
            ]);

            $user_products = [];

            foreach ($data[ 'products' ] as $product) {
                array_push($user_products, [
                    'ad_retailer_user_id' => $this->user->retailerUser->id,
                    'ad_product_id'       => $product,
                ]);
            }

            $this->user->retailerUser->userProducts()->delete();
            $this->user->retailerUser->userProducts()->createMany($user_products);

            $this->user->userProfile()->update([
                'ad_profile_id' => $data['profile'],
                'active'        => true,
            ]);

            $permissions = [];

            foreach ($data['permissions'] as $permission) {
                array_push($permissions, [
                    'ad_permission_id' => $permission,
                    'active'           => true,
                ]);
            }

            $this->user->userPermissions()->delete();
            $this->user->userPermissions()->createMany($permissions);

            $this->user = $this->user->fresh([
                'city',
                'agency',
                'profile',
                'permissions',
                'retailerUser.products',
            ]);

            return true;
        } catch (QueryException $e) {
            $this->errors = $e->getMessage();
        }

        return false;
    }


    public function passwordEdit(Request $request)
    {
        try {
            /**
             * @var User $user
             */
            $user = $this->model;
            $attributes = [ 'password' => bcrypt($request->get('password')) ];
            $attributes['password_change'] = false;

            $user->update($attributes);

            return true;
        } catch (QueryException $e) {
            $this->errors = $e->getMessage();
        }

        return false;
    }


    /**
     * @param bool $active
     *
     * @return bool
     */
    public function userActivate($active)
    {
        $this->model->active = $active;

        return $this->saveModel();
    }


    /**
     * @param Collection $data
     * @param array      $fields
     *
     * @return bool
     */
    public function importUsers($data, $fields)
    {
        if (count($data) > 0) {
            $auth           = auth()->user();
            $db_users       = User::get();
            $db_agencies    = Agency::get();
            $db_retailer    = Retailer::with(['retailerCities.city'])->first();
            $db_rc          = $db_retailer->retailerCities;
            $db_cities      = City::get();
            $db_profiles    = Profile::get();
            $db_permissions = Permission::get();
            $db_types       = UserType::get()->where('code', 'UST')->first();
            $db_r_users     = RetailerUser::get();

            $id        = (int) date('U');
            $date      = $this->carbon->now();
            $password  = bcrypt('Abrenet123');
            $agency_id = (int) $db_agencies->max('id');
            $a_id      = $agency_id;
            $ru_id     = (int) $db_r_users->max('id');

            $users            = collect([]);
            $users_upd        = collect([]);
            $user_profiles    = collect([]);
            $user_permissions = collect([]);
            $retailer_users   = collect([]);
            $r_user_products  = collect([]);
            $agencies         = collect([]);
            $r_city_agencies  = collect([]);

            $profiles = [
                'vendedor'                  => 'SEP',
                'sub-gerente-de-agencia'    => 'REP',
                'gerente'                   => 'REP',
                'gestor-nacional'           => 'REP',
                'subgerente-comercial-zona' => 'REP',
                'supervisor'                => 'REP',
                'oficial-de-negocios-banca-empresas-cartera-comercial-y-de-servicios' => 'SEP',
                'oficial-de-negocios-banca-empresas-cartera-productiva'               => 'SEP',
                'oficial-de-negocios-de-banca-microcredito'                           => 'SEP',
                'oficial-de-negocios-banca-personas'      => 'SEP',
                'oficial-de-negocios-mediana-empresa'     => 'SEP',
                'oficial-de-negocios-pequena-empresa'     => 'SEP',
                'oficial-de-negocios-banca-mype'          => 'SEP',
                'oficial-de-negocios-banca-de-desarrollo' => 'SEP',
                'oficial-de-negocios-banca-personas-ai'   => 'SEP',
                'oficial-de-negocios-banca-empresas'      => 'SEP',
            ];

            $cities = [ 11, 15, 16, 13, 14, 17, 12, 19, 20, 11, 10 ];

            $db_username   = $db_users->pluck('id', 'username')->toArray();
            $db_username   = array_change_key_case($db_username, MB_CASE_UPPER);
            $db_agency     = $db_agencies->pluck('id', 'code')->toArray();
            $db_rc_city    = $db_rc->pluck('id', 'city.slug')->toArray();
            $db_city       = $db_cities->pluck('id', 'slug')->toArray();
            $db_profile    = $db_profiles->pluck('id', 'slug')->toArray();
            $db_permission = $db_permissions->pluck('id', 'slug')->toArray();

            foreach ($data as $c => $item) {
                if (in_array($c, $cities)) {
                    foreach ($item as $d) {
                        $username   = mb_strtolower(trim($d['nombre_usuario']));
                        $subsidiary = str_slug($d['nombre_sucursal']);
                        $profile    = str_slug($d['perfil']);

                        if (! in_array($username, array_keys($db_username))) {
                            $id    += 1;
                            $ru_id += 1;

                            $ins_agency = $agencies->pluck('id', 'code')->toArray();

                            if (in_array($d['codigo_agencia'], array_keys($db_agency))) {
                                $a_id = $db_agency[(int) $d['codigo_agencia']];
                            } elseif (in_array($d['codigo_agencia'], array_keys($ins_agency))) {
                                $a_id = $ins_agency[$d['codigo_agencia']];
                            } else {
                                $agency_id += 1;
                                $a_id       = $agency_id;

                                $agencies->push([
                                    'id'         => $a_id,
                                    'name'       => $d['nombre_agencia'],
                                    'code'       => $d['codigo_agencia'],
                                    'slug'       => str_slug($d['nombre_agencia']),
                                    'type'       => '',
                                    'created_at' => $date,
                                    'updated_at' => $date,
                                ]);

                                if (in_array($subsidiary, array_keys($db_rc_city))) {
                                    $rc = $db_rc_city[$subsidiary];

                                    $r_city_agencies->push([
                                        'id'                  => null,
                                        'ad_retailer_city_id' => $rc,
                                        'ad_agency_id'        => $a_id,
                                        'active'              => true,
                                        'created_at'          => $date,
                                        'updated_at'          => $date,
                                    ]);
                                }
                            }

                            $users->push([
                                'id'              => $id,
                                'username'        => $username,
                                'password'        => $password,
                                'full_name'       => mb_strtoupper(trim($d['nombre_completo'])),
                                'email'           => mb_strtolower(trim($d['correo_electronico'])),
                                'position'        => $d['perfil'],
                                'password_change' => false,
                                'ad_city_id'      => $db_city[$subsidiary],
                                'ad_agency_id'    => $a_id,
                                'ad_user_type_id' => $db_types->id,
                                'active'          => true,
                                'created_at'      => $date,
                                'updated_at'      => $date,
                            ]);

                            $user_profiles->push([
                                'id'            => null,
                                'ad_user_id'    => $id,
                                'ad_profile_id' => $db_profile[$profiles[$profile]],
                                'active'        => true,
                                'created_at'    => $date,
                                'updated_at'    => $date,
                            ]);

                            $permission = $db_permission['RU'];

                            if ($profiles[$profile] === 'REP') {
                                $permission = $db_permission['RA'];
                            }

                            $user_permissions->push([
                                'id'               => null,
                                'ad_user_id'       => $id,
                                'ad_permission_id' => $permission,
                                'active'           => true,
                                'created_at'       => $date,
                                'updated_at'       => $date,
                            ]);

                            $retailer_users->push([
                                'id'             => $ru_id,
                                'ad_retailer_id' => $db_retailer->id,
                                'ad_user_id'     => $id,
                                'companies'      => json_encode([]),
                                'active'         => true,
                                'created_at'     => $date,
                                'updated_at'     => $date,
                            ]);

                            $r_user_products->push([
                                'id'                  => null,
                                'ad_retailer_user_id' => $ru_id,
                                'ad_product_id'       => 1,
                                'created_at'          => $date,
                                'updated_at'          => $date,
                            ]);
                        } else {
                            $users_upd->push([
                                'id'       => $db_username[$username],
                                'username' => $username,
                                'position' => $d['perfil'],
                            ]);
                        }
                    }
                }
            }

            try {
                foreach ($users_upd as $d) {
                    User::where('id', $d['id'])->update($d);
                }

                Agency::insert($agencies->toArray());
                RetailerCityAgency::insert($r_city_agencies->toArray());
                User::insert($users->toArray());
                UserProfile::insert($user_profiles->toArray());
                UserPermission::insert($user_permissions->toArray());
                RetailerUser::insert($retailer_users->toArray());
                RetailerUserProduct::insert($r_user_products->toArray());

                return true;
            } catch (QueryException $e) {
                $this->errors = $e->getMessage();
            }
        }

        return false;
    }


    /**
     * @param Request $request
     *
     * @return bool
     */
    public function storeAssignmentWallet($request)
    {
        try {
            $this->data = $request->all();

            $userFrom     = decode($this->data['userFrom']);
            $userTo       = decode($this->data['userTo']);
            $certificates = [];

            foreach ($this->data['certificates'] as $c) {
                array_push($certificates, decode($c));
            }

            UserWalletAssignment::create([
                'id'              => date('U'),
                'ad_user_from_id' => $userFrom,
                'ad_user_to_id'   => $userTo,
                'product_code'    => 'de',
                'certificates'    => $certificates,
                'disabled'        => $this->data['disabled'],
            ]);

            if ($this->data['disabled']) {
                User::where('id', $userFrom)
                    ->update([ 'active' => false ]);
            }

            Header::whereIn('id', $certificates)
                ->update([ 'ad_user_id' => $userTo ]);

            return true;
        } catch (QueryException $e) {
            $this->errors = $e->getMessage();
        }

        return false;
    }


    /**
     * @return mixed
     */
    public function getUser()
    {
        $user = [
            'idx'                => $this->user->idc,
            'userName'           => $this->user->username,
            'fullName'           => $this->user->full_name,
            'email'              => $this->user->email,
            'type'               => $this->user->type->id,
            'cityIdx'            => $this->user->city->id,
            'cityName'           => $this->user->city->name,
            'agencyIdx'          => $this->user->agency ? $this->user->agency->id : null,
            'agencyName'         => $this->user->agency ? $this->user->agency->name : null,
            'additionalAgencies' => $this->user->additional_agencies,
            'phoneNumber'        => $this->user->phone_number,
            'position'           => $this->user->position,
            'active'             => $this->user->active,
            'passwordChange'     => $this->user->password_change,
            'profileIdx'         => $this->user->profile->first()->id,
            'profileSlug'        => $this->user->profile->first()->slug,
            'profileName'        => $this->user->profile->first()->name,
            'retailer'           => $this->user->retailerUser->retailer->id,
            'products'           => $this->user->retailerUser->products->pluck('id'),
            'companies'          => $this->user->retailerUser->companies,
            'permissionIdx'      => $this->user->permissions->pluck('id'),
            'permissionNames'    => $this->user->permissions->pluck('name'),
        ];

        return $user;
    }


    /**
     * @param bool $turn_off
     *
     * @return bool
     */
    public function passwordTurnOff($turn_off)
    {
        $this->model->password_change = $turn_off;

        return $this->saveModel();
    }
}