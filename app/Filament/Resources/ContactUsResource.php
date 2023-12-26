<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactUsResource\Pages;
use App\Filament\Resources\ContactUsResource\RelationManagers;
use App\Models\ContactUs;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\Action;
use stdClass;

class ContactUsResource extends Resource
{
    protected static ?string $model = ContactUs::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $navigationLabel = 'Contact Us';
    protected static ?int $navigationSort = 5;
    public static function getNavigationBadge(): ?string
    {
        return ContactUs::query()->where('action', 'action_required')->count();
    }

    public static function getEloquentQuery(): Builder
    {
        return ContactUs::query()->orderBy('created_at', 'DESC');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Contact Info')->schema([
                    Forms\Components\Group::make()->schema([
                        TextInput::make('name')->label('Sender Name')->disabled(),
                        TextInput::make('email')->label('Sender Email')->disabled(),
                    ])->columns(2),
                    Forms\Components\Textarea::make('message')->disabled()->rows(10)
                ]),
                Section::make('Response')->schema([
                    RichEditor::make('response')->required()
                ])->columns(1)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')->state(
                    static function (HasTable $livewire, stdClass $rowLoop): string {
                        return (string) (
                            $rowLoop->iteration +
                            ($livewire->getTableRecordsPerPage() * (
                                    $livewire->getTablePage() - 1
                                ))
                        );
                    }
                ),
                TextColumn::make('name')->label('Sender Name'),
                TextColumn::make('email')->label('Sender Email')->copyable()->icon('heroicon-o-clipboard')->color('primary'),
                TextColumn::make('message')->label('Message')->limit(35),
                TextColumn::make('action')->label('Latest Action')->badge()->color(function ($record) {
                    return self::badgeColorHandler($record);
                }),

            ])
            ->filters([
                SelectFilter::make('action')
                    ->options([
                        'action_required' => 'Action Required',
                        'responded' => 'Responded',
                        'archived' => 'Archived',
                        'junk' => 'Junk or inappropriate',
                    ])->searchable()->default(['action_required'])->multiple()
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('response')->color('success'),
                ActionGroup::make([
                    Action::make('archive')
                        ->label('mark as archived')
                        ->requiresConfirmation()
                        ->action(fn(ContactUs $record) => $record->update(['action' => 'archived', 'admin_id' => auth()->user()->id]))
                        ->icon('heroicon-o-archive-box-arrow-down')
                        ->color('info')
                        ->requiresConfirmation(false),
                    Action::make('archive')
                        ->label('mark as junk or inappropriate')
                        ->requiresConfirmation()
                        ->action(fn(ContactUs $record) => $record->update(['action' => 'junk' , 'admin_id' => auth()->user()->id]))
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->requiresConfirmation(false),
                ])->iconButton()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
//                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function badgeColorHandler($record)
    {
        if ($record->action == 'action_required') {
            return 'warning';
        } elseif ($record->action == 'junk' || $record->action == 'archived') {
            return 'danger';
        } elseif ($record->action == 'responded')
            return 'success';
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContactUs::route('/'),
//            'create' => Pages\CreateContactUs::route('/create'),
            'edit' => Pages\EditContactUs::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
