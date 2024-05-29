<?php

namespace App\Services;

use Error;
use Exception;
use App\Lib\GlobalFunction;
use App\Lib\ReturnCode;
use App\Repositories\Accounts\AccountRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/**
 * Our service is where the bussiness logic based on the name
 */
class UserService
{
    public function __construct()
    {
    }


    public static function create(string $name, string $email, string $password): array
    {
        try {
            $validate = Validator::make([
                'name' => $name,
                'email' => $email,
                'password' => $password
            ], [
                'name' => 'string|required',
                'email' => 'email|required',
                'password' => 'string|required',
            ]);

            if ($validate->fails()) {
                return GlobalFunction::responseFormat(ReturnCode::VALIDATION_ERROR, $validate->errors()->toArray(), null);
            }
        } catch (\Throwable $th) {
            return GlobalFunction::responseFormat(ReturnCode::ERROR, $th, null);
        }

        $data = UserRepository::create($name, $email, $password);

        if ($data instanceof \Exception) {
            return GlobalFunction::responseFormat(ReturnCode::ERROR, $data, null);
        }

        return GlobalFunction::responseFormat(ReturnCode::SUCCESS, $data, null);
    }

    public static function update(int $id, string|null $name, string|null $email, string|null $password): array
    {
        try {
            $validate = Validator::make([
                'id' => $id,
                'name' => $name,
                'email' => $email,
                'password' => $password
            ], [
                'id' => ['required', Rule::exists('users', 'id')],
                'name' => 'string',
                'email' => 'email',
                'password' => 'string',
            ]);

            if ($validate->fails()) {
                return GlobalFunction::responseFormat(ReturnCode::VALIDATION_ERROR, $validate->errors()->toArray(), null);
            }
        } catch (\Throwable $th) {
            return GlobalFunction::responseFormat(ReturnCode::ERROR, $th, null);
        }

        $data = UserRepository::update($id, $name, $email, $password);

        if ($data instanceof \Exception) {
            return GlobalFunction::responseFormat(ReturnCode::ERROR, $data, null);
        }

        return GlobalFunction::responseFormat(ReturnCode::SUCCESS, $data, null);
    }

    public static function delete(int $id): array
    {
        try {
            $validate = Validator::make(['id' => $id], [
                'id' => ['required', Rule::exists('users', 'id')],
            ]);

            if ($validate->fails()) {
                return GlobalFunction::responseFormat(ReturnCode::VALIDATION_ERROR, $validate->errors()->toArray(), null);
            }
        } catch (\Throwable $th) {
            return GlobalFunction::responseFormat(ReturnCode::ERROR, $th, null);
        }

        $data = UserRepository::delete($id);
        return GlobalFunction::responseFormat(ReturnCode::SUCCESS, $data, null);
    }

    /**
     * Get all of the registered user .
     * @method getUserList
     * @return User Returned object from App\Models\User with pagination.
     */
    public static function getList(): object
    {
        $userList = UserRepository::getList();

        if ($userList instanceof Exception) {
            return response()->json(GlobalFunction::responseFormat(ReturnCode::ERROR, $userList, null));
        }

        return response()->json(GlobalFunction::responseFormat(ReturnCode::SUCCESS, $userList, null));
    }

    /**
     * Get detail data of the registered user.
     * @method getDetail
     * @return User Returned object from App\Models\User.
     */
    public static function getDetail(Request $request): object
    {
        try {
            $validate = Validator::make($request->all(), [
                'id' => ['present_if:email,null|integer', Rule::exists('users', 'id')],
                'email' => ['present_if:id,null|email', Rule::exists('users', 'email')],
            ]);

            if ($validate->fails()) {
                return response()->json(GlobalFunction::responseFormat(ReturnCode::VALIDATION_ERROR, $validate->messages(), null));
            }
        } catch (Exception $e) {
            return response()->json(GlobalFunction::responseFormat(ReturnCode::ERROR, $e, null));
        }

        $userData = UserRepository::getDetail($request['id'], $request['email']);

        if ($userData instanceof Exception) {
            return response()->json(GlobalFunction::responseFormat(ReturnCode::ERROR, $userData, null));
        }

        $getAccountData = AccountRepository::getDetail($userData['id']);

        if (empty($getAccountData)) {
            return response()->json(GlobalFunction::responseFormat(ReturnCode::DATA_NOT_FOUND, $getAccountData, null));
        }

        return response()->json(GlobalFunction::responseFormat(ReturnCode::SUCCESS, $getAccountData, null));
    }
}
