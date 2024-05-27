<?php

namespace App\Services;

use Error;
use Exception;
use App\Lib\GlobalFunction;
use App\Lib\ReturnCode;
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

        return response()->json(GlobalFunction::responseFormat(ReturnCode::SUCCESS, $userData, null));
    }
}
