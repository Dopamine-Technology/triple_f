<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StatusResource\Pages;
use App\Filament\Resources\StatusResource\RelationManagers;
use App\Infolists\Components\StatusVideo;
use App\Models\Status;
use Filament\Forms;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Pages\SubNavigationPosition;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\Pages\Page;
use Filament\Infolists\Infolist;

class StatusResource extends Resource
{
    protected static ?string $model = Status::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Toggle::make('approved'),
                    Forms\Components\Grid::make()->schema([
                        Forms\Components\TextInput::make('title'),
                        Forms\Components\Textarea::make('description')->label('Text')->rows(4),
                    ])->columns(2),
                    Forms\Components\Grid::make()->schema([
                        Forms\Components\FileUpload::make('image'),
                        Forms\Components\FileUpload::make('video'),
                    ])->columns(2),
                ])
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('User Info')
                    ->schema([
                        Grid::make()
                            ->schema([
                                Fieldset::make('Statistics')
                                    ->schema([
                                        TextEntry::make('shares')->icon('heroicon-o-share')->hiddenLabel(),
                                        TextEntry::make('saves')->icon('heroicon-o-bookmark')->hiddenLabel(),
                                        TextEntry::make('gold_reacts')->icon('heroicon-o-trophy')->hiddenLabel()->iconColor('gold'),
                                        TextEntry::make('silver_reacts')->icon('heroicon-o-trophy')->hiddenLabel()->iconColor('silver'),
                                        TextEntry::make('bronze_reacts')->icon('heroicon-o-trophy')->hiddenLabel()->iconColor('bronze'),
                                    ])->columns(5),
                            ])->columns(1),
                        Grid::make()
                            ->schema([
                                TextEntry::make('user.name')->label('name'),
                                TextEntry::make('user.profile_type.name')->label('profile type')->color('primary'),
                                ImageEntry::make('user.profile.image')->default(asset('logo.svg'))->circular()->hiddenLabel()
                                    ->grow(true)
                            ])->columns(3),
                    ]),
                Section::make('Post')
                    ->schema([
                        Grid::make()
                            ->schema([
                                TextEntry::make('title')->label('post Title')->default('-'),
                                TextEntry::make('description'),
                            ])->columns(2),
                        Grid::make()
                            ->schema([
                                ImageEntry::make('image')->default(asset('logo.svg')),
                                StatusVideo::make('video'),
                            ])->columns(1),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->default('-')->searchable()->sortable(),
                TextColumn::make('challenge.name')->default('-')->color('primary')->searchable()->sortable(),
                TextColumn::make('description'),
                TextColumn::make('user.name')->label('owner')->searchable()->sortable(),
                TextColumn::make('shares')->label('Shares')->default(0)->toggleable(isToggledHiddenByDefault: true)->sortable(),
                TextColumn::make('saves')->label('Saves')->default(0)->toggleable(isToggledHiddenByDefault: true)->sortable(),
                TextColumn::make('gold_reacts')->label('Gold Reacts')->default(0)->iconColor('gold')->icon('heroicon-o-trophy')->toggleable(isToggledHiddenByDefault: true)->sortable(),
                TextColumn::make('silver_reacts')->label('Silver Reacts')->default(0)->iconColor('silver')->icon('heroicon-o-trophy')->toggleable(isToggledHiddenByDefault: true)->sortable(),
                TextColumn::make('bronze_reacts')->label('Bronze Reacts')->default(0)->iconColor('bronze')->icon('heroicon-o-trophy')->toggleable(isToggledHiddenByDefault: true)->sortable(),
                TextColumn::make('created_at')->label('Published Date')->date()->sortable(),
                ToggleColumn::make('approved'),
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

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewStatus::class,
            Pages\EditStatus::class,
            Pages\StatusReaction::class,
            Pages\StatusReports::class,
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
            'index' => Pages\ListStatuses::route('/'),
            'create' => Pages\CreateStatus::route('/create'),
            'edit' => Pages\EditStatus::route('/{record}/edit'),
            'view' => Pages\ViewStatus::route('/{record}/view'),
            'reactions' => Pages\StatusReaction::route('/{record}/reactions'),
            'reports' => Pages\StatusReports::route('/{record}/reports'),
        ];
    }
}
