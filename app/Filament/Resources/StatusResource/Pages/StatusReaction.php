<?php

namespace App\Filament\Resources\StatusResource\Pages;

use App\Filament\Resources\StatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use stdClass;

class StatusReaction extends ManageRelatedRecords
{
    protected static string $resource = StatusResource::class;
    protected static string $relationship = 'reactions';
    protected static ?string $navigationIcon = 'heroicon-o-trophy';

    public static function getNavigationLabel(): string
    {
        return 'View Reactions';
    }

    public function getTitle(): string|Htmlable
    {
        return "Manage {$this->getRecordTitle()} Posts";
    }

    public function table(Table $table): Table
    {

        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('index')->state(
                    static function (HasTable $livewire, stdClass $rowLoop): string {
                        return (string)(
                            $rowLoop->iteration +
                            ($livewire->getTableRecordsPerPage() * (
                                    $livewire->getTablePage() - 1
                                ))
                        );
                    }
                ),
                TextColumn::make('name'),
                TextColumn::make('pivot.points')
                    ->formatStateUsing(fn(string $state): string => Str::upper(self::getPointText("{$state}")))
                    ->icon('heroicon-o-trophy')
                    ->iconColor(fn(string $state): string => self::getPointText("{$state}"))->sortable()
            ]);
    }

    public static function getPointText($point)
    {
        $string = '-';
        if ($point == 1) {
            $string = 'bronze';
        }
        if ($point == 2) {
            $string = 'silver';
        }
        if ($point == 3) {
            $string = 'gold';
        }
        return $string;
    }

}
