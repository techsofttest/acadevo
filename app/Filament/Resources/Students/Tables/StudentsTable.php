<?php

namespace App\Filament\Resources\Students\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class StudentsTable
{
    public static function configure(Table $table): Table
    {
        return $table 
            ->columns([
                TextColumn::make('full_name')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('Mobile Number')
                    ->searchable(),
                TextColumn::make('institute.lab_code')
                    ->label('Lab Code')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('class')
                    ->label('Class')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('division')
                    ->label('Division')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('institute_id')
                    ->label('Institute')
                    ->relationship('institute', 'name')
                    ->searchable()
                    ->preload(),

                Filter::make('class')
                    ->schema([
                        TextInput::make('class')
                            ->label('Class')
                            ->placeholder('Filter by class (e.g. 10th)'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            filled($data['class'] ?? null),
                            fn (Builder $query) => $query->where('class', 'like', '%' . trim($data['class']) . '%')
                        );
                    }),

                Filter::make('division')
                    ->schema([
                        TextInput::make('division')
                            ->label('Division')
                            ->placeholder('Filter by division (e.g. A)'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            filled($data['division'] ?? null),
                            fn (Builder $query) => $query->where('division', 'like', '%' . trim($data['division']) . '%')
                        );
                    }),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(3)
            ->recordActions([
                \App\Filament\Resources\Students\Actions\ChangeClassAction::make(),
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
