<?php

namespace App\Filament\Widgets;

use App\Models\Talent;
use Filament\Widgets\ChartWidget;

class TalensAge extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        $talents = Talent::query()->get();
        $dataset = [
            'fiveToTen' => 0,
            'elevenToFourteen' => 0,
            'fifteenToSeventeen' => 0,
            'eighteenTotwantefour' => 0,
            'twantefiveTotherteefive' => 0,
            'old' => 0,
        ];
        foreach ($talents as $talent) {
            if ($talent->age >= 5 && $talent->age <= 10) {
                $dataset['fiveToTen'] += 1;
            } elseif ($talent->age >= 11 && $talent->age <= 14) {
                $dataset['elevenToFourteen'] += 1;
            } elseif ($talent->age >= 15 && $talent->age <= 17) {
                $dataset['fifteenToSeventeen'] += 1;
            } elseif ($talent->age >= 18 && $talent->age <= 24) {
                $dataset['eighteenTotwantefour'] += 1;
            } elseif ($talent->age >= 25 && $talent->age <= 35) {
                $dataset['twantefiveTotherteefive'] += 1;
            } elseif ($talent->age > 35) {
                $dataset['old'] += 1;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Talents Age Distributions',
                    'data' => [$dataset['fiveToTen'], $dataset['elevenToFourteen'], $dataset['fifteenToSeventeen'], $dataset['eighteenTotwantefour'], $dataset['twantefiveTotherteefive'], $dataset['old']],
                    'backgroundColor' => [
                        '#77DCBF',
                        '#213555',
                        '#6B6B6B',
                        '#1877F2',
                        '#d3b67f',
                        '#D43030',
                    ],

                ],
            ],
            'labels' => ['5-10', '11-14', '15-17', '18-24', '25-35', 'Older Than 35'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
