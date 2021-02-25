<?php

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'id'    => 1,
                'title' => 'user_management_access',
            ],
            [
                'id'    => 2,
                'title' => 'permission_create',
            ],
            [
                'id'    => 3,
                'title' => 'permission_edit',
            ],
            [
                'id'    => 4,
                'title' => 'permission_show',
            ],
            [
                'id'    => 5,
                'title' => 'permission_delete',
            ],
            [
                'id'    => 6,
                'title' => 'permission_access',
            ],
            [
                'id'    => 7,
                'title' => 'role_create',
            ],
            [
                'id'    => 8,
                'title' => 'role_edit',
            ],
            [
                'id'    => 9,
                'title' => 'role_show',
            ],
            [
                'id'    => 10,
                'title' => 'role_delete',
            ],
            [
                'id'    => 11,
                'title' => 'role_access',
            ],
            [
                'id'    => 12,
                'title' => 'user_create',
            ],
            [
                'id'    => 13,
                'title' => 'user_edit',
            ],
            [
                'id'    => 14,
                'title' => 'user_show',
            ],
            [
                'id'    => 15,
                'title' => 'user_delete',
            ],
            [
                'id'    => 16,
                'title' => 'user_access',
            ],
            [
                'id'    => 17,
                'title' => 'master_access',
            ],
            [
                'id'    => 18,
                'title' => 'warga_binaan_create',
            ],
            [
                'id'    => 19,
                'title' => 'warga_binaan_edit',
            ],
            [
                'id'    => 20,
                'title' => 'warga_binaan_show',
            ],
            [
                'id'    => 21,
                'title' => 'warga_binaan_delete',
            ],
            [
                'id'    => 22,
                'title' => 'warga_binaan_access',
            ],
            [
                'id'    => 23,
                'title' => 'seller_create',
            ],
            [
                'id'    => 24,
                'title' => 'seller_edit',
            ],
            [
                'id'    => 25,
                'title' => 'seller_show',
            ],
            [
                'id'    => 26,
                'title' => 'seller_delete',
            ],
            [
                'id'    => 27,
                'title' => 'seller_access',
            ],
            [
                'id'    => 28,
                'title' => 'produk_create',
            ],
            [
                'id'    => 29,
                'title' => 'produk_edit',
            ],
            [
                'id'    => 30,
                'title' => 'produk_show',
            ],
            [
                'id'    => 31,
                'title' => 'produk_delete',
            ],
            [
                'id'    => 32,
                'title' => 'produk_access',
            ],
            [
                'id'    => 33,
                'title' => 'profile_password_edit',
            ],
        ];

        Permission::insert($permissions);
    }
}
