<?php

namespace App\Filament\Resources\Offers\Schemas;

use Filament\Schemas\Schema;

use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;


class OfferForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Select::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->required(),

                TextInput::make('percentage')
                    ->label('Offer Percentage')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->required(),
            ]);
    }
}
