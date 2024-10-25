<?php

namespace Sibas\Repositories\Adm;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Sibas\Entities\Email;
use Sibas\Entities\RetailerProductEmail;
use Sibas\Repositories\BaseRepository;

class EmailAdmRepository extends BaseRepository
{

    public function getEmails($id = null)
    {
        $user       = auth()->user();
        $ruProducts = $user->retailerUser->userProducts->pluck('ad_product_id')->toArray();

        $emails = Email::with([
            'retailerProducts' => function ($q) use ($ruProducts) {
                $q->with('companyProduct.product')
                    ->where('type', '!=', 'RP')
                    ->whereHas('companyProduct.product', function ($q) use ($ruProducts) {
                        $q->whereIn('id', $ruProducts);
                    });
            },
        ])->orderBy('email', 'asc');

        if (! is_null($id)) {
            $email = $emails->where('id', $id)->get();

            if ($email->count() === 1) {
                $this->model = $email->first();

                return true;
            }

            return false;
        }

        $emails = $emails->get();

        $api = collect([
            'emails' => collect([]),
        ]);

        $emails->each(function ($e) use ($api) {
            $api['emails']->push([
                'idx'          => $e->id,
                'email'        => $e->email,
                'name'         => $e->name,
                'products'     => $e->retailerProducts->pluck('pivot'),
            ]);
        });

        return $api;
    }


    /**
     * @param Request $request
     *
     * @return bool
     */
    public function emailStore($request)
    {
        try {
            $products = [];
            $date     = $this->carbon->now();

            foreach ($request->get('products') as $product) {
                $products[$product] = [
                    'active'     => true,
                    'created_at' => $date->toDateTimeString(),
                    'updated_at' => $date->toDateTimeString(),
                ];
            }

            $this->model = Email::create([
                'email' => $request->get('email'),
                'name'  => $request->get('name'),
            ]);

            $this->model->retailerProducts()->sync($products);
            $this->model = $this->model->fresh([ 'retailerProducts.companyProduct.product' ]);

            return true;
        } catch (QueryException $e) {
            $this->errors = $e->getMessage();
        }

        return false;
    }


    /**
     * @param Model|Email $email
     * @param Request     $request
     *
     * @return bool
     */
    public function emailUpdate($email, $request)
    {
        try {
            $products = [];
            $date     = $this->carbon->now();

            foreach ($request->get('products') as $product) {
                $products[$product] = [
                    'active'     => true,
                    'created_at' => $date->toDateTimeString(),
                    'updated_at' => $date->toDateTimeString(),
                ];
            }

            $email->update([
                'email' => $request->get('email'),
                'name'  => $request->get('name'),
            ]);

            $email->retailerProducts()->sync($products);

            return true;
        } catch (QueryException $e) {
            $this->errors = $e->getMessage();
        }

        return false;
    }


    public function emailTurnOff($id, $turn_off)
    {
        try {
            $active = RetailerProductEmail::where('id', $id)->update([ 'active' => $turn_off ]);

            if ($active) {
                return true;
            }
        } catch (QueryException $e) {
            $this->errors = $e->getMessage();
        }

        return false;
    }


    public function emailDelete($id)
    {
        try {
            Email::where('id', $id)->delete();

            return true;
        } catch (QueryException $e) {
            $this->errors = $e->getMessage();
        }

        return false;
    }


    public function getApiModel()
    {
        $email = [
            'idx'          => $this->model->id,
            'email'        => $this->model->email,
            'name'         => $this->model->name,
            'products'     => $this->model->retailerProducts->pluck('pivot'),
        ];

        return $email;
    }
}