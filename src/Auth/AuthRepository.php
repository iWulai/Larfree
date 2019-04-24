<?php

namespace Larfree\Auth;

use Larfree\Repository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Larfree\Exceptions\ApiErrorException;

class AuthRepository extends Repository
{
    protected $columns = ['password'];

    /**
     * @var JWTAuthModel
     */
    protected $model;

    /**
     * AuthRepository constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        if (! $model = Config::get('larfree.auth.model'))
        {
            throw new \Exception('Not Found The Larfree Auth Model', 500);
        }

        parent::__construct(new $model);
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
            throw new ApiErrorException('认证错误！账号或密码错误。');
        }

        $user->setAttribute('token', Auth::guard()->login($user));

        return $user;
    }
}