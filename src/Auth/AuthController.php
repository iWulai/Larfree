<?php

namespace Larfree\Auth;

use Larfree\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class AuthController extends Controller
{
    protected $validator = [
        'loginUsePhone' => [
            'rules' => [
                'username' => 'bail|required|cellphone',
                'password' => 'bail|required|password',
            ],
            'messages' => [],
        ],
        'loginUseEmail1' => [
            'rules' => [
                'username' => 'bail|required|email',
                'password' => 'bail|required|password',
            ],
            'messages' => [],
        ],
    ];

    public function __construct(AuthRepository $repository)
    {
        $this->repository = $repository;

        $this->validator['loginUsePhone']['messages'] = Config::get('larfree.auth.validator_messages.phone');

        $this->validator['loginUseEmail']['messages'] = Config::get('larfree.auth.validator_messages.email');
    }

    public function logout()
    {
        Auth::guard()->logout();

        return '退出登录成功！';
    }

    /**
     * @author iwulai
     *
     * @return \Larfree\Model
     *
     * @throws \Larfree\Exceptions\ApiErrorException
     */
    public function loginUsePhone()
    {
        return $this->repository->login($this->request->username, $this->request->password);
    }

    /**
     * @author iwulai
     *
     * @return \Larfree\Model
     *
     * @throws \Larfree\Exceptions\ApiErrorException
     */
    public function loginUseEmail()
    {
        return $this->repository->setLoginColumn('email')->login($this->request->username, $this->request->password);
    }
}