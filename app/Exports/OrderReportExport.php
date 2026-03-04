<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class OrderReportExport implements 
    FromCollection, 
    WithHeadings, 
    WithMapping, 
    ShouldAutoSize,
    WithEvents
{
    protected $startDate;
    protected $endDate;
    protected $orders;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate   = $endDate;

        // Simpan orders biar bisa dipakai di merge
        $this->orders = Order::with('orderDetails')
            ->whereBetween('order_date', [$this->startDate, $this->endDate])
            ->orderBy('order_date', 'desc')
            ->get();
    }

    public function collection()
    {
        return $this->orders->flatMap(function ($order) {
            return $order->orderDetails;
        });
    }

    public function map($detail): array
    {
        return [
            $detail->order->order_no,
            $detail->order->user_name,
            $detail->order->order_date,
            $detail->order->status,
            $detail->package_name,
            $detail->class_name,
            $detail->total_quota,
            $detail->used_quota,
            $detail->remaining_quota,
            $detail->order->total_amount,
        ];
    }

    public function headings(): array
    {
        return [
            'Order No',
            'User Name',
            'Order Date',
            'Status',
            'Package Name',
            'Class Name',
            'Total Quota',
            'Used Quota',
            'Remaining Quota',
            'Total Amount'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                // 🔥 Bold Header
                $sheet->getStyle('A1:J1')->getFont()->setBold(true);

                // 🔥 Freeze Header
                $sheet->freezePane('A2');

                $row = 2;

                foreach ($this->orders as $order) {

                    $detailCount = $order->orderDetails->count();

                    if ($detailCount > 1) {

                        $startRow = $row;
                        $endRow   = $row + $detailCount - 1;

                        // Merge kolom order-level
                        foreach (['A','B','C','D','J'] as $column) {
                            $sheet->mergeCells("$column$startRow:$column$endRow");
                            $sheet->getStyle("$column$startRow")
                                  ->getAlignment()
                                  ->setVertical(Alignment::VERTICAL_CENTER);
                        }
                    }

                    $row += $detailCount;
                }
            }
        ];
    }
}