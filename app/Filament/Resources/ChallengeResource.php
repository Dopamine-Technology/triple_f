<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChallengeResource\Pages;
use App\Filament\Resources\ChallengeResource\RelationManagers;
use App\Models\Challenge;
use App\Models\Position;
use App\Models\Sport;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ChallengeResource extends Resource
{
    protected static ?string $model = Challenge::class;

    protected static ?string $navigationIcon = 'icon-trophy';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    Group::make()->schema([
                        FileUpload::make('image'),
                    ])->columns(1),
                    Group::make()->schema([
                        TextInput::make('name.ar')->label('Arabic Title')->required(),
                        TextInput::make('name.en')->label('English Title')->required(),
                    ])->columns(2),
                    Group::make()->schema([
                        RichEditor::make('description.en'),
                        RichEditor::make('description.ar'),
                    ])->columns(2),
                    Group::make()->schema([
                        Select::make('sport_id')->options(Sport::query()->pluck('name', 'id')->toArray()),
                    ])->columns(1),
                    Grid::make()->schema([
                        Select::make('positions')
                            ->options(Position::query()->where('parent_id' , 0)->pluck('name', 'id')->toArray())
                            ->searchable()->multiple(),
                    ])->columns(1),
                ]),
                Section::make('What Should I do')->schema([
                    Repeater::make('tips')->label('Challenge Tips List')
                        ->schema([
                            TextInput::make('en')->label('Tips in english')->required(),
                            TextInput::make('ar')->label('Tips in arabic')->required(),
                        ])
                        ->columns(2)->required()
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                ImageColumn::make('image')->default(asset('logo.svg')),
                TextColumn::make('name'),
                TextColumn::make('sport.name')->default('-'),
                TextColumn::make('positions')
                    ->formatStateUsing(fn(string $state): string => Position::query()->find($state)->name)
                    ->badge(),
                TextColumn::make('created_at')->default('-'),
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
            'index' => Pages\ListChallenges::route('/'),
            'create' => Pages\CreateChallenge::route('/create'),
            'edit' => Pages\EditChallenge::route('/{record}/edit'),
        ];
    }
}
