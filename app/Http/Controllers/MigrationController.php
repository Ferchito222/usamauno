<?php

namespace Sibas\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Sibas\Http\Requests;
use Sibas\Http\Controllers\Controller;
use Sibas\Repositories\Migration\ClientMigrationRepository;
use Sibas\Repositories\Migration\ElpCollectionMigrationRepository;
use Sibas\Repositories\Migration\ElpMigrationRepository;
use Sibas\Repositories\Migration\UserMigrationRepository;

class MigrationController extends Controller
{
    /**
     * @var UserMigrationRepository
     */
    private $userRepository;
    /**
     * @var ClientMigrationRepository
     */
    private $clientRepository;
    /**
     * @var ElpMigrationRepository
     */
    private $elpRepository;
    /**
     * @var ElpCollectionMigrationRepository
     */
    private $elpCollectionRepository;

    public function __construct(
        UserMigrationRepository $userRepository,
        ClientMigrationRepository $clientRepository,
        ElpMigrationRepository $elpRepository,
        ElpCollectionMigrationRepository $elpCollectionRepository
    ) {
        $this->userRepository          = $userRepository;
        $this->clientRepository        = $clientRepository;
        $this->elpRepository           = $elpRepository;
        $this->elpCollectionRepository = $elpCollectionRepository;
    }

    public function index()
    {
        return view('migration.index');
    }

