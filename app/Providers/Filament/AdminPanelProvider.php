<?php

namespace App\Providers\Filament;

use App\Models\TransaksiPenyetoran;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->authGuard('web')
            ->darkMode(false)
            ->colors([
                'primary' => Color::Emerald,
            ])
            ->brandName('Rumah Hijau')
            ->brandLogo(fn () => view('filament.admin.brand'))
            ->brandLogoHeight('2.75rem')

            // Aktifkan ikon lonceng notifikasi (butuh tabel `notifications`):
            //   php artisan notifications:table && php artisan migrate
            // ->databaseNotifications()

            ->discoverResources(
                in: app_path('Filament/Admin/Resources'),
                for: 'App\\Filament\\Admin\\Resources'
            )
            ->discoverPages(
                in: app_path('Filament/Admin/Pages'),
                for: 'App\\Filament\\Admin\\Pages'
            )
            ->discoverWidgets(
                in: app_path('Filament/Admin/Widgets'),
                for: 'App\\Filament\\Admin\\Widgets'
            )

            // CSS tema (sidebar hijau gelap, kartu, dll.)
            ->renderHook(PanelsRenderHook::HEAD_END, fn () => view('filament.admin.theme-styles'))

            // Nama + role di samping avatar (pojok kanan atas)
            ->renderHook(PanelsRenderHook::USER_MENU_BEFORE, fn () => view('filament.admin.user-label'))

            // Kartu "Dampak Bulan Ini" di sidebar
            ->renderHook(PanelsRenderHook::SIDEBAR_NAV_END, function () {
                // Asumsi: 1 kg sampah didaur ulang ≈ 0,5 kg CO2e dihindari. Ganti sesuai data kamu.
                $kg = Cache::remember(
                    'sidebar-co2-' . now()->format('Ym'),
                    300,
                    fn () => (float) TransaksiPenyetoran::whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year)
                        ->sum('total_berat') * 0.5
                );

                $co2 = $kg >= 1000
                    ? number_format($kg / 1000, 1, ',', '.') . ' ton'
                    : number_format($kg, 0, ',', '.') . ' kg';

                return view('filament.admin.sidebar-impact', ['co2' => $co2]);
            })

            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}