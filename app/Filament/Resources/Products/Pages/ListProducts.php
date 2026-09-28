<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use App\Services\ProductImportService;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadTemplate')
                ->label('Download Template')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->url(route('admin.products.download-template'))
                ->openUrlInNewTab(),

            Action::make('importProducts')
                ->label('Import Excel')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->modalHeading('Import Products from Excel / CSV')
                ->modalDescription('Upload an Excel (.xlsx, .xls) or CSV (.csv) file with columns: Product Name, Category, Product SKU, Variant SKU, Value, Unit, Strike Price, Selling Price, Stock, Is Default, Description.')
                ->modalSubmitActionLabel('Import')
                ->form([
                    FileUpload::make('file')
                        ->label('Select File')
                        ->disk('local')
                        ->directory('temp-product-imports')
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-excel',
                            'text/csv',
                            'text/plain',
                        ])
                        ->required()
                        ->storeFiles(),
                ])
                ->action(function (array $data, ProductImportService $importService) {
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
                                ->body("Products (Created: {$result['products_created']}, Updated: {$result['products_updated']}), Variants (Created: {$result['variants_created']}, Updated: {$result['variants_updated']}). Errors: " . count($result['errors']) . "\n" . implode("\n", array_slice($result['errors'], 0, 5)))
                                ->persistent()
                                ->send();
                        } else {
                            Notification::make()
                                ->success()
                                ->title('Products imported successfully')
                                ->body("Products (Created: {$result['products_created']}, Updated: {$result['products_updated']}), Variants (Created: {$result['variants_created']}, Updated: {$result['variants_updated']})")
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
