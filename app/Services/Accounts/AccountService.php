<?php

namespace App\Services\Accounts;

use Error;
use Exception;
use App\Lib\GlobalFunction;
use App\Lib\ReturnCode;
use App\Models\Account\Account;
use App\Repositories\Accounts\AccountRepository;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/**
 * Our service is where the bussiness logic based on the name
 */
class AccountService
{
    public function __construct()
    {
    }

    public static function attachIdentityToAccount(int $id, int $identity_id): array
    {
        try {
            $validate = Validator::make(['id' => $id, 'identity_id' => $identity_id], [
                'id' => ['required', Rule::exists('accounts', 'id')],
                'identity_id' => ['required', Rule::exists('identities', 'id')],
            ]);

            if ($validate->fails()) {
                return GlobalFunction::responseFormat(ReturnCode::VALIDATION_ERROR, $validate->errors()->toArray(), null);
            }
        } catch (\Throwable $th) {
            return GlobalFunction::responseFormat(ReturnCode::ERROR, $th, null);
        }

        $data = AccountRepository::bindIdentity($id, $identity_id);

        return GlobalFunction::responseFormat(ReturnCode::SUCCESS, $data, null);
    }

    public static function create(Request $request): mixed
    {
        try {
            $validate = Validator::make($request->all(), [
                'username' => 'string|required',
                'email' => ['email', Rule::unique('users', 'email')],
                'password' => 'string|required',
                'role_id' => 'integer|required',
            ]);

            if ($validate->fails()) {
                return response()->json(GlobalFunction::responseFormat(ReturnCode::VALIDATION_ERROR, $validate->errors()->toArray(), null));
            }
        } catch (\Throwable $th) {
            return response()->json(GlobalFunction::responseFormat(ReturnCode::ERROR, $th, null));
        }

        $createUser = UserService::create($request['username'], $request['email'], $request['password']);

        if ($createUser['code'] !== ReturnCode::SUCCESS->code()) {
            $deleteUser = UserService::delete($createUser['data']['id']);

            if ($deleteUser !== ReturnCode::SUCCESS->code()) {
                return GlobalFunction::responseFormat(ReturnCode::ERROR, $deleteUser, null);
            }

            return response()->json($createUser);
        }

        $data = AccountRepository::create($createUser['data']['id'], $request['role_id'], Account::getStatus('active'));

        if ($data instanceof \Exception) {
            return GlobalFunction::responseFormat(ReturnCode::ERROR, $data, null);
        }

        $getAccountData = AccountRepository::getDetail($createUser['data']['id']);

        return response()->json(GlobalFunction::responseFormat(ReturnCode::SUCCESS, $getAccountData, null));
    }

    public static function update(Request $request): mixed
    {
        $getAccount = AccountRepository::getDetail(Auth::user()->id, null);

        if (empty($getAccount)) {
            return response()->json(GlobalFunction::responseFormat(ReturnCode::DATA_NOT_FOUND, [], null));
        }

        $request = $request->all();
        $request['id'] = $getAccount->id;

        try {
            $validate = Validator::make($request, [
                'id' => 'integer|required',
                'username' => 'string',
                'email' => 'email',
                'password' => 'string',
                'role_id' => 'integer',
            ]);

            if ($validate->fails()) {
                return response()->json(GlobalFunction::responseFormat(ReturnCode::VALIDATION_ERROR, $validate->errors()->toArray(), null));
            }
        } catch (\Throwable $th) {
            return response()->json(GlobalFunction::responseFormat(ReturnCode::ERROR, $th, null));
        }

        $updateUser = UserService::update($getAccount['user_id'], $request['username'], $request['email'], $request['password']);

        if ($updateUser['code'] !== ReturnCode::SUCCESS->code()) {

            return response()->json($updateUser);
        }

        if (!empty($request['role_id'])) {
            $data = AccountRepository::update($request['id'], $request['role_id']);

            if ($data instanceof \Exception) {
                return response()->json(GlobalFunction::responseFormat(ReturnCode::ERROR, $data, null));
            }
        }

        $getAccountData = AccountRepository::getDetail($updateUser['data']['id']);
        return response()->json(GlobalFunction::responseFormat(ReturnCode::SUCCESS, $getAccountData, null));
    }

    public static function delete(Request $request): object
    {
        try {
            $validate = Validator::make($request->all(), [
                'id' => 'integer|required',
            ]);

            if ($validate->fails()) {
                return response()->json(GlobalFunction::responseFormat(ReturnCode::VALIDATION_ERROR, $validate->errors()->toArray(), null));
            }
        } catch (\Throwable $th) {
            return response()->json(GlobalFunction::responseFormat(ReturnCode::ERROR, $th, null));
        }

        $data = AccountRepository::delete($request['id']);
        return response()->json(GlobalFunction::responseFormat(ReturnCode::SUCCESS, $data, null));
    }
}
