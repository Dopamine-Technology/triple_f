<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class UserByGender extends ChartWidget
{
    protected static ?string $heading = 'User Genders';
    private $gender_colors = ['male' => '#213555', 'female' => '#77dcbf', 'other' => '#fdb83e'];

    protected function getData(): array
    {
        $users_gender = DB::table('users')->selectRaw('gender, count(id) as gender_count')->where('is_admin', false)->groupBy('gender')->get();
        $dataset = [];
        foreach ($users_gender as $one) {
            $dataset[] = [
                'label' => Str::camel($one->gender),
                'data' => [$one->gender_count],
                'backgroundColor' => $this->gender_colors[$one->gender],
                'borderColor' => $this->gender_colors[$one->gender],
            ];
        }
        return [
            'datasets' => $dataset,
            'labels' => ['Genders']
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
