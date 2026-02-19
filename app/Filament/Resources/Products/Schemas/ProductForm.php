<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use App\Models\Category;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Select::make('category_id')
                ->label('Category')
                ->relationship('category', 'name')
                ->required()
                ->searchable()
                ->preload()
                ->columnSpanFull(),
                RichEditor::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('original_price')
                    ->numeric()
                    ->prefix('₹')
                    ->required(),
                TextInput::make('offer_price')
                    ->numeric()
                    ->prefix('₹'),
                TextInput::make('meta_title')
                    ->default(null),
                TextInput::make('meta_desc')
                    ->default(null),
                FileUpload::make('image')
                    ->image()
                    ->columnSpanFull(),
                FileUpload::make('additional_images')
                    ->image()
                    ->multiple()
                    ->columnSpanFull()
            ]);
    }
}
