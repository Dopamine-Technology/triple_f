<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OpportunityResource\Pages;
use App\Filament\Resources\OpportunityResource\RelationManagers;
use App\Models\Opportunity;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use stdClass;

class OpportunityResource extends Resource
{
    protected static ?string $model = Opportunity::class;

    protected static ?string $navigationIcon = 'icon-opportunity';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->disabledClick(),
                TextColumn::make('requirements')->disabledClick(),
                TextColumn::make('additional_info')->disabledClick(),
                TextColumn::make('status')->badge()->disabledClick(),
                TextColumn::make('from_age')->state(
                    static function (Opportunity $record): string {
                        return ($record->from_age != 0 ? $record->from_age : '') . ' - ' . ($record->to_age != 0 ? $record->to_age : '');
                    }
                )->label('Age')->disabledClick(),
                TextColumn::make('height')->state(
                    static function (Opportunity $record): string {
                        return ($record->from_height != 0 ? $record->from_height . ' cm ' : '') . ' - ' . ($record->to_height != 0 ? $record->to_height . ' cm ' : '');
                    }
                )->label('Height')->disabledClick(),
                TextColumn::make('weight')->state(
                    static function (Opportunity $record): string {
                        return ($record->from_weight != 0 ? $record->from_weight . ' kg ' : '') . ' - ' . ($record->to_weight != 0 ? $record->to_weight . ' kg ' : '');
                    }
                )->label('Weight')->disabledClick(),
            ])
            ->filters([
                //
            ])
            ->actions([
//                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
//                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
//                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOpportunities::route('/'),
            'create' => Pages\CreateOpportunity::route('/create'),
            'edit' => Pages\EditOpportunity::route('/{record}/edit'),
        ];
    }
    public static function canCreate(): bool
    {
        return false;
    }
}
