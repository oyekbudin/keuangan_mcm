@include('header')
@include('sidebar')
<!-- Main Content -->
<main class="main">
    <div class="main-content page-blank">
        <div class="blank-shell">
            <section class="section">
                <h5 class="section-title mb-3">Import Transaksi</h5>
                <div class="row g-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Unggah Data Transaksi</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <a href="{{ route('import-transaksi.template') }}" class="btn btn-success mb-3">
                                            <i class="bi bi-file-earmark-excel me-1"></i>
                                            Unduh Template Excel
                                        </a>

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="alert alert-info">
                                            Unggah file Excel transaksi dan file ZIP yang berisi
                                            gambar bukti transfer serta nota.
                                        </div>

                                        <form action="{{ route('import-transaksi.preview') }}" method="POST"
                                            enctype="multipart/form-data">

                                            @csrf

                                            <div class="mb-3">
                                                <label for="fileExcel" class="form-label">File Excel</label>
                                                <input type="file" class="form-control" id="fileExcel"
                                                    name="file_excel" accept=".xlsx,.xls,.csv" required>

                                                @error('file_excel')
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="fileBukti" class="form-label">
                                                    File ZIP Bukti Transaksi
                                                </label>

                                                <input type="file" class="form-control" id="fileBukti"
                                                    name="file_bukti" accept=".zip" required>

                                                @error('file_bukti')
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-search me-1"></i>
                                                Periksa Data
                                            </button>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    @include('footer')
