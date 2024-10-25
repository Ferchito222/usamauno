<?php

namespace Sibas\Repositories\Migration;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Sibas\Entities\Agency;
use Sibas\Entities\Permission;
use Sibas\Entities\Retailer\Cashier;
use Sibas\Entities\Retailer\RetailerUserProduct;
use Sibas\Entities\RetailerCity;
use Sibas\Entities\RetailerCityAgency;
use Sibas\Entities\RetailerUser;
use Sibas\Entities\User;
use Sibas\Entities\UserAssignment;
use Sibas\Entities\UserPermission;
use Sibas\Entities\UserProfile;

class UserMigrationRepository
{
    public $result = [];
    
    use MigrationTrait;

    /**
     * @return bool
     */
    public function dataExport()
    {
        $cashiers = DB::connection($this->conn_old)
            ->table('tbl_cajeros')
            ->select([
                'tbl_cajeros.nombre as full_name',
                'tbl_cajeros.ci as dni',
                DB::raw('CASE tbl_cajeros.sucursal
                            WHEN "beni" THEN 8
                            WHEN "chuquisaca" THEN 5
                            WHEN "cochabamba" THEN 4
                            WHEN "elalto" THEN 10
                            WHEN "lapaz" THEN 1
                            WHEN "oruro" THEN 2
                            WHEN "potosi" THEN 3
                            WHEN "santacruz" THEN 7
                            WHEN "tarija" THEN 6
                            END as ad_city_id'),
                DB::raw('IF(tbl_cajeros.agencia != "Seleccione", tbl_cajeros.agencia, "") as agency'),
                DB::raw('"CAJERO" as position'),
                DB::raw('IFNULL(tbl_cajeros.codigo, "") as code'),
                DB::raw('IFNULL(tbl_cajeros.email, "") as email'),
                DB::raw('IF(tbl_cajeros.activo=1, 1, 0) as active'),
            ]);

        $this->executeQuery($cashiers);

        $cashiers = $this->data;

        $users = DB::connection($this->conn_old)
            ->table('tblusuarios')
            ->join('tblagencia', 'tblusuarios.id_agencia', '=', 'tblagencia.id_agencia')
            ->leftJoin('tblcargos', 'tblusuarios.id_cargo', '=', 'tblcargos.id_cargo')
            ->leftJoin('ecoseguros_asignaciones', 'tblusuarios.idusuario', '=', 'ecoseguros_asignaciones.idusuario')
            ->leftJoin(
                DB::raw('
                    (SELECT
                      tup.idusuario,
                      count(tup.pagina)                      AS permission_number,
                      group_concat(tup.pagina SEPARATOR "|") AS permission_text
                    FROM tblusuariospermisos tup
                    WHERE tup.idusuario != "admin"
                    GROUP BY tup.idusuario
                    ORDER BY tup.idusuario ASC) usuariospermisos
                '), 'tblusuarios.idusuario', '=', 'usuariospermisos.idusuario'
            )
            ->select([
                'tblusuarios.idusuario as username',
                'tblusuarios.password',
                'tblusuarios.nombre as full_name',
                'tblusuarios.email',
                DB::raw('"" as dni'),
                DB::raw('IFNULL(tblusuarios.codigo, "") as code'),
                DB::raw('CASE tblcargos.id_cargo
                            WHEN 1 THEN 5
                            WHEN 2 THEN 6
                            WHEN 3 THEN 7
                            WHEN 4 THEN 8
                            WHEN 5 THEN 1
                            WHEN 6 THEN 2
                            WHEN 7 THEN 3
                            WHEN 8 THEN 4
                            ELSE null
                            END as ad_user_target_id'),
                'tblusuarios.fono_agencia as phone_number',
                DB::raw('IF(tblusuarios.cambio_contrasenia=1, 1, 0) as password_change'),
                DB::raw('CASE tblusuarios.departamento
                            WHEN "La Paz" THEN 1
                            WHEN "beni" THEN 8
                            WHEN "chuquisaca" THEN 5
                            WHEN "cochabamba" THEN 4
                            WHEN "elalto" THEN 10
                            WHEN "lapaz" THEN 1
                            WHEN "oruro" THEN 2
                            WHEN "pando" THEN 9
                            WHEN "potosi" THEN 3
                            WHEN "santacruz" THEN 7
                            WHEN "sucre" THEN 5
                            WHEN "tarija" THEN 6
                            ELSE null END as ad_city_id'),
                DB::raw('IF(tblagencia.id_agencia=0, null, tblagencia.id_agencia) as agency_id'),
                DB::raw('IF(tblagencia.id_agencia=0, "", tblagencia.desc_agencia) as agency_name'),
                'tblagencia.cod_agencia as agency_code',
                DB::raw('IF(tblagencia.id_agencia=0, "", tblagencia.tipo) as agency_type'),
                DB::raw('CASE tblagencia.departamento
                            WHEN "beni" THEN 8
                            WHEN "chuquisaca" THEN 5
                            WHEN "cochabamba" THEN 4
                            WHEN "elalto" THEN 10
                            WHEN "lapaz" THEN 1
                            WHEN "oruro" THEN 2
                            WHEN "potosi" THEN 3
                            WHEN "santacruz" THEN 7
                            WHEN "tarija" THEN 6
                            ELSE null END as agency_city'),
                DB::raw('CASE tblusuarios.tipo
                            WHEN "Creausuario" THEN 3
                            WHEN "Facultativo" THEN 2
                            WHEN "Logueado" THEN 2
                            WHEN "Operador" THEN 3
                            END as ad_user_type_id'),
                DB::raw('IF(tblusuarios.activado=1, 1, "0") as active'),
                DB::raw('IF(ecoseguros_asignaciones.id_asignacion is null, null, ecoseguros_asignaciones.descripcion) as assignment'),
                'usuariospermisos.permission_text',
            ])
            ->where('tblusuarios.idusuario', '!=', 'admin')
            ->whereNotNull('tblusuarios.departamento');

        $this->executeQuery($users);

        $users = $this->data;

        $this->data = collect([
            'cashiers' => $cashiers,
            'users'    => $users,
        ]);

        if ($this->data->count() > 1) {
            return true;
        }

        return false;
    }

    /**
     * @param Collection $data
     */
    public function dataImport($data)
    {
        $time_start             = microtime(true);
        $users                  = collect([]);
        $retailer_users         = collect([]);
        $retailer_user_products = collect([]);
        $retailer_cities        = collect([]);
        $agencies               = collect([]);
        $retailer_city_agencies = collect([]);
        $user_assignments       = collect([]);
        $user_profiles          = collect([]);
        $user_permissions       = collect([]);
        $cashiers               = collect([]);

        $this->date       = new Carbon();
        $id               = (int) $this->date->format('U');
        $password         = bcrypt('Abrenet123');
        $created_at       = $this->date->now();
        $retailer         = $this->getRetailer();
        $pms              = Permission::get();
        $retailer_user_id = 0;

        $cashiers = $this->data['cashiers']->map(function ($item, $key) use ($retailer, $created_at) {
            $item['id']             = ($key + 1);
            $item['ad_retailer_id'] = $retailer->id;
            $item['full_name']      = mb_strtoupper($item['full_name']);
            $item['slug']           = str_slug($item['full_name']);
            $item['assignment']     = '1:2016:A:15:1|1:2016:B:15:1|';
            $item['agency_slug']    = str_slug($item['agency']);
            $item['active']         = filter_var($item['active'], FILTER_VALIDATE_BOOLEAN);
            $item['created_at']     = $created_at;
            $item['updated_at']     = $created_at;

            return $item;
        });

        foreach ($data['users'] as $key => $row) {
            $id        += $key;
            $city_id   = (int) $row['ad_city_id'];
            $user_type = (int) $row['ad_user_type_id'];

            /*
             * RetailerCity
             */
            $retailer_cities->push([
                'id'             => null,
                'ad_retailer_id' => $retailer->id,
                'ad_city_id'     => $city_id,
                'active'         => true,
                'created_at'     => $created_at,
                'updated_at'     => $created_at,
            ]);

            /*
             * Agency
             */
            if (! empty($row['agency_name'])) {
                $agencies->push([
                    'id'            => null,
                    'agency_id_old' => $row['agency_id'],
                    'name'          => $row['agency_name'],
                    'code'          => empty($row['agency_code']) ? '' : $row['agency_code'],
                    'slug'          => str_slug($row['agency_name']),
                    'type'          => empty($row['agency_type']) ? '' : $row['agency_type'],
                    'agency_city'   => (int) $row['agency_city'],
                    'created_at'    => $created_at,
                    'updated_at'    => $created_at,
                ]);
            }

            /*
             * User
             */
            $username = $row['username'];

            if (empty($row['username']) && ! empty($row['full_name'])) {
                $username = random_username($row['full_name']);
            }

            $u = $users->contains(function ($key, $item) use ($row) {
                return $item['email'] === $row['email'];
            });

            $users->push([
                'id'                => $id,
                'username'          => $username,
                'username_old'      => $row['username'],
                'password'          => $password,
                'full_name'         => mb_strtoupper($row['full_name']),
                'email'             => $u ? $username . '-' . $id . '@abrenet.com' :
                    (empty($row['email']) ? $username . '-' . $id . '@abrenet.com' : $row['email']),
                'email_old'         => $u ? (empty($row['email']) ? null : $row['email']) : null,
                'dni'               => $row['dni'],
                'code'              => $row['code'],
                'position'          => '',
                'ad_user_target_id' => empty($row['ad_user_target_id']) ? null : (int) $row['ad_user_target_id'],
                'phone_number'      => is_null($row['phone_number']) ? '' : $row['phone_number'],
                'signature'         => '',
                'password_change'   => filter_var($row['password_change'], FILTER_VALIDATE_BOOLEAN),
                'password_date'     => $this->date->toDateTimeString(),
                'attempt'           => 0,
                'ad_city_id'        => $city_id,
                'ad_agency_id'      => null,
                'ad_user_type_id'   => $user_type,
                'active'            => filter_var($row['active'], FILTER_VALIDATE_BOOLEAN),
                'remember_token'    => null,
                'created_at'        => $created_at,
                'updated_at'        => $created_at,
                'agency_slug'       => str_slug($row['agency_name']),
            ]);

            /*
             * UserAssignment
             */
            if (! empty($row['assignment'])) {
                $user_assignments->push([
                    'id'         => null,
                    'ad_user_id' => $id,
                    'assignment' => $row['assignment'],
                    'created_at' => $this->date->now(),
                    'updated_at' => $this->date->now(),
                ]);
            }

            /*
             * RetailerUser
             */
            $retailer_user_id += 1;

            $retailer_users->push([
                'id'             => $retailer_user_id,
                'ad_retailer_id' => $retailer->id,
                'ad_user_id'     => $id,
                'ad_company_id'  => null,
                'active'         => true,
                'created_at'     => $this->date->now(),
                'updated_at'     => $this->date->now(),
            ]);

            /*
             * RetailerUserProduct
             */
            foreach ($retailer->retailerProducts as $retailerProduct) {
                $retailer_user_products->push([
                    'id'                  => null,
                    'ad_retailer_user_id' => $retailer_user_id,
                    'ad_product_id'       => $retailerProduct->companyProduct->product->id,
                    'created_at'          => $this->date->now(),
                    'updated_at'          => $this->date->now(),
                ]);
            }

            /*
             * UserProfile / UserPermission
             */
            $profile = 3;                       // Vendedor
            $ps      = collect([]);             // Ninguno

            if (! empty($row['permission_text'])) {
                $permissions = array_unique(explode('|', $row['permission_text']));

                foreach ($permissions as $permission) {
                    if ($permission === 'facultativo' || $permission === 'farep') {
                        $profile = 4;       // Compañia
                    } elseif (strpos($permission, 'reporte') !== false
                              || strpos($permission, 'repsolpr') !== false
                              || strpos($permission, 'nacional') !== false
                              || strpos($permission, 'sucursal') !== false
                              || strpos($row['email'], 'bisa') !== false
                    ) {
                        $profile = 2;       // Reportes
                    } elseif ($permission === 'creausuario' || $permission === 'nosotros') {
                        $profile = null;    // Ninguno
                    }

                    if ($permission === 'anulaciones') {
                        $ps->push('CA');
                    }
                    if ($permission === 'anularde') {
                        $ps->push('CADE');
                    }
                    if ($permission === 'facultativoau') {
                        $ps->push('FAU');
                    }
                    if ($permission === 'facultativotr') {
                        $ps->push('FTD');
                    }
                    if ($permission === 'fide' || $permission === 'fideicomisos') {
                        $ps->push('TRU');
                    }
                    if ($permission === 'nacional' || $permission === 'soloreportes') {
                        $ps->push('RN');
                    } elseif ($permission === 'sucursal') {
                        $ps->push('RR');
                    } elseif ($permission === 'repsolpr' ) {
                        $ps->push('RA');
                    } elseif ($permission === 'sinacceso') {
                        $ps->push('RU');
                    }
                    if ($permission === 'reporteanulado') {
                        $ps->push('RCA');
                    }
                    if ($permission === 'reportecredin') {
                        $ps->push('RCR');
                    }
                    if ($permission === 'reporteecosud') {
                        $ps->push('RSS');
                    }
                    if ($permission === 'reporteprovida') {
                        $ps->push('RPR');
                    }
                }
            }

            if (strpos($row['email'], 'bisa') !== false) {
                $profile = 2;       // Reportes
            }

            /*
             * UserProfile
             */
            if (! is_null($profile)) {
                $user_profiles->push([
                    'id'            => null,
                    'ad_user_id'    => $id,
                    'ad_profile_id' => $profile,
                    'active'        => true,
                    'created_at'    => $this->date->now(),
                    'updated_at'    => $this->date->now(),
                ]);
            }

            /*
             * UserPermission
             */
            if ($ps->count() > 0) {
                $ps->each(function ($p, $k) use ($pms, $user_permissions, $id) {
                    $permission_id = $pms->search(function ($item, $key) use ($p) {
                        return $item->slug === $p;
                    });

                    $user_permissions->push([
                        'id'               => null,
                        'ad_user_id'       => $id,
                        'ad_permission_id' => $pms[$permission_id]->id,
                        'active'           => true,
                        'created_at'       => $this->date->now(),
                        'updated_at'       => $this->date->now(),
                    ]);
                });
            } elseif ($profile === 3) {
                $permission_id = $pms->search(function ($item, $key) {
                    return $item->slug === 'RU';
                });

                $user_permissions->push([
                    'id'               => null,
                    'ad_user_id'       => $id,
                    'ad_permission_id' => $pms[$permission_id]->id,
                    'active'           => true,
                    'created_at'       => $this->date->now(),
                    'updated_at'       => $this->date->now(),
                ]);
            }
        }

        /*
         * RetailerCity
         */
        $retailer_cities = $retailer_cities->unique(function ($item) {
            return $item['ad_retailer_id'] . $item['ad_city_id'];
        })->sortBy(function ($item, $key) {
            return $item['ad_city_id'];
        })->values()->map(function ($item, $key) {
            $item['id'] = $key + 1;

            return $item;
        });

        /*
         * Agency
         */
        $agencies = $agencies->unique(function ($item) {
            return $item['slug'];
        })->sortBy(function ($item, $key) {
            return $item['slug'];
        })->values()->map(function ($item, $key) {
            $item['id'] = $key + 1;

            return $item;
        });

        /*
         * RetailerCityAgency
         */
        $agencies = $agencies->map(function ($item, $key) use (&$retailer_city_agencies, $retailer_cities) {
            $rc_id = $retailer_cities->search(function ($rci, $rck) use ($item) {
                return $rci['ad_city_id'] === $item['agency_city'];
            });

            if ($rc_id !== false) {
                $retailer_city_agencies->push([
                    'id'                  => null,
                    'ad_retailer_city_id' => $retailer_cities[$rc_id]['id'],
                    'ad_agency_id'        => $item['id'],
                    'active'              => true,
                    'created_at'          => $this->date->now(),
                    'updated_at'          => $this->date->now(),
                ]);
            }

            unset($item['agency_city']);

            return $item;
        });

        /*
         * User
         */
        $users = $users->map(function ($item, $key) use ($agencies) {
            $agency_id = $agencies->search(function ($ai, $ak) use ($item) {
                return $item['agency_slug'] === $ai['slug'];
            });

            if ($agency_id !== false) {
                $item['ad_agency_id'] = $agencies[$agency_id]['id'];
            }

            unset($item['agency_slug']);

            return $item;
        });

        Agency::insert($agencies->toArray());
        RetailerCity::insert($retailer_cities->toArray());
        RetailerCityAgency::insert($retailer_city_agencies->toArray());
        User::insert($users->toArray());
        UserAssignment::insert($user_assignments->toArray());
        RetailerUser::insert($retailer_users->toArray());
        RetailerUserProduct::insert($retailer_user_products->toArray());
        UserProfile::insert($user_profiles->toArray());
        UserPermission::insert($user_permissions->toArray());
        Cashier::insert($cashiers->toArray());

        // var_dump('Users. Total execution time in seconds: ' . (microtime(true) - $time_start) . '. Total: ' . User::count());
    }
    
    public function getUserByUemail($email)
    {
        if(User::where('email',$email)->count()>0)
                return true;
        else
                return false;
    }
    
    public function storeUser($data)
    {
        return DB::table('ad_users')->insert($data);
    }
    
    public function storeRetailerUser($data)
    {
        return DB::table('ad_retailer_users')->insert($data);
    }
    
    public function storeRetailerUserProduct($data)
    {
        return DB::table('ad_retailer_user_products')->insert($data);
    }
    
    public function storeUserPermission($data)
    {
        return DB::table('ad_user_permissions')->insert($data);
    }
    
    public function storeUserProfile($data)
    {
        return DB::table('ad_user_profiles')->insert($data);
    }
    
    public function getUserById($id)
    {
        $this->result = DB::table('ad_users')->where('id',$id)->get();
        if(count($this->result)>0){
            $this->result = $this->result[0];
            return true;
        }else
            return false;
    }
    
    public function getResult()
    {
        return $this->result;
    }
}