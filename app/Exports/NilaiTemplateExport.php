<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class NilaiTemplateExport implements FromCollection, WithHeadings, WithStyles, WithColumnFormatting
{
    protected $mahasiswaList;

    public function __construct($mahasiswaList)
    {
        $this->mahasiswaList = $mahasiswaList;
    }

    public function collection()
    {
        return collect($this->mahasiswaList);
    }

    public function headings(): array
    {
        return [
            'nrp',
            'nama',
            'nilai_angka',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_NUMBER,
        ];
    }
}
