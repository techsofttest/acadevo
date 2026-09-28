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
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('sku')
                    ->label('Main SKU')
                    ->placeholder('e.g. PRD-RES-001')
                    ->unique(table: 'products', column: 'sku', ignoreRecord: true)
                    ->maxLength(255),
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
                Repeater::make('variants')
                    ->relationship('variants')
                    ->schema([
                        TextInput::make('sku')
                            ->label('Variant SKU')
                            ->placeholder('e.g. PRD-RES-001-10OHM')
                            ->unique(table: 'product_variants', column: 'sku', ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make('value')
                            ->label('Value / Quantity')
                            ->placeholder('e.g. 10, 20, 500')
                            ->required(),
                        TextInput::make('unit')
                            ->label('Unit')
                            ->placeholder('e.g. ohm')
                            ->required(),
                        TextInput::make('strike_price')
                            ->label('Strike Price (MRP)')
                            ->numeric()
                            ->prefix('₹')
                            ->required(),
                        TextInput::make('selling_price')
                            ->label('Selling Price')
                            ->numeric()
                            ->prefix('₹')
                            ->required(),
                        TextInput::make('stock')
                            ->label('Stock')
                            ->numeric()
                            ->default(0)
                            ->required(),
                        Toggle::make('is_default')
                            ->label('Default Variant')
                            ->default(false),
                    ])
                    ->columns(3)
                    ->columnSpanFull()
                    ->minItems(1)
                    ->defaultItems(1)
                    ->required()
                    ->addActionLabel('Add Variant Option'),
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
