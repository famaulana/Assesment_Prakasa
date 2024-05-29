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
     * Create User.
     * @method create
     * @return Account Returned object from App\Models\Useer
     */
    public static function create(
        string $name,
        string $email,
        string $password
    ): mixed {
        try {
            return User::create([
                'name' => $name,
                'email' => $email,
                'password' => $password
            ]);
        } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * Update User.
     * @method update
     * @return Account Returned object from App\Models\Useer
     */
    public static function update(
        int $id,
        string|null $name,
        string|null $email,
        string|null $password
    ): mixed {
        try {
            $data = User::where('id', $id)->first();

            if (!empty($name)) {
                $data->name = $name;
            }
            if (!empty($email)) {
                $data->email = $email;
            }
            if (!empty($password)) {
                $data->password = $password;
            }
            $data->save();

            return $data;
        } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * Delete User.
     * @method delete
     * @return User Returned object from App\Models\User
     */
    public static function delete(int $id): mixed
    {
        try {
            return User::where('id', $id)->delete();
        } catch (Exception $e) {
            return $e;
        }
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
