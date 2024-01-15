<?php

namespace App\Filament\Resources\StatusResource\Pages;

use App\Filament\Resources\StatusResource;
use App\Models\ReportStatus;
use App\Models\Status;


use Filament\Forms\Components\Textarea;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;
use stdClass;

class StatusReports extends ManageRelatedRecords
{
    protected static string $resource = StatusResource::class;
    protected static string $relationship = 'reports';
    protected static ?string $navigationIcon = 'heroicon-o-face-frown';

    public static function getNavigationBadge(): ?string
    {

        return ReportStatus::query()->where('status_id', request()->record)->where('is_reviewed', 0)->count();
    }


    public static function getNavigationLabel(): string
    {
        return 'View Reports';
    }

    public function getTitle(): string|Htmlable
    {
        return "Manage {$this->getRecordTitle()} Reports";
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
                TextColumn::make('name')->label('User Name'),
                TextColumn::make('report')->label('Report'),
                ToggleColumn::make('is_reviewed')->label('Is Reviewed')->sortable(),


//                TextColumn::make('pivot.points')
//                    ->formatStateUsing(fn(string $state): string => Str::upper(self::getPointText("{$state}")))
//                    ->icon('heroicon-o-trophy')
//                    ->iconColor(fn(string $state): string => self::getPointText("{$state}"))->sortable()
            ])->defaultSort('is_reviewed', 'ASC')->actions([
                Action::make('delete')
                    ->action(fn (ReportStatus $record) => dd($record->report))
//                    ->requiresConfirmation()
            ]);
    }
}
