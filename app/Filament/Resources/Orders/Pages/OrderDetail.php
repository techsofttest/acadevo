<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use App\Models\Order;
use Filament\Resources\Pages\ViewRecord;

class OrderDetail extends ViewRecord
{
    //use InteractsWithRecord;

    protected static string $resource = OrderResource::class;

    protected string $view = 'filament.resources.orders.pages.order-detail';

    public Order $order;

    public function getTitle(): string
    {
        return "Order Details";
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('print')
                ->label('Print Invoice')
                ->icon('heroicon-o-printer')
                ->color('gray')
                ->url(fn () => route('orders.print', $this->record))
                ->openUrlInNewTab(),
        ];
    }
}
