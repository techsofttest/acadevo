<?php

namespace App\Filament\Resources\Students\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class StudentsTable
{
    public static function configure(Table $table): Table
    {
        return $table 
            ->columns([
                TextColumn::make('full_name')
                    ->searchable(),
                TextColumn::make('institute.lab_code')
                    ->label('Lab Code')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('division')
                    ->label('Division / Class')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('institute_id')
                    ->label('Institute')
                    ->relationship('institute', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make(),
                \Filament\Actions\Action::make('certificate')
                    ->label('Certificate')
                    ->icon('heroicon-o-document-text')
                    ->url(fn ($record) => route('certificate.generate', ['student' => $record->id]))
                    ->openUrlInNewTab(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
