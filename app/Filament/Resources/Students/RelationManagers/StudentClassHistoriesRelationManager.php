<?php

namespace App\Filament\Resources\Students\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StudentClassHistoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'classHistories';

    protected static ?string $title = 'Class History';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('class')
            ->defaultSort('from_date', 'desc')
            ->columns([
                TextColumn::make('academic_year')
                    ->label('Academic Year')
                    ->placeholder('—'),

                TextColumn::make('class')
                    ->label('Class')
                    ->searchable(),

                TextColumn::make('division')
                    ->label('Division')
                    ->placeholder('—')
                    ->searchable(),

                TextColumn::make('from_date')
                    ->label('From')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('to_date')
                    ->label('To')
                    ->formatStateUsing(fn ($state) => $state ? \Carbon\Carbon::parse($state)->format('d/m/Y') : 'Present')
                    ->sortable(),
            ])
            ->headerActions([])
            ->recordActions([])
            ->toolbarActions([]);
    }

    public function isReadOnly(): bool
    {
        return true;
    }
}
