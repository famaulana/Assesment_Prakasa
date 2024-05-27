<?php

namespace App\Repositories;

use App\Lib\GlobalValue;
use App\Models\User;
use Exception;

/**
 * Our repositories is where the bridge from service to model, please make you eloquent right here.
 */
class UserRepository
{
    public function __construct()
    {
    }

    /**
     * Get all of the registered user.
     * @method getList
     * @return User Returned object from App\Models\User with pagination
     */
    public static function getList(): mixed
    {
        try {
            return User::paginate(GlobalValue::get('pagination'));
        } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * Get all of the registered user.
     * @method getList
     * @return User Returned object from App\Models\User with pagination
     */
    public static function getDetail(int|null $id, string|null $email): mixed
    {
        try {
            if (empty($id)) {
                return User::where('email', $email)->first();
            }

            return User::where('id', $id)->first();
        } catch (Exception $e) {
            return $e;
        }
    }
}
