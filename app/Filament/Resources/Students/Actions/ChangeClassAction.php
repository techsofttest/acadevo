<?php

namespace App\Filament\Resources\Students\Actions;

use App\Models\Student;
use App\Services\Students\StudentClassService;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Illuminate\Validation\ValidationException;

class ChangeClassAction
{
    public static function make(?string $name = 'changeClass'): Action
    {
        return Action::make($name)
            ->label('Change Class')
            ->icon(Heroicon::OutlinedAcademicCap)
            ->color('primary')
            ->modalHeading(fn (Student $record): string => "Change Class & Division — {$record->full_name}")
            ->modalSubmitActionLabel('Save Changes')
            ->form([
                TextInput::make('current_class')
                    ->label('Current Class')
                    ->disabled()
                    ->default(fn (Student $record): ?string => $record->class)
                    ->placeholder('None'),

                TextInput::make('current_division')
                    ->label('Current Division')
                    ->disabled()
                    ->default(fn (Student $record): ?string => $record->division)
                    ->placeholder('None'),

                TextInput::make('class')
                    ->label('New Class')
                    ->placeholder('e.g. 10th / MCA / B.Tech')
                    ->required(),

                TextInput::make('division')
                    ->label('New Division')
                    ->placeholder('e.g. A / B / Morning'),

                TextInput::make('academic_year')
                    ->label('Academic Year')
                    ->placeholder('e.g. 2026-27'),

                DatePicker::make('from_date')
                    ->label('Effective From')
                    ->default(now()->toDateString())
                    ->required()
                    ->native(false)
                    ->displayFormat('d/m/Y'),
            ])
            ->action(function (Student $record, array $data, StudentClassService $classService) {
                try {
                    $classService->changeClass(
                        student: $record,
                        class: $data['class'],
                        division: $data['division'] ?? null,
                        academicYear: $data['academic_year'] ?? null,
                        fromDate: $data['from_date'] ?? now()
                    );

                    Notification::make()
                        ->success()
                        ->title('Class changed successfully.')
                        ->send();
                } catch (ValidationException $e) {
                    $errors = $e->errors();
                    $firstMessage = reset($errors)[0] ?? $e->getMessage();

                    Notification::make()
                        ->danger()
                        ->title('Validation Error')
                        ->body($firstMessage)
                        ->send();

                    // Re-throw so Filament can highlight modal validation if applicable or prevent modal dismissal
                    throw $e;
                } catch (\Throwable $e) {
                    Notification::make()
                        ->danger()
                        ->title('Failed to change class')
                        ->body($e->getMessage())
                        ->send();

                    throw $e;
                }
            });
    }
}
