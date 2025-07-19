<?php

namespace App\Exports;

use App\Models\GiftCard;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Color;

class GiftCardExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle, ShouldAutoSize
{
    protected $filters;
    protected $dateRange;

    public function __construct($filters = [], $dateRange = null)
    {
        $this->filters = $filters;
        $this->dateRange = $dateRange;
    }

    public function collection()
    {
        $query = GiftCard::with([
            'user', 'vendor', 'product', 'offer', 'order',
            'walletTransaction', 'backgroundImage', 'createdBy'
        ]);

        // Apply filters
        if (!empty($this->filters['gift_type'])) {
            $query->where('gift_type', $this->filters['gift_type']);
        }

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (!empty($this->filters['redemption_method'])) {
            $query->where('redemption_method', $this->filters['redemption_method']);
        }

        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('recipient_name', 'like', "%{$search}%")
                  ->orWhere('recipient_email', 'like', "%{$search}%");
            });
        }

        if (!empty($this->filters['vendor_id'])) {
            $query->where('vendor_id', $this->filters['vendor_id']);
        }

        if (!empty($this->filters['user_id'])) {
            $query->where('user_id', $this->filters['user_id']);
        }

        // Apply date range
        if (!empty($this->dateRange['from'])) {
            $query->whereDate('created_at', '>=', $this->dateRange['from']);
        }

        if (!empty($this->dateRange['to'])) {
            $query->whereDate('created_at', '<=', $this->dateRange['to']);
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Code',
            'Type',
            'Amount',
            'Currency',
            'Status',
            'Redemption Method',
            'Recipient Name',
            'Recipient Email',
            'Recipient Phone',
            'Message',
            'Background Color',
            'Background Image',
            'Expires At',
            'Redeemed At',
            'User',
            'Vendor',
            'Product',
            'Offer',
            'Order ID',
            'Wallet Transaction ID',
            'Created By',
            'Created At',
            'Updated At',
        ];
    }

    public function map($giftCard): array
    {
        return [
            $giftCard->id,
            $giftCard->code,
            $giftCard->gift_type_label,
            $giftCard->amount,
            $giftCard->currency,
            $giftCard->status_label,
            $giftCard->redemption_method,
            $giftCard->recipient_name,
            $giftCard->recipient_email,
            $giftCard->recipient_number,
            $giftCard->message,
            $giftCard->background_color,
            $giftCard->backgroundImage?->name ?? 'Custom',
            $giftCard->expires_at?->format('Y-m-d H:i:s') ?? '',
            $giftCard->redeemed_at?->format('Y-m-d H:i:s') ?? '',
            $giftCard->user?->name ?? '',
            $giftCard->vendor?->name ?? '',
            $giftCard->product?->title ?? '',
            $giftCard->offer?->title ?? '',
            $giftCard->order_id ?? '',
            $giftCard->wallet_transaction_id ?? '',
            $giftCard->createdBy?->name ?? '',
            $giftCard->created_at->format('Y-m-d H:i:s'),
            $giftCard->updated_at->format('Y-m-d H:i:s'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Header styles
        $sheet->getStyle('A1:X1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => new Color('FFFFFF'),
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => new Color('4F46E5'),
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => new Color('000000'),
                ],
            ],
        ]);

        // Data row styles
        $sheet->getStyle('A2:X' . ($sheet->getHighestRow()))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => new Color('E5E7EB'),
                ],
            ],
        ]);

        // Alternate row colors
        for ($row = 2; $row <= $sheet->getHighestRow(); $row++) {
            if ($row % 2 == 0) {
                $sheet->getStyle("A{$row}:X{$row}")->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->setStartColor(new Color('F9FAFB'));
            }
        }

        // Status column colors
        $statusColumn = 'F';
        for ($row = 2; $row <= $sheet->getHighestRow(); $row++) {
            $status = $sheet->getCell($statusColumn . $row)->getValue();
            $color = $this->getStatusColor($status);
            if ($color) {
                $sheet->getStyle($statusColumn . $row)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->setStartColor(new Color($color));
            }
        }

        return $sheet;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,  // ID
            'B' => 15,  // Code
            'C' => 12,  // Type
            'D' => 12,  // Amount
            'E' => 10,  // Currency
            'F' => 12,  // Status
            'G' => 18,  // Redemption Method
            'H' => 20,  // Recipient Name
            'I' => 25,  // Recipient Email
            'J' => 15,  // Recipient Phone
            'K' => 30,  // Message
            'L' => 15,  // Background Color
            'M' => 20,  // Background Image
            'N' => 18,  // Expires At
            'O' => 18,  // Redeemed At
            'P' => 20,  // User
            'Q' => 20,  // Vendor
            'R' => 25,  // Product
            'S' => 25,  // Offer
            'T' => 12,  // Order ID
            'U' => 20,  // Wallet Transaction ID
            'V' => 20,  // Created By
            'W' => 18,  // Created At
            'X' => 18,  // Updated At
        ];
    }

    public function title(): string
    {
        return 'Gift Cards Report';
    }

    private function getStatusColor($status)
    {
        return match(strtolower($status)) {
            'active' => 'DCFCE7',  // Green
            'used' => 'FEF3C7',    // Yellow
            'expired' => 'FEE2E2', // Red
            'cancelled' => 'F3F4F6', // Gray
            default => null,
        };
    }
}
