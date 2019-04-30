<?php

namespace Larfree\Auth;

use Larfree\Repository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Larfree\Exceptions\ApiErrorException;

class AuthRepository extends Repository
{
    protected $columns = ['id', 'password'];

    /**
     * @var JWTAuthModel
     */
    protected $model;

    public function __construct()
    {
        $model = Config::get('larfree.auth.model');

        if ($model) parent::__construct(new $model());
    }

    public function setLoginColumn(string $column)
    {
        $this->model->setLoginColumn($column);

        return $this;
    }

    /**
     * @author iwulai
     *
     * @param string $value
     * @param string $password
     *
     * @return \Larfree\Model
     *
     * @throws ApiErrorException
     */
    public function login(string $value, string $password)
    {
        $user = $this->where($this->model->getLoginColumn(), $value)->orderByDesc('created_at')->first();

        if (is_null($user) || ! password_verify($password, $user->getAttributeValue('password')))
        {
            throw new ApiErrorException('认证错误！账号或密码错误，请确认。');
        }

        $user->setAttribute('token', Auth::guard()->login($user));

        return $user;
    }

    public function logout()
    {
        Auth::guard()->logout();

        return '退出登录成功';
    }
}