<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class SalesReportExport implements WithMultipleSheets
{
    protected $selectedCategory;

    public function __construct($selectedCategory = null)
    {
        $this->selectedCategory = $selectedCategory;
    }

    public function sheets(): array
    {
        return [
            'Laporan Penjualan' => new LaporanPenjualanSheet($this->selectedCategory),
            'Rekap Harian' => new RekapHarianSheet($this->selectedCategory),
            'Barang Terlaris' => new BarangTerlakisSheet($this->selectedCategory),
            'Rekap Kategori' => new RekapKategoriSheet($this->selectedCategory),
        ];
    }
}

class LaporanPenjualanSheet implements FromCollection, WithTitle, WithHeadings, WithStyles
{
    protected $selectedCategory;

    public function __construct($selectedCategory = null)
    {
        $this->selectedCategory = $selectedCategory;
    }

    public function collection()
    {
        $query = \App\Models\TransactionDetail::with('transaction.customer', 'product.category')
            ->selectRaw('
                transactions.id as transaction_id,
                transactions.date,
                customers.name as customer_name,
                products.name as product_name,
                categories.name as category_name,
                transaction_details.qty,
                transaction_details.price as unit_price,
                (transaction_details.qty * transaction_details.price) as subtotal,
                transactions.total as transaction_total
            ')
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->join('customers', 'transactions.customer_id', '=', 'customers.id')
            ->join('products', 'transaction_details.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->orderByDesc('transactions.date');

        if ($this->selectedCategory && $this->selectedCategory !== 'all') {
            $query->where('categories.id', $this->selectedCategory);
        }

        $details = $query->get();

        $data = collect();
        $no = 1;
        foreach ($details as $detail) {
            $data->push([
                'No' => $no++,
                'Tanggal Transaksi' => $detail->date ? \Carbon\Carbon::parse($detail->date)->format('d-m-Y') : '-',
                'Kode Transaksi' => 'TRX-' . str_pad($detail->transaction_id, 5, '0', STR_PAD_LEFT),
                'Nama Pelanggan' => $detail->customer_name,
                'Nama Barang' => $detail->product_name,
                'Kategori' => $detail->category_name,
                'Jumlah' => $detail->qty,
                'Harga Satuan' => $detail->unit_price,
                'Subtotal' => $detail->subtotal,
                'Total Transaksi' => $detail->transaction_total,
            ]);
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal Transaksi',
            'Kode Transaksi',
            'Nama Pelanggan',
            'Nama Barang',
            'Kategori',
            'Jumlah',
            'Harga Satuan',
            'Subtotal',
            'Total Transaksi',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return $this->applyStyles($sheet, 10);
    }

    public function title(): string
    {
        return 'Laporan Penjualan';
    }

    protected function applyStyles(Worksheet $sheet, int $maxRow)
    {
        // Header styling
        $sheet->getStyle('A1:J1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '003366'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        // Data styling
        for ($row = 2; $row <= $maxRow; $row++) {
            $sheet->getStyle("A$row:J$row")->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ]);

            // Number alignment
            $sheet->getStyle("A$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("G$row:J$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            // Format currency
            $sheet->getStyle("H$row:J$row")->getNumberFormat()->setFormatCode('_("Rp"* #,##0.00_);_("Rp"* (#,##0.00);_("Rp"* "-"??_);_(@_)');
        }

        // Column widths
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(18);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(10);
        $sheet->getColumnDimension('H')->setWidth(15);
        $sheet->getColumnDimension('I')->setWidth(15);
        $sheet->getColumnDimension('J')->setWidth(18);

        return [];
    }
}

class RekapHarianSheet implements FromCollection, WithTitle, WithHeadings, WithStyles
{
    protected $selectedCategory;

    public function __construct($selectedCategory = null)
    {
        $this->selectedCategory = $selectedCategory;
    }

    public function collection()
    {
        $query = \App\Models\Transaction::selectRaw('
                DATE(date) as tanggal,
                COUNT(id) as jumlah_transaksi,
                SUM(total) as total_penjualan
            ')
            ->groupByRaw('DATE(date)')
            ->orderByDesc('tanggal');

        if ($this->selectedCategory && $this->selectedCategory !== 'all') {
            $query->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
                ->join('products', 'transaction_details.product_id', '=', 'products.id')
                ->where('products.category_id', $this->selectedCategory);
        }

        $data = $query->get()->map(function ($item) {
            return [
                'Tanggal' => \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y'),
                'Jumlah Transaksi' => $item->jumlah_transaksi,
                'Total Penjualan' => $item->total_penjualan,
            ];
        });

        return $data;
    }

    public function headings(): array
    {
        return ['Tanggal', 'Jumlah Transaksi', 'Total Penjualan'];
    }

    public function styles(Worksheet $sheet)
    {
        $maxRow = $sheet->getHighestRow();

        $sheet->getStyle('A1:C1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '006699']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);

        for ($row = 2; $row <= $maxRow; $row++) {
            $sheet->getStyle("B$row:C$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("C$row")->getNumberFormat()->setFormatCode('_("Rp"* #,##0.00_);_("Rp"* (#,##0.00);_("Rp"* "-"??_);_(@_)');
        }

        $sheet->getColumnDimension('A')->setWidth(18);
        $sheet->getColumnDimension('B')->setWidth(18);
        $sheet->getColumnDimension('C')->setWidth(18);

        return [];
    }

    public function title(): string
    {
        return 'Rekap Harian';
    }
}

class BarangTerlakisSheet implements FromCollection, WithTitle, WithHeadings, WithStyles
{
    protected $selectedCategory;

    public function __construct($selectedCategory = null)
    {
        $this->selectedCategory = $selectedCategory;
    }

    public function collection()
    {
        $query = \App\Models\TransactionDetail::selectRaw('
                products.name as product_name,
                SUM(transaction_details.qty) as total_terjual,
                SUM(transaction_details.qty * transaction_details.price) as total_pendapatan
            ')
            ->join('products', 'transaction_details.product_id', '=', 'products.id')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_terjual');

        if ($this->selectedCategory && $this->selectedCategory !== 'all') {
            $query->join('categories', 'products.category_id', '=', 'categories.id')
                ->where('categories.id', $this->selectedCategory);
        }

        $data = $query->get()->map(function ($item, $index) {
            return [
                'No' => $index + 1,
                'Nama Barang' => $item->product_name,
                'Total Terjual' => $item->total_terjual,
                'Total Pendapatan' => $item->total_pendapatan,
            ];
        });

        return $data;
    }

    public function headings(): array
    {
        return ['No', 'Nama Barang', 'Total Terjual', 'Total Pendapatan'];
    }

    public function styles(Worksheet $sheet)
    {
        $maxRow = $sheet->getHighestRow();

        $sheet->getStyle('A1:D1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '009900']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);

        for ($row = 2; $row <= $maxRow; $row++) {
            $sheet->getStyle("A$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("C$row:D$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("D$row")->getNumberFormat()->setFormatCode('_("Rp"* #,##0.00_);_("Rp"* (#,##0.00);_("Rp"* "-"??_);_(@_)');
        }

        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(18);

        return [];
    }

    public function title(): string
    {
        return 'Barang Terlaris';
    }
}

class RekapKategoriSheet implements FromCollection, WithTitle, WithHeadings, WithStyles
{
    protected $selectedCategory;

    public function __construct($selectedCategory = null)
    {
        $this->selectedCategory = $selectedCategory;
    }

    public function collection()
    {
        $query = \App\Models\TransactionDetail::selectRaw('
                categories.name as category_name,
                SUM(transaction_details.qty) as total_item_terjual,
                SUM(transaction_details.qty * transaction_details.price) as total_penjualan
            ')
            ->join('products', 'transaction_details.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_item_terjual');

        if ($this->selectedCategory && $this->selectedCategory !== 'all') {
            $query->where('categories.id', $this->selectedCategory);
        }

        $data = $query->get()->map(function ($item, $index) {
            return [
                'No' => $index + 1,
                'Kategori' => $item->category_name,
                'Total Item Terjual' => $item->total_item_terjual,
                'Total Penjualan' => $item->total_penjualan,
            ];
        });

        return $data;
    }

    public function headings(): array
    {
        return ['No', 'Kategori', 'Total Item Terjual', 'Total Penjualan'];
    }

    public function styles(Worksheet $sheet)
    {
        $maxRow = $sheet->getHighestRow();

        $sheet->getStyle('A1:D1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FF6600']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);

        for ($row = 2; $row <= $maxRow; $row++) {
            $sheet->getStyle("A$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("C$row:D$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("D$row")->getNumberFormat()->setFormatCode('_("Rp"* #,##0.00_);_("Rp"* (#,##0.00);_("Rp"* "-"??_);_(@_)');
        }

        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(18);
        $sheet->getColumnDimension('D')->setWidth(18);

        return [];
    }

    public function title(): string
    {
        return 'Rekap Kategori';
    }
}
