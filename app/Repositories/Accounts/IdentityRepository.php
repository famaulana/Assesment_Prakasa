<?php

namespace App\Repositories\Accounts;

use App\Lib\GlobalValue;
use App\Models\Account\Identity;
use App\Models\User;
use Exception;

/**
 * Our repositories is where the bridge from service to model, please make you eloquent right here.
 */
class IdentityRepository
{
    public function __construct()
    {
    }

    /**
     * Create Identity.
     * @method create
     * @return Identity Returned object from App\Models\Account\Identity
     */
    public static function create(
        string $first_name,
        string $middle_name,
        string $last_name,
        string $phone,
        int $gender,
        string $profile_img,
        string $address,
    ): mixed {
        try {
            return Identity::create([
                'first_name' => $first_name,
                'middle_name' => $middle_name,
                'last_name' => $last_name,
                'phone' => $phone,
                'gender' => $gender,
                'profile_img' => $profile_img,
                'address' => $address
            ]);
        } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * Update Identity.
     * @method update
     * @return Identity Returned object from App\Models\Account\Identity
     */
    public static function update(
        int $id,
        string $first_name,
        string $middle_name,
        string $last_name,
        string $phone,
        int $gender,
        string $profile_img,
        string $address,
    ): mixed {
        try {
            $data = Identity::where('id', $id)->firstOrFails();

            $data->first_name = $first_name;
            $data->middle_name = $middle_name;
            $data->last_name = $last_name;
            $data->phone = $phone;
            $data->gender = $gender;
            $data->profile_img = $profile_img;
            $data->address = $address;

            $$data->save();

            return $data;
        } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * Delete Identity.
     * @method delete
     * @return Identity Returned object from App\Models\Account\Identity
     */
    public static function delete(int $id): mixed
    {
        try {
            return Identity::where('id', $id)->delete();
        } catch (Exception $e) {
            return $e;
        }
    }
}
