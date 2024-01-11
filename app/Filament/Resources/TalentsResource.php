<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TalentsResource\Pages;
use App\Filament\Resources\TalentsResource\RelationManagers;
use App\Models\Position;
use App\Models\Sport;
use App\Models\Talent;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class TalentsResource extends Resource
{
    protected static ?string $model = Talent::class;

    protected static ?string $navigationIcon = 'fas-futbol';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    Toggle::make('approved_by_admin'),
                    Grid::make()->schema([
                        Placeholder::make('user_name')
                            ->label('User Name')
                            ->content(fn(Talent $record) => new HtmlString('<b><a class="text-primary-600 dark:text-primary-400"  href="' . url('admin/users/' . $record->user_id . '/edit') . '">' . User::query()->find($record->user_id)->name . '</a></b>')
                            ),
                        Placeholder::make('user_email')
                            ->label('User Email')
                            ->content(fn(Talent $record): ?string => User::find($record->user_id)->email),
                        Placeholder::make('created_at')
                            ->label('Created at')
                            ->content(fn(Talent $record): ?string => $record->created_at?->diffForHumans()),
                    ])->columns(3)
                ]),
                Section::make()->schema([
                    Grid::make()->schema([
                        TextInput::make('mobile_number'),
                        DatePicker::make('birth_date'),
                        TextInput::make('residence_place'),
                    ])->columns(3),
                    Grid::make()->schema([
                        Select::make('gender')->options([
                            'male' => 'Male',
                            'female' => 'Female',
                            'other' => 'Other',
                        ]),
                        TextInput::make('height')->numeric(),
                        TextInput::make('wight')->numeric(),
                    ])->columns(3),
                    Grid::make()->schema([
                        Select::make('sport_id')->options(Sport::query()->pluck('name', 'id')->toArray())->searchable(),
                    ])->columns(1),

                    Grid::make()->schema([
                        Select::make('parent_position_id')
                            ->options(Position::query()->where('parent_id', 0)->pluck('name', 'id')->toArray())
                            ->searchable()->live(),
                        Select::make('parent_position_id')->options(
                            fn(Get $get) => Position::query()->where('parent_id', $get('parent_position_id'))->get()->isNotEmpty() ?
                                Position::query()->where('parent_id', $get('parent_position_id'))->pluck('name', 'id')->toArray()
                                : Position::query()->where('id', $get('parent_position_id'))->pluck('name', 'id')->toArray()
                        ),
                    ])->columns(2),
                ]),

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
                TextColumn::make('height'),
                TextColumn::make('wight'),
                ToggleColumn::make('approved_by_admin'),
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

    public static function canCreate(): bool
    {
        return false;
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTalents::route('/'),
            'create' => Pages\CreateTalents::route('/create'),
            'edit' => Pages\EditTalents::route('/{record}/edit'),
        ];
    }
}
