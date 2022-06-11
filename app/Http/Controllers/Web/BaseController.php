<?php

namespace App\Http\Controllers\Web;

use App\Base\Constants\Auth\Role;
use App\Http\Controllers\ApiController;
use App\Models\User;
use Redirect;

abstract class BaseController extends ApiController
{
    protected function validateAdmin()
    {
        $user = auth()->user();

        if (!$user) {
            return Redirect::to('login')->send();
        } else {
            //  check his role if admin roles
            if ($user->hasRole(Role::adminRoles())) {
                $this->user = $user;
            } else {
                return Redirect::to('unauthorized')->send();
            }
        }
    }
}
