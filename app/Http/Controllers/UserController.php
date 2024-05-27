<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

/**
 * Our controller need to be clean, so just pass and use it for gateway between Services and Routes.
 */
class UserController extends Controller
{
    public function __construct()
    {
        //
    }

    public function getUserList(): object
    {
        return UserService::getList();
    }

    public function getUserData(Request $request): object
    {
        return UserService::getDetail($request);
    }
}
