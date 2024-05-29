<?php

namespace App\Models\Account;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'role_id',
        'identity_id',
        'status'
    ];

    protected $hidden = [];

    /**
     * Status enumeration for Account
     * 
     * @var string STATUS_ACTIVE
     * @var string STATUS_INACTIVE
     * @var string STATUS_SUSPENDED
     */
    public const STATUS_INACTIVE = 0;
    public const STATUS_ACTIVE = 1;
    public const STATUS_SUSPENDED = 2;

    public static function getStatus(string $status = null): mixed
    {
        $statusArr = [
            self::STATUS_INACTIVE => 'inactive',
            self::STATUS_ACTIVE => 'active',
            self::STATUS_SUSPENDED => 'suspend',
        ];

        if (!isset($status)) {
            return $statusArr;
        }

        return in_array($status, $statusArr) ? intval(array_keys($statusArr, $status)[0]) : null;
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function role(): HasOne
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    public function identity(): HasOne
    {
        return $this->hasOne(Identity::class, 'id', 'identity_id');
    }

    public static function getDetailAccount(int $id)
    {
        return self::with('identity')
            ->join('users', 'users.id', '=', 'accounts.user_id')
            ->join('roles', 'roles.id', '=', 'accounts.role_id')
            ->where('users.id', $id)
            ->select(['accounts.id as id', 'users.name as username', 'users.email as email', 'roles.name as role', 'accounts.created_at as created_at', 'accounts.updated_at as updated_at', 'accounts.*'])->first();
    }
}
