<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserTypesResource\Pages;
use App\Filament\Resources\UserTypesResource\RelationManagers;
use App\Models\UserType;
use App\Models\UserTypes;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class UserTypesResource extends Resource
{
    protected static ?string $model = UserType::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    private static $permisions_options = [
        'view_talent' => 'View Talent',
        'view_scout' => 'View Scout',
        'view_club' => 'View Club',
        'react_to_video' => 'React To Video',
    ];
    private static $permisions_descriptions = [
        'view_talent' => 'View Talent Profiles , View Talent Reels And content',
        'view_scout' => 'View Scout Profiles and mke scout profiles appear in user search result',
        'view_club' => 'View Clubs , Clubs Profiles , Show Club Certificates and information',

    ];

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    Repeater::make('permissions')
                        ->schema([
                            Toggle::make('value')->label('Enable')
                                ->onColor('success')
                                ->offColor('danger')
                        ])
                        ->itemLabel(fn(array $state): ?string => $state['name'] ? Str::upper(str_replace('_', ' ', $state['name'])) : null)
                        ->addable(false)
                        ->deletable(false)
                        ->orderable(false)
                        ->columns(2)
                        ->grid(2)


                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('name'),
                TextColumn::make('selected_permissions')->badge(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUserTypes::route('/'),
            'create' => Pages\CreateUserTypes::route('/create'),
            'edit' => Pages\EditUserTypes::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
