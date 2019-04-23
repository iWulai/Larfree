<?php

namespace Larfree\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class ValidatorServiceProvider extends ServiceProvider
{
    protected $cellphoneRegex = '/^1[3456789][0-9]{9}$/';

    protected $telephoneRegex = '/(^(0[0-9]{2}-?)([2-9][0-9]{7})(-[0-9]{1,4})?$)|(^(0[0-9]{3}-?)([2-9][0-9]{6})(-[0-9]{1,4})?$)|(^400(-?[0-9]{3,4}){2}$)/';

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->cellphone();

        $this->telephone();

        $this->phone();
    }

    protected function cellphone()
    {
        Validator::extend('cellphone', function ($attribute, $value, $parameters, $validator)
            {
                return !! preg_match($this->cellphoneRegex, $value);
            }
        );

        Validator::replacer('cellphone', function ($message, $attribute, $rule, $parameters)
            {
                return $message === 'validation.' . $rule ? '手机号码格式错误！请填写正确的手机号码。' : $message;
            }
        );
    }

    protected function telephone()
    {
        Validator::extend('telephone', function ($attribute, $value, $parameters, $validator)
            {
                return !! preg_match($this->telephoneRegex, $value);
            }
        );

        Validator::replacer('telephone', function ($message, $attribute, $rule, $parameters)
            {
                return $message === 'validation.' . $rule ? '座机号码格式错误！请填写正确的座机号码（分机号）或400电话。' : $message;
            }
        );
    }

    protected function phone()
    {
        Validator::extend('phone', function ($attribute, $value, $parameters, $validator)
            {
                if (preg_match($this->cellphoneRegex, $value))
                {
                    return true;
                }

                return !! preg_match($this->telephoneRegex, $value);
            }
        );

        Validator::replacer('phone', function ($message, $attribute, $rule, $parameters)
            {
                return $message === 'validation.' . $rule ? '电话号码格式错误！请填写正确的电话号码。允许手机号码，座机号码（分机号），400号码' : $message;
            }
        );
    }
}
