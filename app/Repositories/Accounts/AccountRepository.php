<?php

namespace App\Repositories\Accounts;

use App\Lib\GlobalValue;
use App\Models\Account\Account;
use App\Models\User;
use Exception;

/**
 * Our repositories is where the bridge from service to model, please make you eloquent right here.
 */
class AccountRepository
{
    public function __construct()
    {
    }

    /**
     * Get detail with account.
     * @param $id Fill the id with user id from App\Models\User
     * @method getDetail
     * @return Account Returned object from App\Models\Account\Account
     */
    public static function getDetail(
        int $id
    ): mixed {
        try {
            return Account::getDetailAccount($id);
        } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * Bind identity with account.
     * @method bindIdentity
     * @return Account Returned object from App\Models\Account\Account
     */
    public static function bindIdentity(
        int $id,
        int $identity_id,
    ): mixed {
        try {
            $data = Account::where('id', $id)->firstOrFail();
            $data->identity_id = $identity_id;
            $data->save();

            return $data;
        } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * Create account.
     * @method create
     * @return Account Returned object from App\Models\Account\Account
     */
    public static function create(
        string $user_id,
        int $role_id,
        int $status
    ): mixed {
        try {
            return Account::create([
                'user_id' => $user_id,
                'role_id' => $role_id,
                'status' => $status
            ]);
        } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * Update account.
     * @method update
     * @return Account Returned object from App\Models\Account\Account
     */
    public static function update(
        int $id,
        int $role_id
    ): mixed {
        try {
            $data = Account::where('id', $id)->firstOrFail();

            $data->role_id = $role_id;

            $data->save();

            return $data;
        } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * Delete account.
     * @method delete
     * @return Account Returned object from App\Models\Account\Account
     */
    public static function delete(int $id): mixed
    {
        try {
            return Account::where('id', $id)->delete();
        } catch (Exception $e) {
            return $e;
        }
    }
}
