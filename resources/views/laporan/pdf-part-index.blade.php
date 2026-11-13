
@include('header')
@include('sidebar')

<main class="main">
    <div class="main-content page-blank">
        <div class="blank-shell">
            <section class="section dashboard-panel">

                <h5 class="section-title mb-3">
                    {{ $title }}
                </h5>

                <div class="card">
                    <div class="card-body">

                        <p class="mb-1">
                            Sumber Dana: <strong>{{ $akun->name }}</strong>
                        </p>

                        <p class="mb-3">
                            Periode: <strong>{{ $namaBulan }}</strong>
                        </p>

                        <div class="alert alert-info">
                            Tersedia {{ $jumlahPart }} bagian PDF.
                            Setiap bagian maksimal 20 halaman.
                        </div>

                        <div class="d-flex flex-wrap gap-2">
                            @for ($part = 1; $part <= $jumlahPart; $part++)
                                @php
                                    $routeDownload = $jenis === 'transaksi'
                                        ? 'laporan.bos.transaksi.download'
                                        : 'laporan.bos.lampiran.download';
                                @endphp

                                <a href="{{ route($routeDownload, [
                                    'tahun' => $tahun,
                                    'bulan' => $bulan,
                                    'part' => $part,
                                    'akun' => $akun->id,
                                ]) }}"
                                    target="_blank"
                                    class="btn btn-outline-danger">
                                    <i class="bi bi-file-pdf"></i>
                                    Part {{ $part }}
                                </a>
                            @endfor
                        </div>

                        <hr>

                        <a href="{{ route('laporan.index') }}"
                            class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i>
                            Kembali
                        </a>

                    </div>
                </div>

            </section>
        </div>
    </div>
</main>

@include('footer')
