<?php

namespace Larfree\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Larfree\Exceptions\ValidateException;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    /**
     * @var Repository
     */
    protected $repository;

    /**
     * @var array
     */
    protected $validator = [
        'store' => [
            'rules' => [
                //
            ],
            'messages' => [
                //
            ],
        ],
        'update',
    ];

    /**
     * @author iwulai
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws ValidateException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function callAction($method, $parameters)
    {
        $request = Request::instance();

        if (isset($this->validator[$method]) || in_array($method, $this->validator))
        {
            $rules = Arr::get($this->validator, $method . '.rules', []);

            $messages = Arr::get($this->validator, $method . '.messages', []);
            /**
             * @var \Illuminate\Validation\Validator $validator
             */
            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails())
            {
                throw new ValidateException($validator);
            }

            $request->replace($validator->validated());
        }

        return call_user_func_array([$this, $method], $parameters);
    }
}