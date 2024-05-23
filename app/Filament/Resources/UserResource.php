<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\Section;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'icon-users';
    protected static ?int $navigationSort = 8;

    public static function getEloquentQuery(): Builder
    {
        return User::query()->where('is_admin', false);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    TextInput::make('name')->required(),
                    TextInput::make('email')->required(),
                    TextInput::make('password')->required(fn(string $context): bool => $context === 'create'),
                ]),
                \Filament\Forms\Components\Section::make()->schema([
                    CheckboxList::make('notification_settings')->options([
                        "new_followers" => "New Followers",
                        "follower_challenges" => "Follower Challenges",
                        "follower_opportunities" => "Follower Opportunities",
                        "new_message" => "New Message",
                        "email_notifications" => "Email Notifications"
                    ])->descriptions([
                        'new_followers' => 'notify users when getting new followers',
                        'follower_challenges' => 'notify users when one of his/her following list add new challenge post',
                        'follower_opportunities' => 'notify users when one of his/her following list add new opportunities post',
                        "new_message" => 'notify users when receiving new message',

                    ])
                        ->columns(2)
                        ->searchable()
                        ->bulkToggleable()


                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->color('primary'),
                TextColumn::make('name'),
                TextColumn::make('email')->copyable()->copyMessage('email copied to clipboard')->icon('heroicon-o-clipboard'),
                TextColumn::make('created_at'),
                TextColumn::make('updated_at'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
//                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
//                ]),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

}
