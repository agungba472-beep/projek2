<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table, th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background: #f0f0f0;
        }

        h2 {
            margin-bottom: 0;
        }
    </style>
</head>
<body>

    <h2>Laporan Detail Aset</h2>
    <p>Tanggal Export: {{ date('d-m-Y') }}</p>

    <h3>Informasi Aset</h3>
    <table>
        <tr>
            <th>Nama Aset</th>
            <td>{{ $aset->nama }}</td>
        </tr>
        <tr>
            <th>Jenis</th>
            <td>{{ $aset->jenis }}</td>
        </tr>
        <tr>
            <th>Tanggal Peroleh</th>
            <td>{{ $aset->tanggal_peroleh ?? '-' }}</td>
        </tr>
        <tr>
            <th>Umur Maksimal</th>
            <td>{{ $aset->umur_maksimal ? $aset->umur_maksimal.' Tahun' : '-' }}</td>
        </tr>
    </table>

    <h3>Informasi Pemutihan</h3>
    @if ($pemutihan)
    <table>
        <tr>
            <th>Tanggal Kadaluarsa</th>
            <td>{{ $pemutihan['tanggal_kadaluarsa'] }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>Aset Melewati Umur Maksimal</td>
        </tr>
    </table>
    @else
    <p><strong>Aset ini belum memasuki masa pemutihan.</strong></p>
    @endif

</body>
</html>