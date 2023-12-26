<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CoachResource\Pages;
use App\Filament\Resources\CoachResource\RelationManagers;
use App\Models\Coach;
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
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class CoachResource extends Resource
{
    protected static ?string $model = Coach::class;

    protected static ?string $navigationIcon = 'fas-hands-asl-interpreting';
    protected static ?int $navigationSort = 2;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    Toggle::make('approved_by_admin'),
                    Grid::make()->schema([

                        Placeholder::make('user_name')
                            ->label('User Name')
                            ->content(fn(Coach $record) => new HtmlString('<b><a class="text-primary-600 dark:text-primary-400"  href="' . url('admin/users/' . $record->user_id . '/edit') . '">' . User::query()->find($record->user_id)->name . '</a></b>')
                            ),

                        Placeholder::make('user_email')
                            ->label('User Email')
                            ->content(fn(Coach $record): ?string => User::find($record->user_id)->email),

                        Placeholder::make('created_at')
                            ->label('Created at')
                            ->content(fn(Coach $record): ?string => $record->created_at?->diffForHumans()),
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
                    ])->columns(1),

                ])
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
            'index' => Pages\ListCoaches::route('/'),
            'create' => Pages\CreateCoach::route('/create'),
            'edit' => Pages\EditCoach::route('/{record}/edit'),
        ];
    }
    public static function canCreate(): bool
    {
        return false;
    }

}
