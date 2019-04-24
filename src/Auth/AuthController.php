<?php

namespace Larfree\Auth;

use Larfree\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $validator = [
        'loginUsePhone' => [
            'rules' => [
                'username' => 'bail|required|cellphone',
                'password' => 'bail|required|between:3,18|password',
            ],
            'messages' => [],
        ],
        'loginUseEmail' => [
            'rules' => [
                'username' => 'bail|required|email',
                'password' => 'bail|required|between:3,18|password',
            ],
            'messages' => [],
        ],
    ];

    public function __construct(AuthRepository $repository)
    {
        $this->repository = $repository;
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