<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanInventaris extends Model
{public static function cekPemutihan($aset)
{
    // Jika data tidak lengkap → tidak masuk daftar pemutihan
    if (!$aset->tanggal_peroleh || !$aset->umur_maksimal) {
        return null;
    }

    // Hitung tanggal expired
    $tanggal_kadaluarsa = date(
        'Y-m-d',
        strtotime("+{$aset->umur_maksimal} years", strtotime($aset->tanggal_peroleh))
    );

    // Jika sudah kadaluarsa → masukkan dalam hasil
    if ($tanggal_kadaluarsa < date('Y-m-d')) {
        return [
            'tanggal_kadaluarsa' => $tanggal_kadaluarsa,
            'umur_maksimal' => $aset->umur_maksimal,
            'tanggal_peroleh' => $aset->tanggal_peroleh,
            'nama' => $aset->nama,
            'jenis' => $aset->jenis
        ];
    }

    return null;
}
}