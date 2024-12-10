<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use BezhanSalleh\FilamentShield\Support\Utils;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[{"name":"\u7ba1\u7406\u54e1","guard_name":"web","permissions":["view_fund","view_any_fund","create_fund","update_fund","restore_fund","restore_any_fund","replicate_fund","reorder_fund","delete_fund","delete_any_fund","force_delete_fund","force_delete_any_fund","view_item","view_any_item","create_item","update_item","restore_item","restore_any_item","replicate_item","reorder_item","delete_item","delete_any_item","force_delete_item","force_delete_any_item","view_role","view_any_role","create_role","update_role","delete_role","delete_any_role","view_user","view_any_user","create_user","update_user","restore_user","restore_any_user","replicate_user","reorder_user","delete_user","delete_any_user","force_delete_user","force_delete_any_user","view_all_wallet","view_wallet","view_any_wallet","create_wallet","update_wallet","restore_wallet","restore_any_wallet","replicate_wallet","reorder_wallet","delete_wallet","delete_any_wallet","force_delete_wallet","force_delete_any_wallet","page_Withdraw"]},{"name":"\u6703\u54e1","guard_name":"web","permissions":["view_any_fund","view_item","view_any_item","view_wallet","view_any_wallet"]}]';
        $directPermissions = '{"30":{"name":"view_wallet::record","guard_name":"web"},"31":{"name":"view_any_wallet::record","guard_name":"web"},"32":{"name":"create_wallet::record","guard_name":"web"},"33":{"name":"update_wallet::record","guard_name":"web"},"34":{"name":"restore_wallet::record","guard_name":"web"},"35":{"name":"restore_any_wallet::record","guard_name":"web"},"36":{"name":"replicate_wallet::record","guard_name":"web"},"37":{"name":"reorder_wallet::record","guard_name":"web"},"38":{"name":"delete_wallet::record","guard_name":"web"},"39":{"name":"delete_any_wallet::record","guard_name":"web"},"40":{"name":"force_delete_wallet::record","guard_name":"web"},"41":{"name":"force_delete_any_wallet::record","guard_name":"web"},"42":{"name":"view_all_wallet::record","guard_name":"web"}}';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            /** @var Model $roleModel */
            $roleModel = Utils::getRoleModel();
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = $roleModel::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name'],
                ]);

                if (! blank($rolePlusPermission['permissions'])) {
                    $permissionModels = collect($rolePlusPermission['permissions'])
                        ->map(fn ($permission) => $permissionModel::firstOrCreate([
                            'name' => $permission,
                            'guard_name' => $rolePlusPermission['guard_name'],
                        ]))
                        ->all();

                    $role->syncPermissions($permissionModels);
                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (! blank($permissions = json_decode($directPermissions, true))) {
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($permissions as $permission) {
                if ($permissionModel::whereName($permission)->doesntExist()) {
                    $permissionModel::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }
}
