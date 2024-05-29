<?php

namespace Database\Seeders;

use App\Models\Account\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = [
            'superadmin' => [
                'IT'
            ],
            'managerial' => [
                'Human Resource', 'CEO', 'COO', 'CTO'
            ],
            'staff' => [
                'Admin', 'Koordinator'
            ],
            'outsource' => [
                'Helpdesk'
            ],
        ];

        foreach ($role as $type => $item) {
            foreach ($item as $name) {
                Role::create([
                    'name' => $name,
                    'type' => Role::getType($type)
                ]);
            }
        }
    }
}
