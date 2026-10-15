@include('header')
@include('sidebar')

<!-- Main Content -->
<main class="main">
    <div class="main-content page-blank">
        <div class="blank-shell">

            <section class="section dashboard-panel dashboard-prduct-panel">

                <h5 class="section-title mb-3">{{ $data['title'] }}</h5>
                <div class="row g-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Sumber Dana</h5>
                            </div>

                            <div class="card-body">
                                @php
                                    $namaBulan = [
                                        1 => 'Januari',
                                        2 => 'Februari',
                                        3 => 'Maret',
                                        4 => 'April',
                                        5 => 'Mei',
                                        6 => 'Juni',
                                        7 => 'Juli',
                                        8 => 'Agustus',
                                        9 => 'September',
                                        10 => 'Oktober',
                                        11 => 'November',
                                        12 => 'Desember',
                                    ];
                                @endphp

                                
                                <ul class="nav nav-pills mb-3" id="pillTabs" role="tablist">
                                    @foreach ($data['sumberDanaLaporan'] as $akun)
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                                                id="tab-akun-{{ $akun->id }}" data-bs-toggle="pill"
                                                data-bs-target="#panel-akun-{{ $akun->id }}" type="button"
                                                role="tab" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                                {{ $akun->name }}
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>

                                <div class="tab-content p-3 rounded" id="pillTabsContent">
                                    @forelse ($data['sumberDanaLaporan'] as $akun)
                                        @php
                                            $daftarTahun = $data['bulanAdaPerSumber'][$akun->id] ?? collect();
                                        @endphp

                                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                            id="panel-akun-{{ $akun->id }}" role="tabpanel"
                                            aria-labelledby="tab-akun-{{ $akun->id }}">
                                            <div class="accordion" id="accordionAkun{{ $akun->id }}">
                                                @forelse ($daftarTahun as $tahun => $bulanTersedia)
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header">
                                                            <button
                                                                class="accordion-button {{ !$loop->first ? 'collapsed' : '' }}"
                                                                type="button" data-bs-toggle="collapse"
                                                                data-bs-target="#tahun{{ $akun->id }}{{ $tahun }}"
                                                                aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                                                aria-controls="tahun{{ $akun->id }}{{ $tahun }}">
                                                                Tahun {{ $tahun }}
                                                            </button>
                                                        </h2>

                                                        <div id="tahun{{ $akun->id }}{{ $tahun }}"
                                                            class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                                                            data-bs-parent="#accordionAkun{{ $akun->id }}">
                                                            <div class="accordion-body">
                                                                <div class="row g-2">
                                                                    @foreach ($namaBulan as $nomor => $nama)
                                                                        <div class="col-6 col-md-2">
                                                                            @if (in_array($nomor, $bulanTersedia))
                                                                                <a href="{{ route('laporan.bos.pdf', [
                                                                                    'tahun' => $tahun,
                                                                                    'bulan' => $nomor,
                                                                                    'akun' => $akun->id,
                                                                                ]) }}"
                                                                                    target="_blank"
                                                                                    class="btn btn-outline-primary w-100">
                                                                                    {{ $nama }}
                                                                                </a>
                                                                            @else
                                                                                <button type="button"
                                                                                    class="btn btn-outline-secondary w-100"
                                                                                    disabled>
                                                                                    {{ $nama }}
                                                                                </button>
                                                                            @endif
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <p class="text-muted mb-0">
                                                        Belum ada transaksi untuk {{ $akun->name }}.
                                                    </p>
                                                @endforelse
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-muted mb-0">
                                            Belum ada sumber dana bertipe Owner atau Kas.
                                        </p>
                                    @endforelse
                                </div>
                                

                            </div>

                            <!--div class="card-body">
                                <ul class="nav nav-pills mb-3" id="pillTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="pill-home-tab" data-bs-toggle="pill"
                                            data-bs-target="#pill-home" type="button" role="tab"
                                            aria-selected="true">Bos</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="pill-profile-tab" data-bs-toggle="pill"
                                            data-bs-target="#pill-profile" type="button" role="tab"
                                            aria-selected="false" tabindex="-1">Kas Kantor</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="pill-contact-tab" data-bs-toggle="pill"
                                            data-bs-target="#pill-contact" type="button" role="tab"
                                            aria-selected="false" tabindex="-1">Kas MCM Listrik</button>
                                    </li>
                                </ul>
                                <div class="tab-content p-3 rounded" id="pillTabsContent">
                                    <div class="tab-pane fade show active" id="pill-home" role="tabpanel"
                                        aria-labelledby="pill-home-tab">
                                        @php
                                            $namaBulan = [
                                                1 => 'Januari',
                                                2 => 'Februari',
                                                3 => 'Maret',
                                                4 => 'April',
                                                5 => 'Mei',
                                                6 => 'Juni',
                                                7 => 'Juli',
                                                8 => 'Agustus',
                                                9 => 'September',
                                                10 => 'Oktober',
                                                11 => 'November',
                                                12 => 'Desember',
                                            ];
                                        @endphp

                                        <div class="accordion" id="accordionLaporan">
                                            @forelse ($data['bulanAdaPerTahun'] as $tahun => $bulanTersedia)
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header">
                                                        <button
                                                            class="accordion-button {{ !$loop->first ? 'collapsed' : '' }}"
                                                            type="button" data-bs-toggle="collapse"
                                                            data-bs-target="#tahun{{ $tahun }}"
                                                            aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                                            aria-controls="tahun{{ $tahun }}">
                                                            Tahun {{ $tahun }}
                                                        </button>
                                                    </h2>

                                                    <div id="tahun{{ $tahun }}"
                                                        class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                                                        data-bs-parent="#accordionLaporan">
                                                        <div class="accordion-body">
                                                            <div class="row g-2">
                                                                @foreach ($namaBulan as $nomor => $nama)
<div class="col-6 col-md-2">
                                                                        @if (in_array($nomor, $bulanTersedia))
<a href="{{ route('laporan.bos.pdf', [
    'tahun' => $tahun,
    'bulan' => $nomor,
]) }}"
                                                                                target="_blank"
                                                                                class="btn btn-outline-primary w-100">
                                                                                {{ $nama }}
                                                                            </a>
@else
<button type="button"
                                                                                class="btn btn-outline-secondary w-100"
                                                                                disabled>
                                                                                {{ $nama }}
                                                                            </button>
@endif
                                                                    </div>
@endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                        @empty
                                                <p class="text-muted">
                                                    Belum ada transaksi Pengeluaran Bos.
                                                </p>
                                            @endforelse
                                        </div>

                                    </div>
                                    <div class="tab-pane fade" id="pill-profile" role="tabpanel"
                                        aria-labelledby="pill-profile-tab">
                                        <p class="mb-0">Profile panel content. Pills work great for horizontal and
                                            vertical layouts alike.</p>
                                    </div>
                                    <div class="tab-pane fade" id="pill-contact" role="tabpanel"
                                        aria-labelledby="pill-contact-tab">
                                        <p class="mb-0">Contact panel content. Choose pills for a softer, more
                                            contemporary look.</p>
                                    </div>
                                </div>
                            </div-->
                        </div>
                    </div>
                </div>


            </section>


        </div>
    </div>


    @include('footer')