    public function export(Request $request)
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);

        $time_start = microtime(true);

        $types = $request->get('types');

        if (! is_null($types)) {
            $data     = collect([]);
            $filename = 'Migration_:type_' . date('Ymd');

            try {
                foreach ($types as $type) {
                    if ($type === 'users') {
                        if ($this->userRepository->dataExport()) {
                            $data[$type] = $this->userRepository->getData();
                        }
                    }

                    if ($type === 'clients') {
                        if ($this->clientRepository->dataExport()) {
                            $data[$type] = $this->clientRepository->getData();

                            if ($this->clientRepository->dataActivityExport()) {
                                $data['activities'] = $this->clientRepository->getData();
                            }
                        }
                    }

                    if ($type === 'elp') {
                        if ($this->elpRepository->dataExport()) {
                            $data[$type] = $this->elpRepository->getData();

                            if ($this->elpCollectionRepository->dataCollectionExport()) {
                                $data['elp_collections'] = $this->elpCollectionRepository->getData();
                            }

                            if ($this->elpCollectionRepository->dataCollectionRecordExport()) {
                                $data['elp_collection_records'] = $this->elpCollectionRepository->getData();
                            }
                        }
                    }
                }

                if ($data->count() > 0) {
                    // $this->exportToCSV($filename, $data);
                    $this->import($filename, $data);
                    var_dump('Total execution time in seconds: ' . (microtime(true) - $time_start));
                }
            } catch (QueryException $e) {
                dd($e->getMessage());
            }
        }

        return redirect()->route('migration::index');
    }


    /**
     * @param string     $filename
     * @param Collection $data
     */
    private function exportToCSV($filename, $data)
    {
        $types = $data->keys();

        foreach ($types as $type) {
            $fn = str_replace(':type', ucfirst($type), $filename);

            Excel::create($fn, function($excel) use ($data, $type) {
                $excel->sheet($type, function($sheet) use ($data, $type) {
                    $items = $data[$type]->chunk(2000);
                    $keys  = array_keys($items->first()[0]);

                    $sheet->appendRow($keys);

                    foreach ($items as $item) {
                        $sheet->rows($item);
                    }
                });
            })->store('csv');
        }
    }


    /**
     * @param string     $filename
     * @param Collection $data
     */
    private function import($filename, $data)
    {
        $types = $data->keys();

        foreach ($types as $type) {
            switch ($type) {
                case 'users':
                    $this->userRepository->dataImport($data[$type]);
                    break;
                case 'clients':
                    $this->clientRepository->dataImport($data[$type], $data['activities']);
                    break;
                case 'elp':
                    $this->elpRepository->dataImport($data[$type], $data['elp_collections'], $data['elp_collection_records']);
                    break;
            }
        }

        /*try {
            Excel::load(storage_path('exports/' . $filename . '.csv'), function($reader) {
                $results = $reader->get();

                foreach ($results as $sheet) {
                    $title = $sheet->getTitle();
                }

                dd($results);
            });
        } catch (\PHPExcel_Reader_Exception $e) {
            var_dump($e->getMessage());
        } catch (QueryException $e) {
            var_dump($e->getMessage());
        }

        dd('Err');*/
    }
    
    public function importUsers()
    {
        return view('migration.users.import-form');
    }
    
    public function readXls(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            
            Excel::load($file, function($reader) {
                $results = $reader->get();
                
                $user_migrate = $this->storeUser($results->toArray());
                echo '<pre>';
                print_r($user_migrate);
                echo '</pre>';
            });
        }
        return view('migration.users.import-form');
    }
    
    public function storeUser($users)
    {
        $errors = [];
        foreach ($users[0] as $key => $value) {
            
            if($value['email']!=''){
                if(!$this->userRepository->getUserByUemail($value['email'])){
                    $username = explode('@', $value['email']);
                    $user = [
                        'id'                => DB::table('ad_users')->max('id')+1,
                        'username'          => $username[0],
                        'password'          => '$2y$10$5yuc6rT5UBcYPFZcet8NTuhaVtnffHjjBTkf0tLuYBTFLaECmCuii',
                        'full_name'         => $value['full_name'],
                        'email'             => $value['email'],
                        'ad_city_id'        => $value['id_city'],
                        'ad_agency_id'      => $value['id_agency'],
                        'ad_user_type_id'   => 2,
                        'active'            => true,
                    ];
                    if($value['dni'])
                        $user['dni'] = $value['dni'];
                    if($value['position'])
                        $user['position'] = $value['position'];
                    if($this->userRepository->storeUser($user)){
                        $retailer_user = [
                            'id'                => DB::table('ad_retailer_users')->max('id')+1,
                            'ad_retailer_id'    => 1,
                            'ad_user_id'        => $user['id'],
                            'companies'         => '[]',
                            'active'            => true,
                        ];
                        if($this->userRepository->storeRetailerUser($retailer_user)){
                            $err = [];
                            $products = explode(',', $value['products']);
                            foreach ($products as $key => $value) {
                                if($product = DB::table('ad_products')->where('code', $value)->get()){
                                    $user_product = [
                                        'id' => DB::table('ad_retailer_user_products')->max('id')+1,
                                        'ad_retailer_user_id' => $retailer_user['id'],
                                        'ad_product_id' => $product[0]->id,
                                    ];
                                    if($this->userRepository->storeRetailerUserProduct($user_product)){
                                        
                                    }else
                                        $err[]=$value;
                                }
                            }
                            
                            if($err){
                                $users[0][$key]['error']='Error '. implode(',', $err);
                            }else{
                                #permisos usuario
                                $user_permission = [
                                    'id'                => DB::table('ad_user_permissions')->max('id')+1,
                                    'ad_user_id'        => $user['id'],
                                    'ad_permission_id'   => 4,
                                    'active'            => true,
                                ];
                                if($this->userRepository->storeUserPermission($user_permission)){

                                }else
                                   $users[0][$key]['error']='Error al registrar permisos'; 

                                #perfil usuario
                                $user_profile = [
                                    'id'                => DB::table('ad_user_profiles')->max('id')+1,
                                    'ad_user_id'        => $user['id'],
                                    'ad_profile_id'     => 3,
                                    'active'            => true,
                                ];
                                if($this->userRepository->storeUserProfile($user_profile)){

                                }else
                                   $users[0][$key]['error']='Error al registrar permisos';
                            }
                        }else
                            $users[0][$key]['error']='Error al registrar retailer user';
                        
                    }else
                        $users[0][$key]['error']='Error al registrar usuario';
                }else
                    $users[0][$key]['error']='Ya se encuentra registrado en abrenet';
            }else
                $users[0][$key]['error']='No cuenta con Email';
        }
        return $users[0];
    }        
}
