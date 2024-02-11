<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClubResource\Pages;
use App\Filament\Resources\ClubResource\RelationManagers;
use App\Models\Club;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ClubResource extends Resource
{
    protected static ?string $model = Club::class;

    protected static ?string $navigationIcon = 'icon-club';
    protected static ?int $navigationSort = 1;
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Club Details')->schema([
                    ImageEntry::make('logo')->height(60)
                        ->circular()->label(''),
                    TextEntry::make('name')->label('Club Name')->weight('bold'),
                    TextEntry::make('year_founded')->color('primary')->weight('bold'),
                    TextEntry::make('sport.name')->icon('heroicon-o-sparkles')->iconColor('primary'),
                    TextEntry::make('country.name')->icon('heroicon-o-globe-europe-africa')->iconColor('primary'),
                    TextEntry::make('mobile_number')->icon('heroicon-o-phone')->iconColor('primary')->copyable(),
                    TextEntry::make('is_authorized')->size(TextEntry\TextEntrySize::Large)->weight('bold')->badge()->color(fn(string $state): string => match ($state) {
                        '1' => 'primary',
                        '0' => 'danger',
                    })->icon(fn(string $state): string => match ($state) {
                        '1' => 'heroicon-o-check-circle',
                        '0' => 'heroicon-o-x-circle',
                    })->formatStateUsing(fn(string $state): string => match ($state) {
                        '1' => 'Authorized',
                        '0' => 'Unauthorized',
                    }),
                    TextEntry::make('approved_by_admin')->size(TextEntry\TextEntrySize::Large)->weight('bold')->badge()->color(fn(string $state): string => match ($state) {
                        '1' => 'primary',
                        '0', '' => 'danger'
                    })->icon(fn(string $state): string => match ($state) {
                        '1' => 'heroicon-o-check-circle',
                        '0', '' => 'heroicon-o-x-circle'
                    })->formatStateUsing(fn(string $state): string => match ($state) {
                        '1' => 'Approved',
                        '0', '' => 'Not Approved'
                    }),
                ])->columns(3),
                Section::make('Club User Details')->schema([
                    TextEntry::make('user.name')->label('User Name')->weight('bold'),
                    TextEntry::make('user.email')->label('Email')->icon('heroicon-o-envelope')->iconColor('primary')->copyable(),
                    ImageEntry::make('image')->height(60),
                    TextEntry::make('user.is_blocked')->label('User Status')->size(TextEntry\TextEntrySize::Large)->weight('bold')->badge()->color(fn(string $state): string => match ($state) {
                        '0', '' => 'info',
                        '1' => 'danger'
                    })->icon(fn(string $state): string => match ($state) {
                        '0', '' => 'heroicon-o-check-circle',
                        '1' => 'heroicon-o-x-circle'
                    })->formatStateUsing(fn(string $state): string => match ($state) {
                        '0', '' => 'Active',
                        '1' => 'Blocked'
                    }),
                    TextEntry::make('user.email_verified_at')->label('Email Verification')->size(TextEntry\TextEntrySize::Large)->weight('bold')->badge()
                        ->color(fn($state) => $state ? 'primary' : 'danger')
                        ->icon(fn($state) => $state ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                        ->formatStateUsing(fn($state) => $state ? 'Email Verified' : 'Not Verified'),

                    TextEntry::make('user.baned_to')->label('Is User Baned')->size(TextEntry\TextEntrySize::Large)->weight('bold')->badge()
                        ->color(fn($state) => $state ? 'danger' : 'primary')
                        ->icon(fn($state) => $state ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                        ->formatStateUsing(fn($state) => $state ?? 'Active'),

                ])->columns(3),
                Section::make('Club Documents')->schema([
                    ImageEntry::make('registration_document'),
                ])
            ])->columns(1);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->disabledClick(),
                ImageColumn::make('logo')->disk('public')->circular()
                    ->defaultImageUrl(asset('2NoKUDZBHGiX0UUIFb1MJkU6zDwe4FRHFOE9BrRq.png'))->disabledClick(),
                TextColumn::make('name')->label('club name')->searchable()->disabledClick(),
                TextColumn::make('user.name')->label('associated user')->icon('heroicon-o-user')->color('primary')->searchable()->disabledClick(),
                TextColumn::make('mobile_number')->icon('heroicon-o-clipboard-document')->copyable()->searchable(),
                TextColumn::make('year_founded')->color('primary')->disabledClick(),
                TextColumn::make('sport.name')->disabledClick(),
                TextColumn::make('country.name')->disabledClick(),
                ToggleColumn::make('approved_by_admin')->disabledClick()
            ])
            ->filters([
                //
            ])
            ->actions([
//                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
                    BulkAction::make('Approve')
                        ->requiresConfirmation()
                        ->icon('heroicon-o-shield-check')
                        ->color('primary')
                        ->action(fn($records) => $records->each->update(['approved_by_admin' => true])),
                    BulkAction::make('Block')
                        ->requiresConfirmation()
                        ->icon('heroicon-o-exclamation-circle')
                        ->color('danger')
                        ->action(fn($records) => $records->each->update(['approved_by_admin' => false]))
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
//            RelationManagers\UserRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClubs::route('/'),
            'create' => Pages\CreateClub::route('/create'),
            'edit' => Pages\EditClub::route('/{record}/edit'),
            'view' => Pages\ViewClub::route('/{record}'),

        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

}
