<!DOCTYPE html>

<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
        }

        h2,
        p {
            text-align: center;
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 7px;
        }

        th {
            background-color: #eee;
            text-align: center;
        }

        .nominal {
            text-align: right;
        }

        @page {
            margin: 1cm 1cm 1cm 2cm;
        }

        .lampiran-page {
            page-break-before: always;
        }

        .lampiran-item {
            height: 13cm;
            margin: 0;
            padding: 0;
            page-break-inside: avoid;
        }

        .kode-bukti {
            font-size: 11px;
            font-weight: bold;
            margin: 0 0 2px 0;
            padding: 0;
        }

        .tabel-gambar {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin: 0;
            padding: 0;
        }

        .tabel-gambar th,
        .tabel-gambar td {
            width: 50%;
            text-align: center;
            vertical-align: middle;
            padding: 2px;
            border: none;
        }

        .tabel-gambar td {
            height: 13cm;
            padding: 0;
            vertical-align: middle;
            text-align: center;
        }

        .tabel-gambar img {
            width: auto;
            height: auto;
            max-width: 8.5cm;
            max-height: 13cm;
        }
    </style>


</head>

<body>
    <h2>{{ $title }}</h2>
    <p>Periode {{ $namaBulan }}</p>



    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Tanggal</th>
                <th>Kode Bukti</th>
                <th>Uraian</th>

                @if ($akun->jenis_akun === 'Owner')
                    <th>Pengeluaran (Rp)</th>
                @else
                    <th>Masuk (Rp)</th>
                    <th>Keluar (Rp)</th>
                    <th>Saldo (Rp)</th>
                @endif
            </tr>
        </thead>

        <tbody>
            @forelse ($transaksi as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                    <td>{{ $item->kode_bukti ?? '-' }}</td>
                    <td>{{ $item->keterangan }}</td>

                    @if ($akun->jenis_akun === 'Owner')
                        <td class="nominal">
                            {{ number_format($item->nominal, 0, ',', '.') }}
                        </td>
                    @else
                        <td class="nominal">
                            {{ $item->id_tujuan_transaksi == $akun->id ? number_format($item->nominal, 0, ',', '.') : '-' }}
                        </td>
                        <td class="nominal">
                            {{ $item->id_sumber_dana == $akun->id ? number_format($item->nominal, 0, ',', '.') : '-' }}
                        </td>
                        <td class="nominal">
                            {{ number_format($item->saldo_berjalan, 0, ',', '.') }}
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $akun->jenis_akun === 'Owner' ? 5 : 7 }}" class="text-center">
                        Tidak ada transaksi.
                    </td>
                </tr>
            @endforelse
        </tbody>

        <tfoot>
            @if ($akun->jenis_akun === 'Owner')
                <tr>
                    <th colspan="4">Total Pengeluaran</th>
                    <th class="nominal">
                        {{ number_format($totalKeluar, 0, ',', '.') }}
                    </th>
                </tr>
            @else
                <tr>
                    <th colspan="4">Total</th>
                    <th class="nominal">
                        {{ number_format($totalMasuk, 0, ',', '.') }}
                    </th>
                    <th class="nominal">
                        {{ number_format($totalKeluar, 0, ',', '.') }}
                    </th>
                    <th class="nominal">
                        {{ number_format($saldoAkhir, 0, ',', '.') }}
                    </th>
                </tr>
            @endif
        </tfoot>
    </table>


    {{-- LAMPIRAN BUKTI --}}
    @foreach ($transaksi->chunk(2) as $kelompok)
        @php
            $adaGambar = $kelompok->contains(function ($item) {
                return ($item->bukti && file_exists(storage_path('app/public/' . $item->bukti))) ||
                    ($item->bukti_nota && file_exists(storage_path('app/public/' . $item->bukti_nota)));
            });
        @endphp

        @if ($adaGambar)
            <div class="lampiran-page">
                @foreach ($kelompok as $item)
                    <div class="lampiran-item">
                        <div class="kode-bukti">
                            {{ $item->kode_bukti ?? '-' }}
                        </div>

                        <table class="tabel-gambar">
                            <tbody>
                                <tr>
                                    <td>
                                        @if ($item->bukti && file_exists(storage_path('app/public/' . $item->bukti)))
                                            <img src="{{ storage_path('app/public/' . $item->bukti) }}">
                                        @endif
                                    </td>

                                    <td>
                                        @if ($item->bukti_nota && file_exists(storage_path('app/public/' . $item->bukti_nota)))
                                            <img src="{{ storage_path('app/public/' . $item->bukti_nota) }}">
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @endforeach
            </div>
        @endif
    @endforeach





</body>

</html>
