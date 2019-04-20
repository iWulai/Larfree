<?php

namespace Larfree\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Larfree\Exceptions\ValidateException;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Request as BaseRequest;

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
     * @var Request
     */
    protected $request;

    /**
     * @var Paginator
     */
    protected $paginator;

    /**
     * @var int
     */
    protected $userId;

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
        $request = BaseRequest::instance();

        $this->paginator = Paginator::make($request->get('per_page', $request->get('page')));

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

            $this->request = Request::make($validator->validated());
        }

        $this->userId = auth_user_id();

        return call_user_func_array([$this, $method], $parameters);
    }
}