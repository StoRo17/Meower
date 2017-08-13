<?php

namespace Meower\Core\Auth;

use Meower\Core\Session;

class Auth
{
    /**
     * @var bool
     */
    private $auth = false;

    /**
     * @var User
     */
    private $user;

    /**
     * Return is user authenticated.
     * @return bool
     */
    public function auth()
    {
        return $this->auth;
    }

    /**
     * Return the authenticated user.
     * @return User|null
     */
    public function user()
    {
        return $this->user;
    }

    /**
     * Authorize the user.
     *
     * @param $user
     */
    public function authorize($user)
    {
        Session::put('user_id', $user->id);
        $this->auth = true;
        $this->user = $user;
    }

    /**
     * Unauthorize the user.
     */
    public function unAuthorize()
    {
        Session::delete('user_id');
        $this->auth = false;
        $this->user = null;
    }
}