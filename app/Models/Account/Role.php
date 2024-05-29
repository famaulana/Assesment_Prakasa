<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
    ];

    /**
     * Type enumeration for Account
     * 
     * @var string TYPE_SUPERADMIN
     * @var string TYPE_MANAGERIAL
     * @var string TYPE_STAFF
     * @var string TYPE_OUTSOURCE
     */
    public const TYPE_SUPERADMIN = 0;
    public const TYPE_MANAGERIAL = 1;
    public const TYPE_STAFF = 2;
    public const TYPE_OUTSOURCE = 3;

    public static function getType(string $type = null): mixed
    {
        $typeArr = [
            self::TYPE_SUPERADMIN => 'superadmin',
            self::TYPE_MANAGERIAL => 'managerial',
            self::TYPE_STAFF => 'staff',
            self::TYPE_OUTSOURCE => 'outsource',
        ];

        if (!isset($type)) {
            return $typeArr;
        }

        return in_array($type, $typeArr) ? intval(array_keys($typeArr, $type)[0]) : null;
    }
}
