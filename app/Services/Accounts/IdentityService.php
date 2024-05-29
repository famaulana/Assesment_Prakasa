<?php

namespace App\Services\Accounts;

use Error;
use Exception;
use App\Lib\GlobalFunction;
use App\Lib\ReturnCode;
use App\Repositories\Accounts\AccountRepository;
use App\Repositories\Accounts\IdentityRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/**
 * Our service is where the bussiness logic based on the name
 */
class IdentityService
{
    public function __construct()
    {
    }

    public static function create(Request $request): mixed
    {
        $getAccount = AccountRepository::getDetail(Auth::user()->id, null);

        if (empty($getAccount)) {
            return response()->json(GlobalFunction::responseFormat(ReturnCode::DATA_NOT_FOUND, [], null));
        }

        $request = $request->all();
        $request['account_id'] = $getAccount['id'];

        try {
            $validate = Validator::make($request, [
                'account_id' => ['required', Rule::exists('accounts', 'id')],
                'first_name' => 'string|required',
                'middle_name' => 'string|required',
                'last_name' => 'string|required',
                'phone' => 'numeric|required',
                'gender' => 'integer|required',
                'profile_img' => 'image|max:50000',
                'address' => 'string|required',
            ]);

            if ($validate->fails()) {
                return response()->json(GlobalFunction::responseFormat(ReturnCode::VALIDATION_ERROR, $validate->errors()->toArray(), null));
            }
        } catch (\Throwable $th) {
            return response()->json(GlobalFunction::responseFormat(ReturnCode::ERROR, $th, null));
        }

        $data = IdentityRepository::create($request['first_name'], $request['middle_name'], $request['last_name'], $request['phone'], $request['gender'], $request['profile_img'], $request['address']);

        if ($data instanceof \Exception) {
            return GlobalFunction::responseFormat(ReturnCode::ERROR, $data, null);
        }

        $bindIdentity = AccountService::attachIdentityToAccount($request['account_id'], $data->id);

        if ($bindIdentity['code'] !== ReturnCode::SUCCESS->code()) {
            return response()->json($bindIdentity);
        }

        $getAccountData = AccountRepository::getDetail(Auth::user()->id);

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

        $data = IdentityRepository::delete($request['id']);
        return response()->json(GlobalFunction::responseFormat(ReturnCode::SUCCESS, $data, null));
    }
}
