<?php

namespace App\Http\Controllers;

use App\Services\Accounts\AccountService;
use App\Services\Accounts\IdentityService;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function __construct()
    {
    }

    public function createAccount(Request $request): mixed
    {
        return AccountService::create($request);
    }

    public function updateAccount(Request $request): mixed
    {
        return AccountService::update($request);
    }

    public function storeAccountIdentity(Request $request): mixed
    {
        return IdentityService::create($request);
    }
}
