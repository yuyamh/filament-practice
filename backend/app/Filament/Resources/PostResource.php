<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                # タイトル　通常の入力欄
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->columnSpan(2)
                    ->maxLength(255),
                # 内容 リッチテキスト
                Forms\Components\RichEditor::make('body')
                    ->columnSpan(2)
                    ->maxLength(65535),
                # 記事タイプ select box
                Forms\Components\Select::make('type')
                    ->options([
                        '0' => '通常',
                        '1' => 'Tips',
                        '2' => 'その他'
                    ]),
                # 投稿日時 入力なしで下書きとすることもできるが、入力した場合には現在以降を指定しないとエラー
                Forms\Components\DateTimePicker::make('published')
                    ->minDate(now()),
            ]);
    }

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('title'),
            Tables\Columns\TextColumn::make('published'),
        ])
        ->filters([
            // 下記のようにすると、投稿済みのもののみ絞り込める
            //Tables\Filters\Filter::make('published')
            //    ->query(fn (Builder $query): Builder => $query->whereNotNull('published')),
        ])
        ->actions([
            // 編集画面へのリンクなど、単発レコードへのリンクが出せる
            Tables\Actions\EditAction::make(),
        ])
        ->bulkActions([
            // 一斉削除など、まとめてアクションを行える
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
