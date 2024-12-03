<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
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

        $cacheUser->each(function (User $user) {
            User::insert($user
                ->setHidden([])
                ->attributesToArray());
        });
    }
}
