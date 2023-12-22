<?php

namespace Framework\Middleware;

use Framework\Session;

class Authorize
{
    /**
     * Check if user is authenticated
     *
     * @return boolean
     */
    public function isAuthenticated()
    {
        return Session::has("user");
    }

    /**
     * Handle a user's request
     *
     * @param string $role
     * @return boolean
     */
    public function handle($role)
    {
        if ($role === 'guest' && $this->isAuthenticated()) {
            return redirect('/');
        } elseif ($role === 'auth' && !$this->isAuthenticated()) {
            return redirect('/login');
        }
    }
}