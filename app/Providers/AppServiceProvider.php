<?php

namespace App\Providers;

use App\Database\PatchedSQLiteGrammar;
use App\Services\MoneyService;
use BezhanSalleh\FilamentShield\Facades\FilamentShield;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class AppServiceProvider extends ServiceProvider
{
    public $singletons = [
        MoneyService::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        // DB::listen(function ($query) {
        //     // Log the SQL query, bindings, and execution time
        //     info('SQL Query Executed', [
        //         'sql' => $query->sql,
        //         'bindings' => $query->bindings,
        //         'time' => $query->time, // Time in milliseconds
        //     ]);
        // });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();
        FilamentShield::prohibitDestructiveCommands($this->app->isProduction());

        // * @throws NotFoundExceptionInterface|ContainerExceptionInterface if failed to get 'db.connection'
        // support check constraint
        // $this->app->get('db.connection')->setSchemaGrammar(new PatchedSQLiteGrammar);
    }
}
