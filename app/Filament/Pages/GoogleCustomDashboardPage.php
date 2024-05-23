<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use BezhanSalleh\FilamentGoogleAnalytics\Widgets;

class GoogleCustomDashboardPage extends Page
{
    protected static ?string $navigationIcon = 'icon-analytic';
    protected static ?string $navigationLabel = 'Google Analytic Dashboard';
    protected static string $view = 'filament.pages.google-custom-dashboard-page';

    protected function getHeaderWidgets(): array
    {
//        dd(storage_path('app/analytics/service-account-credentials.json'));
        return [
            Widgets\PageViewsWidget::class,
            Widgets\VisitorsWidget::class,
            Widgets\ActiveUsersOneDayWidget::class,
            Widgets\ActiveUsersSevenDayWidget::class,
            Widgets\ActiveUsersTwentyEightDayWidget::class,
            Widgets\SessionsWidget::class,
            Widgets\SessionsDurationWidget::class,
            Widgets\SessionsByCountryWidget::class,
            Widgets\SessionsByDeviceWidget::class,
            Widgets\MostVisitedPagesWidget::class,
            Widgets\TopReferrersListWidget::class,
        ];
    }
}
