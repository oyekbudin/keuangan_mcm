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

        @page {
            margin: 1cm 1cm 1cm 2cm;
        }

        .lampiran-page {
            page-break-before: always;
        }

        .lampiran-page:first-of-type {
            page-break-before: auto;
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

        .tabel-gambar td {
            width: 50%;
            height: 13cm;
            padding: 0;
            border: none;
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
    <p style="text-align:center;">Periode {{ $namaBulan }}</p>

    @foreach ($transaksi->chunk(2) as $kelompok)
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
    @endforeach

</body>

</html>
