<?php

namespace App\Exports;

use App\Models\OrderItem;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;

// OrderItemsExport.php
class OrderItemsExport implements FromCollection, WithHeadings
{
    protected $items;

    public function __construct($items)
    {
        $this->items = $items;
    }

    public function collection()
    {
        return $this->items->map(function($item) {
            return [
                'رقم الطلب' => '#ORD-' . $item->order->order_id,
                'الطالب' => $item->order->student->full_name ?? 'غير معروف',
                'المنتج' => $item->product->name,
                'الكمية' => $item->quantity,
                'السعر' => number_format($item->price, 2),
                'المجموع' => number_format($item->quantity * $item->price, 2),
                'التاريخ' => $item->created_at->format('Y-m-d H:i')
            ];
        });
    }

    public function headings(): array
    {
        return ['رقم الطلب', 'الطالب', 'المنتج', 'الكمية', 'السعر', 'المجموع', 'التاريخ'];
    }
}