@include('header')
@include('sidebar')

<!-- Main Content -->
<main class="main">
    <div class="main-content page-blank">
        <div class="blank-shell">
            <section class="section">

                <a href="{{ route('import-drafts.show', $draft) }}" class="btn btn-outline-secondary mb-3">
                    Kembali
                </a>

                <div class="row g-4">
                    <div class="col-12">
                        <div class="card">

                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    Penentuan Tujuan Transaksi
                                </h5>
                                <small class="text-muted">
                                    Tujuan direkomendasikan berdasarkan uraian
                                    yang sama pada transaksi sebelumnya.
                                </small>
                            </div>

                            @if (session('success'))
                                <div class="alert alert-success m-3 mb-0">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger m-3 mb-0">
                                    Periksa kembali pilihan tujuan transaksi.
                                </div>
                            @endif

                            <div class="card-body">
                                <form method="POST" action="{{ route('import-drafts.tujuan.simpan', $draft) }}"
                                    id="formTujuan"
                                    data-autosave-url="{{ route('import-drafts.tujuan.autosave', $draft) }}">
                                    @csrf

                                    @forelse ($kelompok as $index => $item)
                                        <div class="alert {{ $item['sudah_ada_tujuan'] ? 'alert-outline-success' : 'alert-outline-danger' }}"
                                            role="alert">

                                            <div class="row g-1">
                                                <div class="col-4">
                                                    <div class="fw-semibold">
                                                        {{ $item['uraian'] ?: '(Tanpa uraian)' }}
                                                    </div>


                                                    <small class="text-muted">
                                                        {{ $item['jumlah'] }} transaksi
                                                    </small>
                                                </div>
                                                <div class="col-4">


                                                    <select class="form-select sumber-dana-select"
                                                        data-index="{{ $index }}" required>
                                                        <option value="">Pilih sumber dana</option>

                                                        @foreach ($sumberDana as $akun)
                                                            <option value="{{ $akun->id }}"
                                                                @selected((string) ($item['id_sumber_dana'] ?? '') === (string) $akun->id)>
                                                                {{ $akun->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                    <small class="sumber-dana-status text-muted"></small>
                                                </div>


                                                @foreach ($item['item_ids'] as $itemId)
                                                    <input type="hidden" name="item_ids[{{ $index }}][]"
                                                        value="{{ $itemId }}">
                                                @endforeach


                                                <div class="col-4">
                                                    <select class="form-select akun-select"
                                                        name="tujuan[{{ $index }}]"
                                                        data-index="{{ $index }}" required data-choices
                                                        data-choices-search="true">
                                                        <option value="">Pilih tujuan transaksi</option>

                                                        @foreach ($akunTujuan as $akun)
                                                            <option value="{{ $akun->id }}"
                                                                @selected((string) $item['id_tujuan_transaksi'] === (string) $akun->id)>
                                                                {{ $akun->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                    <small class="autosave-status text-muted"></small>
                                                </div>


                                            </div>




                                        </div>
                                    @empty
                                        <div class="alert alert-info">
                                            Tidak ada transaksi yang belum diceklis.
                                        </div>
                                    @endforelse
                                </form>
                                @if ($kelompok->isNotEmpty())
                                    <div class="d-flex justify-content-end mb-4">

                                        <div class="d-flex justify-content-end mt-3">
                                            
                                            <form action="{{ route('import-drafts.simpan-transaksi', $draft) }}"
                                                method="POST" id="formSimpanTransaksi">
                                                @csrf

                                                <button type="submit" id="btnImport" class="btn btn-primary">
                                                    Simpan ke Transaksi
                                                </button>
                                            </form>
                                            
                                        </div>

                                    </div>
                                @endif


                            </div>

                        </div>
                    </div>
                </div>

            </section>
        </div>
    </div>

    @include('footer')
