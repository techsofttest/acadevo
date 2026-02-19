<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table,?\Closure $query = null): Table
    {
        $table = $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('category.name')
                ->label('Category')
                ->sortable()
                ->searchable(),
                ImageColumn::make('image'),
                ToggleColumn::make('is_active')
                ->label('Active')
                ->sortable()
                ->toggleable()
                ->afterStateUpdated(function ($record, $state) {
                    // This runs automatically when toggled
                    $record->update(['is_active' => $state]);
                }),
                ToggleColumn::make('featured')
                ->label('Featured')
                ->sortable()
                ->toggleable()
                ->afterStateUpdated(function ($record, $state) {
                    $record->update(['featured' => $state]);
                }),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);


            if ($query) {
            $table = $table->query($query);
            }


            return $table;
    }
}
