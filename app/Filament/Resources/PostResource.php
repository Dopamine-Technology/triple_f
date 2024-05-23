<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Language;
use App\Models\Post;
use Filament\Tables\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use stdClass;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'icon-blog';
    protected static ?string $navigationLabel = 'Website Blog Posts';
    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    Group::make()->schema([
//                        TextInput::make('title.ar')->label('Arabic Title')->required(),
                        TextInput::make('title.en')->label('English Title')->required(),
                    ])->columns(1),
                    Group::make()->schema([
//                        RichEditor::make('content.ar')->label('Arabic Content')->required(),
                        RichEditor::make('content.en')->label('English Content')->required(),
                    ])->columns(1),
                ]),
                Section::make()->schema([
                    Group::make()->schema([
                        SpatieMediaLibraryFileUpload::make('main_image')->image()->collection('main_image')->required(),
                        SpatieMediaLibraryFileUpload::make('media')->multiple()->collection('post_media'),
                    ])

                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')->state(
                    static function (HasTable $livewire, stdClass $rowLoop): string {
                        return (string)(
                            $rowLoop->iteration +
                            ($livewire->getTableRecordsPerPage() * (
                                    $livewire->getTablePage() - 1
                                ))
                        );
                    }
                ),

                TextColumn::make('title'),
                TextColumn::make('content')->limit(35)->html(),
                TextColumn::make('created_at')->color('primary'),
                TextColumn::make('translated_languages')->state(
                    static function (Post $post) {
                        return $post->getLocale();
                    }
                )->badge(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('delete')
                    ->requiresConfirmation()
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->action(fn (Post $record) => $record->delete()),
                Tables\Actions\EditAction::make(),
                Action::make('translate posts')
                    ->form([
                        Select::make('iso_code')
                            ->label('select language')
                            ->options(Language::query()->pluck('name', 'iso_code'))
                            ->searchable()
                            ->required(),
                        TextInput::make('title')->label('Title')->required(),
                        RichEditor::make('content')->label('Content')->required(),
                    ])
                    ->action(function (array $data, Post $record): void {
                        $record->setTranslation('title', $data['iso_code'], $data['title'])
                            ->setTranslation('content', $data['iso_code'], $data['content'])
                            ->save();
                    })->icon('heroicon-o-language')->color('info')
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
