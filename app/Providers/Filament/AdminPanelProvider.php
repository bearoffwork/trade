<?php

namespace App\Providers\Filament;

use App\Panel\Pages\Login;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Carbon\Carbon;
use Exception;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider as FilamentPanelProvider;
use Filament\Support\Assets\Js;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentAsset;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Vite;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends FilamentPanelProvider
{
    /**
     * @throws Exception
     */
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('a')
            ->login(Login::class)
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Panel/Resources'), for: 'App\\Panel\\Resources')
            ->discoverPages(in: app_path('Panel/Pages'), for: 'App\\Panel\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Panel/Widgets'), for: 'App\\Panel\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make(),
            ]);
    }

    public function boot(): void
    {
        FilamentAsset::register([
            Js::make('paste-ocr', Vite::asset('resources/js/paste-ocr.js'))->module(),
        ]);

        Select::configureUsing(function (Select $select) {
            $select
                ->native(false);
        });

        DateTimePicker::configureUsing(function (DateTimePicker $picker) {
            $picker
                ->native(false)
                ->displayFormat('Y-m-d H:i:s')
                ->hint(fn($state) => Carbon::parse($state)->diffForHumans());
        });

        DatePicker::configureUsing(function (DateTimePicker $picker) {
            $picker
                ->displayFormat('Y-m-d');
        });

        TextInput::macro('number', function () {
            return $this
                ->numeric()
                ->extraInputAttributes(['style' => 'text-align:right']);
        });
        TextInput::macro('percentage', function () {
            return $this
                ->number()
                ->formatStateUsing(fn($state) => bcmul($state, '100'))
                ->dehydrateStateUsing(fn($state) => bcdiv($state, '100', 4))
                ->suffix('%');
        });
    }
}
