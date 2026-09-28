<?php

namespace App\Filament\Resources\Students\Pages;

use App\Filament\Resources\Students\StudentResource;
use App\Services\StudentImportService;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;

class ListStudents extends ListRecords
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadTemplate')
                ->label('Download Template')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->url(route('admin.students.download-template'))
                ->openUrlInNewTab(),

            Action::make('importStudents')
                ->label('Import Excel')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->modalHeading('Import Students from Excel / CSV')
                ->modalDescription('Upload an Excel (.xlsx, .xls) or CSV (.csv) file following the template columns: Full Name, Institute, Roll Number, Division, Class, Email, Phone, Course Name.')
                ->modalSubmitActionLabel('Import')
                ->form([
                    FileUpload::make('file')
                        ->label('Select File')
                        ->disk('local')
                        ->directory('temp-student-imports')
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-excel',
                            'text/csv',
                            'text/plain',
                        ])
                        ->required()
                        ->storeFiles(),
                ])
                ->action(function (array $data, StudentImportService $importService) {
                    $relativeFilePath = $data['file'];
                    $fullPath = Storage::disk('local')->path($relativeFilePath);

                    try {
                        $result = $importService->import($fullPath);

                        // Clean up temporary file
                        Storage::disk('local')->delete($relativeFilePath);

                        if (!empty($result['errors'])) {
                            Notification::make()
                                ->warning()
                                ->title('Import completed with warnings')
                                ->body("Processed: {$result['total']} (Created: {$result['created']}, Updated: {$result['updated']}). Errors encountered: " . count($result['errors']) . "\n" . implode("\n", array_slice($result['errors'], 0, 5)))
                                ->persistent()
                                ->send();
                        } else {
                            Notification::make()
                                ->success()
                                ->title('Students imported successfully')
                                ->body("Total processed: {$result['total']} (Created: {$result['created']}, Updated: {$result['updated']})")
                                ->send();
                        }
                    } catch (\Throwable $e) {
                        if (Storage::disk('local')->exists($relativeFilePath)) {
                            Storage::disk('local')->delete($relativeFilePath);
                        }

                        Notification::make()
                            ->danger()
                            ->title('Import Failed')
                            ->body($e->getMessage())
                            ->send();
                    }
                }),

            CreateAction::make(),
        ];
    }
}
