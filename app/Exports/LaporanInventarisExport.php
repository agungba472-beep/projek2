<?php

namespace App\Exports;

use App\Models\Aset;
use App\Models\LaporanInventaris;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanInventarisExport implements FromArray, WithStyles, WithColumnWidths, WithHeadings
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function headings(): array
    {
        return [
            ['Laporan Inventaris Aset'],      // Judul
            [],                               // Spasi
            ['Informasi Aset'],               // Subjudul
            ['Nama Aset', 'Jenis', 'Tanggal Peroleh', 'Umur Maksimal (Tahun)'],
        ];
    }

    public function array(): array
    {
        $aset = Aset::findOrFail($this->id);
        $pemutihan = LaporanInventaris::cekPemutihan($aset);

        $data = [
            [
                $aset->nama,
                $aset->jenis,
                $aset->tanggal_peroleh ?? '-',
                $aset->umur_maksimal ?? '-',
            ],
            [], // spasi
            ['Informasi Pemutihan'],
        ];

        // Jika masuk pemutihan
        if ($pemutihan) {
            $data[] = ['Tanggal Kadaluarsa', $pemutihan['tanggal_kadaluarsa']];
            $data[] = ['Status', 'Aset Melewati Umur Maksimal'];
        } else {
            $data[] = ['Status', 'Aset Belum Memasuki Masa Pemutihan'];
        }

        return $data;
    }

    public function styles(Worksheet $sheet)
    {
        // Judul (A1)
        $sheet->mergeCells('A1:D1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

        // Subjudul Informasi Aset
        $sheet->mergeCells('A3:D3');
        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal('left');

        // Header table aset
        $sheet->getStyle('A4:D4')->getFont()->setBold(true);
        $sheet->getStyle('A4:D4')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A4:D4')->getBorders()->getAllBorders()->setBorderStyle('thin');

        // Data aset
        $sheet->getStyle('A5:D5')->getBorders()->getAllBorders()->setBorderStyle('thin');

        // Subjudul Pemutihan
        $sheet->getStyle('A7')->getFont()->setBold(true);

        // Border area pemutihan (A8:B9)
        $sheet->getStyle('A8:B12')->getBorders()->getAllBorders()->setBorderStyle('thin');

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 25,
            'C' => 25,
            'D' => 25,
        ];
    }
}
