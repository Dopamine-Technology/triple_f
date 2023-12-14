<?php

namespace App\Filament\Resources\SportResource\RelationManagers;

use App\Models\Position;
use App\Models\Sport;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PositionsRelationManager extends RelationManager
{
    protected static string $relationship = 'positions';

    public function form(Form $form): Form
    {
        $positions_list = array();
        $positions_list[0] = 'parent Category';
        $positions_list = array_merge($positions_list, Position::query()->where('parent_id', 0)->pluck('name', 'id')->toArray());


        return $form
            ->schema([
                Section::make()->schema([
                    Group::make()->schema([
                        TextInput::make('name.ar')->label('Arabic Title')->required(),
                        TextInput::make('name.en')->label('English Title')->required(),
                        TextInput::make('code')->label('Shortcut Code')->required(),
                        Select::make('parent_id')->options($positions_list)->required()
                    ])->columns(2),
                ])

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('id')->color('primary'),
                TextColumn::make('name')->searchable(query: function (Builder $query, string $search): Builder {
                    return $query
                        ->where('name->en', 'like', "%{$search}%");
                }),
                TextColumn::make('code')->label('Shortcut Code')->searchable()->badge(),
                TextColumn::make('parent.name')->color('primary')->default('Parent Category'),
            ])
            ->filters([
                TernaryFilter::make('is_parent')
                    ->label('Parent & Sub')
                    ->placeholder('All Positions')
                    ->trueLabel('Sub Positions')
                    ->falseLabel('Parent Positions')
                    ->queries(
                        true: fn(Builder $query) => $query->where('parent_id', '!=', 0),
                        false: fn(Builder $query) => $query->where('parent_id', 0),
                        blank: fn(Builder $query) => $query, // In this example, we do not want to filter the query when it is blank.
                    ),
                SelectFilter::make('parent_id')->label('Filter By Parent')
                    ->options(Position::query()->where('parent_id', 0)->pluck('name', 'id')->toArray()),

            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
}
