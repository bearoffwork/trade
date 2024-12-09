<?php

namespace Database\Seeders;

use App\Database\Models\User;
use BezhanSalleh\FilamentShield\Support\Utils;
use Illuminate\Database\Seeder;

class UserPermSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(ShieldSeeder::class);
        $user = User::first();
        $user->assignRole(Utils::getSuperAdminName());

        User::all()
            ->each(function (User $user) {
                $user->assignRole(Utils::getPanelUserRoleName());
            });
    }
}
