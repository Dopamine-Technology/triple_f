<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScoutResource\Pages;
use App\Filament\Resources\ScoutResource\RelationManagers;
use App\Models\City;
use App\Models\Coach;
use App\Models\Country;
use App\Models\Scout;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class ScoutResource extends Resource
{
    protected static ?string $model = Scout::class;

    protected static ?string $navigationIcon = 'icon-scout';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Toggle::make('approved_by_admin'),
                Section::make('User Info')
                    ->schema([
                        TextInput::make('name')->required(),
                        TextInput::make('email')->required(),
                        TextInput::make('password')->required(fn(string $context): bool => $context === 'create'),
                    ])->relationship('user'),

                Section::make()->schema([
                    Grid::make()->schema([
                        Select::make('country_id')->label('Country')->options(Country::query()->pluck('name', 'id'))->searchable(),
                        Select::make('city_id')->label('City')->options(City::query()->pluck('name', 'id'))->searchable(),
                    ])->columns(2),
                    Grid::make()->schema([
                        TextInput::make('mobile_number'),
                        DatePicker::make('birth_date'),
                        Select::make('country_id')
                            ->options(Country::query()->pluck('name', 'id')->toArray())
                            ->getSearchResultsUsing(fn(string $search): array => Country::query()->where('name', 'like', "%{$search}%")->limit(10)->pluck('name', 'id')->toArray())->label('Select Country')
                            ->getOptionLabelsUsing(fn(array $values): array => Country::whereIn('id', $values)->pluck('name', 'id')->toArray())
                            ->searchable(),
                        Select::make('city_id')
                            ->label('Select City')
                            ->options(City::query()->pluck('name', 'id')->toArray())
                            ->getSearchResultsUsing(fn(string $search): array => City::query()->where('name', 'like', "%{$search}%")->limit(10)->pluck('name', 'id')->toArray())
                            ->getOptionLabelsUsing(fn(array $values): array => City::whereIn('id', $values)->pluck('name', 'id')->toArray())
                            ->searchable(),
                    ])->columns(4),
                    Grid::make()->schema([
                        Select::make('gender')->options([
                            'male' => 'Male',
                            'female' => 'Female',
                            'other' => 'Other',
                        ]),
                    ])->columns(1),

                ]),
                Section::make()->schema([
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


                ])->relationship('user'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                ImageColumn::make('user.image')->disk('public')->circular()
                    ->defaultImageUrl(asset('2NoKUDZBHGiX0UUIFb1MJkU6zDwe4FRHFOE9BrRq.png'))->disabledClick(),
                TextColumn::make('mobile_number')->copyable()->icon('heroicon-o-clipboard-document'),
                TextColumn::make('user.name')->color('primary'),
                TextColumn::make('birth_date')->date()->tooltip(fn($state) => Carbon::parse($state)->age . ' years old'),
                TextColumn::make('residence_place'),
                TextColumn::make('sport.name'),
                TextColumn::make('gender'),
                ToggleColumn::make('approved_by_admin'),
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
            'index' => Pages\ListScouts::route('/'),
            'create' => Pages\CreateScout::route('/create'),
            'edit' => Pages\EditScout::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
