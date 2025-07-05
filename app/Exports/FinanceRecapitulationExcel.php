<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FinanceRecapitulationExcel implements FromArray, WithStyles, WithColumnWidths, WithEvents, WithTitle, WithDrawings
{
    private $data;
    private $dataAccumulation;
    private $startDate;
    private $startDateRaw;
    private $endDate;
    private $endDateRaw;
    private $reportInfo;

    private const COLORS = [
        'PRIMARY_BLUE' => '1E3A8A',
        'SECONDARY_BLUE' => '3B82F6',
        'LIGHT_BLUE' => 'DBEAFE',
        'ACCENT_BLUE' => '1D4ED8',
        'DARK_BLUE' => '1E40AF',
        'NAVY_BLUE' => '1E293B',
        'SKY_BLUE' => 'E0F2FE',
        'ROYAL_BLUE' => '2563EB',
        'STEEL_BLUE' => '475569',
        'POWDER_BLUE' => 'F0F9FF',
    ];

    public function __construct(Collection $financeRecapitulations, object $financeAccumulation, object $daterange)
    {
        $this->data = $financeRecapitulations;
        $this->dataAccumulation = $financeAccumulation;
        $this->startDate = $daterange->startDate;
        $this->startDateRaw = $daterange->startDateRaw;
        $this->endDate = $daterange->endDate;
        $this->endDateRaw = $daterange->endDateRaw;
        $this->reportInfo = $this->getDefaultReportInfo();
    }

    public function title(): string
    {
        return 'Laporan Rekapitulasi Keuangan';
    }

    public function array(): array {
        $header = ['No', 'Tanggal', 'Kode Transaksi', 'Kategori', 'Deskripsi', 'Pemasukan (Rp)', 'Pengeluaran (Rp)'];
        $totalIncome = $this->dataAccumulation->total_income;
        $totalExpense = $this->dataAccumulation->total_expense;
        $dataRows = $this->getFormattedData();
        $footer = ['', '', '', '', 'Total',
            $totalIncome ? 'Rp. ' . number_format($totalIncome, 0, ',', '.') : '',
            $totalExpense ? 'Rp. ' . number_format($totalExpense, 0, ',', '.') : ''
        ];

        return [
            [$this->reportInfo['name'], '', '', '', '', '', ''],
            ['Tanggal Cetak: '. $this->reportInfo['export_date'], '', '', '', '', '', ''],
            ['', '', '', '', '', '', ''],
            ['', '', '', '', '', '', ''],
            ['', '', '', 'LAPORAN REKAPITULASI KEUANGAN', '', '', ''],
            ['', '', '', 'Periode: ' . $this->startDate . ' - '. $this->endDate, '', '', ''],
            ['', '', '', '', '', '', ''],
            ['', '', '', '', '', '', ''],
            $header,
            ...$dataRows,
            $footer,
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,
            'B' => 15,
            'C' => 40,
            'D' => 30,
            'E' => 50,
            'F' => 30,
            'G' => 30,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 20,
                    'color' => ['rgb' => self::COLORS['PRIMARY_BLUE']],
                    'name' => 'Calibri',
                ],
            ],
            2 => [
                'font' => [
                    'size' => 14,
                    'color' => ['rgb' => self::COLORS['STEEL_BLUE']],
                    'italic' => true,
                ],
            ],
            5 => [
                'font' => [
                    'bold' => true,
                    'size' => 20,
                    'color' => ['rgb' => self::COLORS['ROYAL_BLUE']],
                    'name' => 'Calibri',
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],
            6 => [
                'font' => [
                    'size' => 14,
                    'color' => ['rgb' => self::COLORS['ACCENT_BLUE']],
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $this->applyElegantBlueStyling($sheet);
            },
        ];
    }

    private function applyElegantBlueStyling(Worksheet $sheet)
    {
        $sheet->mergeCells('D5:E5');
        $sheet->mergeCells('D6:E6');
        $sheet->mergeCells('C7:E7');
        $sheet->getStyle('A1:G8')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 45,
                'startColor' => ['rgb' => self::COLORS['POWDER_BLUE']],
                'endColor' => ['rgb' => self::COLORS['SKY_BLUE']],
            ],
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['rgb' => self::COLORS['ACCENT_BLUE']],
                ],
            ],
        ]);
        $headerRow = 9;
        $sheet->getStyle("A{$headerRow}:G{$headerRow}")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 13,
                'color' => ['rgb' => 'FFFFFF'],
                'name' => 'Calibri',
            ],
            'fill' => [
                'fillType' => Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startColor' => ['rgb' => self::COLORS['PRIMARY_BLUE']],
                'endColor' => ['rgb' => self::COLORS['DARK_BLUE']],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['rgb' => self::COLORS['NAVY_BLUE']],
                ],
            ],
        ]);
        $dataStartRow = 10;
        $dataRows = $this->getFormattedData();
        $rawRows = $this->data->values();
        $dataEndRow = $dataStartRow + count($dataRows) - 1;

        for ($i = 0; $i < count($dataRows); $i++) {
            $row = $dataRows[$i];
            $excelRow = $dataStartRow + $i;
            $isEvenRow = ($excelRow - $dataStartRow) % 2 == 0;
            $bgColor = $isEvenRow ? 'FFFFFF' : self::COLORS['LIGHT_BLUE'];

            $sheet->getStyle("A{$excelRow}:G{$excelRow}")->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $bgColor],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => self::COLORS['SECONDARY_BLUE']],
                    ],
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'font' => [
                    'size' => 12,
                    'name' => 'Calibri',
                ],
            ]);
            $sheet->getStyle("E{$excelRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_JUSTIFY);
            $sheet->getStyle("F{$excelRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("G{$excelRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            foreach (['A', 'B', 'C', 'D'] as $col) {
                $sheet->getStyle("{$col}{$excelRow}")->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
            }

            if (!empty($row[5]) && $row[5] !== '—' && (empty($row[6]) || $row[6] === '—')) {
                $sheet->getStyle("F{$excelRow}")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => '16A34A'],
                    ],
                ]);
            }

            if (!empty($row[6]) && $row[6] !== '—' && (empty($row[5]) || $row[5] === '—')) {
                $sheet->getStyle("G{$excelRow}")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'DC2626'],
                    ],
                ]);
            }

            $categoryType = $rawRows[$i]->financeCategory->type ?? null;
            if ($categoryType === 'income') {
                $sheet->getStyle("D{$excelRow}")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => '15803D'],
                        'size' => 11,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'D1FAE5'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'outline' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '15803D'],
                        ],
                    ],
                ]);
            } elseif ($categoryType === 'expense') {
                $sheet->getStyle("D{$excelRow}")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'BE123C'],
                        'size' => 11,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FECACA'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'outline' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'BE123C'],
                        ],
                    ],
                ]);
            }
        }

        for ($row = $dataStartRow; $row <= $dataEndRow; $row++) {
            $sheet->getStyle("E{$row}")->getAlignment()->setWrapText(true);
        }

        $footerRow1 = $dataEndRow + 1;
        $sheet->mergeCells("A{$footerRow1}:E{$footerRow1}");
        $sheet->setCellValue("A{$footerRow1}", 'Total Akumulasi (Pemasukan, Pengeluaran)');
        $sheet->getStyle("A{$footerRow1}:E{$footerRow1}")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 13,
                'color' => ['rgb' => 'FFFFFF'],
                'name' => 'Calibri',
            ],
            'fill' => [
                'fillType' => Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startColor' => ['rgb' => self::COLORS['PRIMARY_BLUE']],
                'endColor' => ['rgb' => self::COLORS['DARK_BLUE']],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['rgb' => self::COLORS['NAVY_BLUE']],
                ],
            ],
        ]);
        $sheet->getStyle("F{$footerRow1}:G{$footerRow1}")->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => self::COLORS['SKY_BLUE']],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['rgb' => self::COLORS['SECONDARY_BLUE']],
                ],
            ],
        ]);
        $sheet->getStyle("F{$footerRow1}")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 13,
                'color' => ['rgb' => '16A34A'],
                'name' => 'Calibri',
            ],
        ]);
        $sheet->getStyle("G{$footerRow1}")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 13,
                'color' => ['rgb' => 'DC2626'],
                'name' => 'Calibri',
            ],
        ]);

        $footerRow2 = $dataEndRow + 2;
        $openingBalanceDate = Carbon::parse($this->startDateRaw)->translatedFormat("F Y");
        $openingBalance = $this->dataAccumulation->opening_balance;
        $sheet->mergeCells("A{$footerRow2}:E{$footerRow2}");
        $sheet->setCellValue("A{$footerRow2}", "Saldo Awal {$openingBalanceDate}");
        $sheet->mergeCells("F{$footerRow2}:G{$footerRow2}");
        $sheet->setCellValue("F{$footerRow2}", 'Rp. ' . number_format($openingBalance, 0, ',', '.'));
        $sheet->getStyle("A{$footerRow2}:E{$footerRow2}")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 13,
                'color' => ['rgb' => 'FFFFFF'],
                'name' => 'Calibri',
            ],
            'fill' => [
                'fillType' => Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startColor' => ['rgb' => self::COLORS['PRIMARY_BLUE']],
                'endColor' => ['rgb' => self::COLORS['DARK_BLUE']],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['rgb' => self::COLORS['NAVY_BLUE']],
                ],
            ],
        ]);
        $sheet->getStyle("F{$footerRow2}:G{$footerRow2}")->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => self::COLORS['SKY_BLUE']],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['rgb' => self::COLORS['SECONDARY_BLUE']],
                ],
            ],
        ]);
        $sheet->getStyle("F{$footerRow2}:G{$footerRow2}")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 13,
                'color' => ['rgb' => self::COLORS['PRIMARY_BLUE']],
                'name' => 'Calibri',
            ],
        ]);

        $footerRow3 = $dataEndRow + 3;
        $closingBalanceDate = Carbon::parse($this->endDateRaw)->translatedFormat("F Y");
        $closingBalance = $this->dataAccumulation->closing_balance;
        $sheet->mergeCells("A{$footerRow3}:E{$footerRow3}");
        $sheet->setCellValue("A{$footerRow3}", "Saldo Akhir {$closingBalanceDate}");
        $sheet->mergeCells("F{$footerRow3}:G{$footerRow3}");
        $sheet->setCellValue("F{$footerRow3}", 'Rp. ' . number_format($closingBalance, 0, ',', '.'));
        $sheet->getStyle("A{$footerRow3}:E{$footerRow3}")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 13,
                'color' => ['rgb' => 'FFFFFF'],
                'name' => 'Calibri',
            ],
            'fill' => [
                'fillType' => Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startColor' => ['rgb' => self::COLORS['PRIMARY_BLUE']],
                'endColor' => ['rgb' => self::COLORS['DARK_BLUE']],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['rgb' => self::COLORS['NAVY_BLUE']],
                ],
            ],
        ]);
        $sheet->getStyle("F{$footerRow3}:G{$footerRow3}")->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => self::COLORS['SKY_BLUE']],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['rgb' => self::COLORS['SECONDARY_BLUE']],
                ],
            ],
        ]);
        $sheet->getStyle("F{$footerRow3}:G{$footerRow3}")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 13,
                'color' => ['rgb' => self::COLORS['PRIMARY_BLUE']],
                'name' => 'Calibri',
            ],
        ]);

        $sheet->setShowGridlines(false);
        $this->setupPageLayout($sheet);


    }

    private function setupPageLayout(Worksheet $sheet)
    {
        $sheet->getPageSetup()
            ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT)
            ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4)
            ->setFitToPage(true)
            ->setFitToWidth(1)
            ->setFitToHeight(0);

        $sheet->getPageMargins()
            ->setTop(1)
            ->setRight(0.75)
            ->setLeft(0.75)
            ->setBottom(1);
    }

    private function getDefaultReportInfo()
    {
        return [
            'name' => 'BAGIAN PERBENDAHARAAN MASJID',
            'export_date' => date('d M Y, H:i')
        ];
    }

    public function getFormattedData()
    {
        $reorderedData = $this->data->map(function($row, $loop) {
            $item = [
                "row_number" => $loop + 1,
                "date" => $row["date"],
                "transaction_code" => $row["transaction_code"],
                "category" => $row->financeCategory->name,
                "description" => $row["description"],
                "income" => $row["income"] ? 'Rp. ' . number_format($row["income"], 0, ',', '.') : "—",
                "expense" => $row["expense"] ? 'Rp. ' . number_format($row["expense"], 0, ',', '.') : "—",
            ];
            $item['__category_type'] = $row->financeCategory->type;
            return $item;
        });

        $mappedData = $reorderedData->map(function($item) {
            return [
                $item['row_number'],
                $item['date'],
                $item['transaction_code'],
                $item['category'],
                $item['description'],
                $item['income'],
                $item['expense'],
            ];
        })->values()->toArray();

        return empty($mappedData) ? [["", "", "", "", "Tidak ada transaksi!", "", ""]] : $mappedData;
    }

    public function drawings() {
        $drawing = new Drawing();
        $drawing->setName('Baitana Logo');
        $drawing->setDescription('Baitana Logo');
        $drawing->setPath(public_path('assets/img/baitana-logo.png'));
        $drawing->setHeight(80);
        $drawing->setOffsetX(offsetX: 120);
        $drawing->setOffsetY(15);
        $drawing->setCoordinates('F1');

        return [$drawing];
    }
}