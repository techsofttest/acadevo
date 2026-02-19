<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use App\Models\Category;


class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Select::make('parent_id')
                ->label('Parent Category')
                ->options(Category::query()->pluck('name', 'id'))
                ->nullable()
                ->searchable(),
                TextInput::make('name')
                    ->required(),
                /*TextInput::make('slug')
                    ->required(),*/
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->image(),
                TextInput::make('meta_title')
                    ->default(null),
                TextInput::make('meta_desc')
                    ->default(null),
                //Toggle::make('is_active')
                    //->required(),
            ]);
    }
}
