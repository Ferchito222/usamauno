<?php

namespace Sibas\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\ServiceProvider;
use Validator;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /*
         * Validate alphabetical chars & spaces only
         */
        Validator::extend('alpha_space', function ($attribute, $value, $parameters, $validator) {
            //return preg_match('/^([a-zA-Z ])+$/i', $value);
            return preg_match('/^[\pL\pM ]+$/u', $value);
        });

        /*
         * Validate alphabetical chars, numbers & spaces only
         */
        Validator::extend('alpha_num_space', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^([a-zA-Z0-9 ])+$/i', $value);
        });

        /*
         * Validate alphabetical chars, dashes & spaces only
         */
        Validator::extend('alpha_dash_space', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[\pL\pM\pN _-]+$/u', $value);
        });

        /*
         * Validate full alphabetical chars, dashes & spaces only
         */
        Validator::extend('ands_full', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[\pL\pM\pN #\/,.:%_-]+$/u', $value);
        });

        /*
         * Validate old password
         */
        Validator::extend('old_password', function ($attribute, $value, $parameters, $validator) {
            return Hash::check($value, current($parameters));
        });

        Validator::replacer('old_password', function ($message, $attribute, $rule, $parameters) {
            return str_replace('validation.old_password', 'La contraseña no coincide con la actual', $message);
        });

        /*
         * Extend blade to define a variable
         */
        Blade::extend(function ($value) {
            return preg_replace('/\@var(.+)/', '<?php ${1}; ?>', $value);
        });
    }


    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
