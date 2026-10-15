@include('header')
@include('sidebar')

<main class="main">
    <div class="main-content page-blank">
        <div class="blank-shell">
            <section class="section">

                <a href="{{ route('import-drafts.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
                    <i class="bi bi-arrow-left me-1"></i>
                    Kembali
                </a>

                {{-- INFORMASI DRAFT DAN FILTER AKUN --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-1">{{ $draft->name }}</h5>
                        <p class="card-subtitle mb-0">
                            {{ $draft->original_filename }}
                        </p>
                    </div>

                    <div class="card-body">

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form method="GET" action="{{ route('import-drafts.show', $draft) }}">
                            <select name="akun_id" id="akun_id" class="form-select" onchange="this.form.submit()">

                                <option value="" {{ empty($akunId) ? 'selected' : '' }}>
                                    Semua Akun
                                </option>

                                @foreach ($akun as $itemAkun)
                                    <option value="{{ $itemAkun->id }}"
                                        {{ (string) $akunId === (string) $itemAkun->id ? 'selected' : '' }}>
                                        {{ $itemAkun->name }}
                                    </option>
                                @endforeach

                            </select>
                        </form>
                    </div>
                </div>

                {{-- ACCORDION PER TANGGAL --}}
                <div class="card">
                    <div class="card-body">

                        @if ($tanggal->isEmpty())
                            <div class="alert alert-warning mb-0">
                                Belum ada tanggal transaksi yang bisa ditampilkan.
                            </div>
                        @else
                            <div class="accordion" id="accordionDefault">

                                @foreach ($tanggal as $index => $tgl)
                                    @php
                                        $itemsExcel = $itemsPerTanggal->get($tgl, collect());
                                        $itemsDatabase = $transaksiPerTanggal->get($tgl, collect());

                                        $totalExcel = $itemsExcel->where('is_checked', true)->sum('nominal');

                                        $totalDatabase = $itemsDatabase->sum('nominal');

                                        $sesuai = (float) $totalExcel === (float) $totalDatabase;

                                        $collapseId = 'collapseTanggal' . $index;
                                        $headingId = 'headingTanggal' . $index;
                                    @endphp

                                    <div class="accordion-item mb-3 reconciliation-date-card">

                                        <h2 class="accordion-header" id="{{ $headingId }}">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}"
                                                aria-expanded="false" aria-controls="{{ $collapseId }}">

                                                <span class="fw-semibold">
                                                    {{ \Carbon\Carbon::parse($tgl)->translatedFormat('d F Y') }}
                                                </span>

                                                <span
                                                    class="ms-auto me-3 badge rounded-pill reconciliation-date-status {{ $sesuai ? 'text-bg-success' : 'text-bg-danger' }}"
                                                    data-total-database="{{ $totalDatabase }}">
                                                    {{ $sesuai ? 'Nominal Sesuai' : 'Belum Sesuai' }}
                                                </span>

                                            </button>
                                        </h2>

                                        <div id="{{ $collapseId }}" class="accordion-collapse collapse"
                                            aria-labelledby="{{ $headingId }}" data-bs-parent="#accordionDefault">

                                            <div class="accordion-body">
                                                <div class="row g-4">

                                                    {{-- KOLOM EXCEL --}}
                                                    <div class="col-xl-6">

                                                        <h6 class="mb-3">
                                                            Transaksi Excel Admin
                                                        </h6>

                                                        <form class="reconciliation-form"
                                                            action="{{ route('import-drafts.checkboxes.save', $draft) }}"
                                                            method="POST">

                                                            @csrf

                                                            {{-- Pertahankan filter akun utama --}}
                                                            <input type="hidden" name="akun_id"
                                                                value="{{ $akunId }}">

                                                            {{-- RINGKASAN --}}
                                                            <div class="reconciliation-summary mb-3"
                                                                data-database-total="{{ $totalDatabase }}">

                                                                <div
                                                                    class="d-flex justify-content-between align-items-center mb-2">
                                                                    <span class="small text-muted">
                                                                        Transaksi dipilih:
                                                                        <strong class="selected-count">0</strong>
                                                                    </span>

                                                                    <strong class="selected-total">
                                                                        Rp0
                                                                    </strong>
                                                                </div>

                                                                <div class="comparison-warning alert alert-danger py-2 small"
                                                                    style="display:none">
                                                                    Total pilihan melebihi total database.
                                                                </div>

                                                                <div class="comparison-status small"></div>
                                                            </div>

                                                            <div class="d-flex justify-content-end mb-3">
                                                                <button type="submit"
                                                                    class="btn btn-outline-primary btn-sm">
                                                                    <i class="bi bi-save me-1"></i>
                                                                    Simpan Draft
                                                                </button>
                                                            </div>

                                                            {{-- CARD TRANSAKSI EXCEL --}}
                                                            <div class="overflow-y-auto pe-2"
                                                                style="height: calc(100vh - 300px);">
                                                                @foreach ($itemsExcel as $item)
                                                                    <div class="card mb-1 shadow-sm reconciliation-excel-row"
                                                                        data-nominal="{{ (int) $item->nominal }}">

                                                                        <div class="card-body p-3">
                                                                            <div class="d-flex align-items-start gap-3">

                                                                                {{-- CHECKBOX --}}
                                                                                <div class="pt-1">
                                                                                    <input type="hidden"
                                                                                        name="item_ids[]"
                                                                                        value="{{ $item->id }}">

                                                                                    <input type="checkbox"
                                                                                        class="form-check-input reconciliation-checkbox"
                                                                                        name="checked_ids[]"
                                                                                        value="{{ $item->id }}"
                                                                                        data-nominal="{{ (int) $item->nominal }}"
                                                                                        {{ $item->is_checked ? 'checked' : '' }}>
                                                                                </div>

                                                                                {{-- INFORMASI --}}
                                                                                <div class="flex-grow-1">
                                                                                    <div class="row g-1">
                                                                                        <div class="col-6">
                                                                                            <input type="date"
                                                                                                name="items[{{ $item->id }}][tanggal]"
                                                                                                class="form-control form-control-sm excel-edit-field mb-2"
                                                                                                value="{{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('Y-m-d') : '' }}"
                                                                                                readonly>
                                                                                        </div>
                                                                                        <div class="col-6">
                                                                                            <input type="number"
                                                                                                name="items[{{ $item->id }}][nominal]"
                                                                                                class="form-control form-control-sm excel-edit-field nominal-input fw-bold text-danger"
                                                                                                value="{{ (int) $item->nominal }}"
                                                                                                min="0"
                                                                                                step="1" required
                                                                                                readonly>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="row g-1">
                                                                                        <div class="col-6">
                                                                                            <div
                                                                                                class="fw-semibold mb-1">
                                                                                                {{ $item->tujuan ?: '-' }}
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-6">
                                                                                            <div
                                                                                                class="text-secondary small mb-2">
                                                                                                {{ $item->keterangan ?: '-' }}
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>











                                                                                    <!--div class="small text-muted mt-2">
                                                                                    {{ $item->kode_bukti }}
                                                                                </div-->

                                                                                </div>

                                                                                {{-- TOMBOL EDIT --}}
                                                                                <div class="text-nowrap">
                                                                                    <button type="button"
                                                                                        class="btn btn-outline-primary btn-sm edit-row-btn"
                                                                                        title="Edit transaksi">
                                                                                        <i
                                                                                            class="bi bi-pencil-square"></i>
                                                                                    </button>

                                                                                    <button type="button"
                                                                                        class="btn btn-success btn-sm save-row-btn d-none"
                                                                                        title="Selesai mengedit">
                                                                                        <i class="bi bi-check-lg"></i>
                                                                                    </button>
                                                                                </div>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>

                                                            <div class="save-status small mt-2" aria-live="polite">
                                                            </div>

                                                        </form>
                                                    </div>

                                                    {{-- KOLOM DATABASE --}}
                                                    <div class="col-xl-6">

                                                        <h6 class="mb-3">
                                                            Transaksi WhatsApp / Database
                                                        </h6>

                                                        <div class="mb-3">
                                                            Jumlah:
                                                            <strong>{{ $itemsDatabase->count() }}</strong>
                                                            transaksi

                                                            <div class="small text-muted">
                                                                Total:
                                                                <strong>
                                                                    Rp{{ number_format($totalDatabase, 0, ',', '.') }}
                                                                </strong>
                                                            </div>
                                                        </div>

                                                        {{-- CARD TRANSAKSI DATABASE --}}
                                                        <div class="overflow-y-auto pe-2"
                                                            style="height: calc(100vh - 300px);">
                                                            @foreach ($itemsDatabase as $transaksi)
                                                                <div class="card mb-1 shadow-sm reconciliation-database-row"
                                                                    data-nominal="{{ (int) $transaksi->nominal }}">

                                                                    <div class="card-body p-3">
                                                                        <div class="d-flex align-items-start gap-3">

                                                                            <div class="flex-grow-1">
                                                                                <div class="row g-1">
                                                                                    <div class="col-6">
                                                                                        <div
                                                                                            class="text-muted small mb-1">
                                                                                            {{ \Carbon\Carbon::parse($transaksi->date)->translatedFormat('d F Y') }}
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-6">
                                                                                        <div
                                                                                            class="fw-bold text-danger">
                                                                                            Rp{{ number_format($transaksi->nominal ?? 0, 0, ',', '.') }}
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row g-1">
                                                                                    <div class="col-6">
                                                                                        {{-- TUJUAN TRANSAKSI --}}
                                                                                        <div
                                                                                            class="d-flex align-items-start gap-2">
                                                                                            <div class="flex-grow-1">
                                                                                                <div
                                                                                                    class="fw-semibold mb-1 database-tujuan-text">
                                                                                                    {{ $transaksi->tujuanTransaksi?->name ?? '-' }}
                                                                                                </div>

                                                                                                {{-- FORM EDIT TUJUAN --}}
                                                                                                <div
                                                                                                    class="database-edit-field d-none mt-2">
                                                                                                    <select
                                                                                                        class="form-select form-select-sm database-tujuan-input">
                                                                                                        <option
                                                                                                            value="">
                                                                                                            Pilih Tujuan
                                                                                                        </option>

                                                                                                        @foreach ($akunTujuan as $tujuan)
                                                                                                            <option
                                                                                                                value="{{ $tujuan->id }}"
                                                                                                                {{ (string) $transaksi->id_tujuan_transaksi === (string) $tujuan->id ? 'selected' : '' }}>
                                                                                                                {{ $tujuan->name }}
                                                                                                            </option>
                                                                                                        @endforeach
                                                                                                    </select>

                                                                                                    <div
                                                                                                        class="d-flex gap-2 mt-2">
                                                                                                        <button
                                                                                                            type="button"
                                                                                                            class="btn btn-success btn-sm save-database-tujuan-btn"
                                                                                                            data-url="{{ route('transaksi.update-tujuan', $transaksi->id) }}">
                                                                                                            <i
                                                                                                                class="bi bi-check-lg me-1"></i>
                                                                                                            Simpan
                                                                                                        </button>

                                                                                                        <button
                                                                                                            type="button"
                                                                                                            class="btn btn-outline-secondary btn-sm cancel-database-tujuan-btn">
                                                                                                            Batal
                                                                                                        </button>
                                                                                                    </div>

                                                                                                    <div
                                                                                                        class="database-tujuan-status small mt-2">
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>

                                                                                            {{-- TOMBOL EDIT --}}
                                                                                            <button type="button"
                                                                                                class="btn btn-outline-primary btn-sm edit-database-tujuan-btn"
                                                                                                title="Edit tujuan transaksi">
                                                                                                <i
                                                                                                    class="bi bi-pencil-square"></i>
                                                                                            </button>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-6">
                                                                                        <div
                                                                                            class="text-secondary small mb-2 mt-2">
                                                                                            {{ $transaksi->keterangan ?: '-' }}
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-12">
                                                                                        <div
                                                                                            class="small text-muted mt-1">
                                                                                            {{ $transaksi->kode_bukti }}
                                                                                        </div>
                                                                                    </div>
                                                                                </div>











                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>


                                                        @if ($itemsDatabase->isEmpty())
                                                            <div class="alert alert-light border small">
                                                                Tidak ada transaksi database pada tanggal ini
                                                                untuk filter akun yang dipilih.
                                                            </div>
                                                        @endif

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        @endif

                    </div>

                    <div class="card-body justify-content-end mb-3">

                        <button type="button" id="btnLanjutkan" class="btn btn-primary" disabled
                            onclick="window.location.href='{{ route('import-drafts.tujuan', $draft) }}'">
                            Lanjutkan
                            <i class="bi bi-arrow-right ms-1"></i>
                        </button>

                    </div>

                </div>

            </section>
        </div>
    </div>

    <style>
        .reconciliation-excel-row.nominal-match {
            border: 1px solid #198754 !important;
            background-color: #d1e7dd !important;
        }

        .reconciliation-excel-row.nominal-match .card-body {
            background-color: #d1e7dd;
            color: #0f5132;
            border-radius: inherit;
        }

        .reconciliation-excel-row .excel-edit-field[readonly] {
            background-color: #f8f9fa;
        }

        .reconciliation-date-status {
            white-space: nowrap;
        }
    </style>



    @include('footer')
