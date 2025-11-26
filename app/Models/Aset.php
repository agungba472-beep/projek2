<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aset extends Model
{
    protected $table = 'aset';
    protected $primaryKey = 'aset_id';
    public $timestamps = false;

    protected $fillable = [
        'nama',
        'jenis',
        'lokasi',
        'tanggal_peroleh',
        'umur_maksimal',
        'nilai',
        'kondisi',
        'status',
        'tanggal_input',
        'laporan_id'
    ];

    /**
     * Cek apakah aset ini perlu pemutihan
     * (pemutihan = penghapusan aset karena rusak)
     */
    public function cekPemutihan()
    {
        // Jika kondisi != rusak → tidak perlu pemutihan
        if (strtolower($this->kondisi) !== 'rusak') {
            return [
                'perlu_pemutihan' => false,
                'alasan'          => 'Aset masih dalam kondisi baik, tidak termasuk pemutihan.',
                'data_laporan'    => null,
            ];
        }

        // Cek apakah aset ini sudah pernah dipemutihkan di periode ini
        $periode = date('Y-m');

        $pemutihan = LaporanInventarisDetail::where('aset_id', $this->aset_id)
            ->whereHas('laporan', function ($q) use ($periode) {
                $q->where('periode', $periode);
            })
            ->first();

        if ($pemutihan) {
            return [
                'perlu_pemutihan' => false,
                'alasan'          => 'Aset sudah dipemutihkan pada periode ini.',
                'data_laporan'    => $pemutihan,
            ];
        }

        // Jika belum pernah → perlu dipemutihkan
        return [
            'perlu_pemutihan' => true,
            'alasan'          => 'Aset dalam kondisi rusak dan belum dipemutihkan.',
            'data_laporan'    => null,
        ];
    }

    public function laporan()
    {
        return $this->belongsTo(LaporanInventaris::class, 'laporan_id');
    }
    public function maintenance_terakhir()
{
    return $this->hasOne(Maintenance::class, 'aset_id', 'aset_id')
                ->latest('tanggal_dijadwalkan');
}

}
