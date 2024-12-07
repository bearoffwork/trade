<?php

namespace App\Console\Commands;

use App\Database\Models\User;
use BezhanSalleh\FilamentShield\Support\Utils;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand('app:init')]
class AppInit extends Command
{
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '(Re-)Initialize App (Database)';

    public function __invoke(): void
    {
        $cacheUser = User::all();

        $this->call('migrate:fresh');
        $this->call('db:seed', ['--class' => 'ShieldSeeder']);

        if (!app()->isProduction()) {
            $cacheUser->each(function (User $user) {
                User::where('name', $user->name)->update([
                    'password' => $user->password,
                    'remember_token' => $user->remember_token,
                ]);
            });

            User::create([
                'name' => 'test',
                'email' => 'test@localhost',
                'password' => Hash::make('test'),
            ])
                ->assignRole(Utils::getSuperAdminName());

            $this->call('db:seed');
        }

    }
}
