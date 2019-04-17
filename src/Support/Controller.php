<?php

namespace Larfree\Support;

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
    protected $validator = [];

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

        if (! empty($this->validator[$method]) && in_array($request->method(), ['POST', 'PUT']))
        {
            $rule = $this->validator[$method]['rules'] ?? [];

            $message = $this->validator[$method]['messages'] ?? [];
            /**
             * @var \Illuminate\Validation\Validator $validator
             */
            $validator = Validator::make($request->all(), $rule, $message);

            if ($validator->fails())
            {
                throw new ValidateException($validator);
            }

            $request->replace($validator->validated());
        }

        return call_user_func_array([$this, $method], $parameters);
    }
}