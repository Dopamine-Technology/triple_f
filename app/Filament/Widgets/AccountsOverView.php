<?php

namespace App\Filament\Widgets;

use App\Models\Club;
use App\Models\Coach;
use App\Models\Scout;
use App\Models\Talent;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AccountsOverView extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Clubs', Club::query()->count())
                ->extraAttributes([
                    'style' => 'background-image:url(https://triplef.dopaminetechnology.com/static/media/Artboard.62177a70.svg)',
                ])
                ->icon('fas-people-group')
                ->color('info'),
            Stat::make('Coaches', Coach::query()->count())
                ->icon('fas-hands-asl-interpreting')
                ->extraAttributes([
                    'style' => 'background-image:url(https://triplef.dopaminetechnology.com/static/media/Artboard.62177a70.svg)',
                ])
                ->color('info'),
            Stat::make('Talents', Talent::query()->count())
                ->icon('fas-futbol')
                ->extraAttributes([
                    'style' => 'background-image:url(https://triplef.dopaminetechnology.com/static/media/Artboard.62177a70.svg)',
                ])
                ->color('info'),
            Stat::make('Scouts', Scout::query()->count())
                ->icon('fas-compass')
                ->extraAttributes([
                    'style' => 'background-image:url(https://triplef.dopaminetechnology.com/static/media/Artboard.62177a70.svg)',
                ])
                ->color('info'),
        ];
    }
}
