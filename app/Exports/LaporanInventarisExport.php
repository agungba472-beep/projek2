<?php

namespace App\Exports;

use App\Models\Aset;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanInventarisExport implements FromArray, WithHeadings, WithStyles
{
    protected $id;

    public function __construct($aset_id)
    {
        $this->id = $aset_id;
    }

    /**
     * Data utama untuk dimasukkan ke Excel
     */
    public function array(): array
    {
        $aset = Aset::findOrFail($this->id);

        // Ambil hasil pemutihan dari model Aset
        $pemutihan = $aset->cekPemutihan();

        return [
            [
                $aset->nama,
                $aset->jenis,
                $aset->tanggal_peroleh,
                $aset->umur_maksimal,
                $pemutihan['tanggal_kadaluarsa'] ?? '-',
                $aset->kondisi,
                $pemutihan['status'] ?? '-',
                $pemutihan['pesan'] ?? '-',
            ]
        ];
    }

    /**
     * Header kolom Excel
     */
    public function headings(): array
    {
        return [
            'Nama Aset',
            'Jenis',
            'Tgl Peroleh',
            'Umur Maks (th)',
            'Tgl Kadaluarsa',
            'Kondisi',
            'Status',
            'Catatan',
        ];
    }

    /**
     * Styling Excel supaya rapi
     */
    public function styles(Worksheet $sheet)
    {
        // Header bold
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);

        // Wrap text untuk isi
        $sheet->getStyle('A1:H100')->getAlignment()->setWrapText(true);

        // Auto width tiap kolom
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Border tipis biar rapi
        $sheet->getStyle('A1:H100')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ]
            ]
        ]);

        return [];
    }
}
