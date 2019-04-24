<?php

namespace Larfree\Providers;

use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class ValidatorServiceProvider extends ServiceProvider
{
    protected const VALIDATORS = [
        'cellphone' => [
            'regex' => '/^1[3456789][0-9]{9}$/',
            'message' => 'The :attribute must be a valid cellphone number.',
        ],
        'telephone' => [
            'regex' => '/(^(0[0-9]{2}-?)([2-9][0-9]{7})(-[0-9]{1,4})?$)|(^(0[0-9]{3}-?)([2-9][0-9]{6})(-[0-9]{1,4})?$)|(^400(-?[0-9]{3,4}){2}$)/',
            'message' => 'The :attribute must be a valid telephone number.',
        ],
        'phone' => [
            'regex' => '/(^1[3456789][0-9]{9}$)|((^(0[0-9]{2}-?)([2-9][0-9]{7})(-[0-9]{1,4})?$)|(^(0[0-9]{3}-?)([2-9][0-9]{6})(-[0-9]{1,4})?$)|(^400(-?[0-9]{3,4}){2}$))/',
            'message' => 'The :attribute must be a valid phone number.',
        ],
        'password' => [
            'regex' => '/^[a-zA-Z0-9]{3,18}$/',
            'message' => 'The :attribute may only contain letters and numbers.',
        ],
    ];

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerValidator();
    }

    protected function registerValidator()
    {
        foreach (static::VALIDATORS as $attribute => $validator)
        {
            if ($regex = Arr::get($validator, 'regex'))
            {
                Validator::extend($attribute, function ($attribute, $value, $parameters, $validator) use ($regex)
                    {
                        return !! preg_match($regex, $value);
                    }
                );
            }

            if ($default = Arr::get($validator, 'message'))
            {
                Validator::replacer($attribute, function ($message, $attribute, $rule, $parameters) use ($default)
                    {
                        return $this->getMessage($message, $rule, str_replace(':attribute', $attribute, $default));
                    }
                );
            }
        }
    }

    protected function getMessage(string $message, string $rule, string $default)
    {
        return $message === 'validation.' . $rule ? $default : $message;
    }
}
