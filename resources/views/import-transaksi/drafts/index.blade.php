@include('header')
@include('sidebar')
<!-- Main Content -->
<main class="main">
    <div class="main-content page-blank">
        <div class="blank-shell">
            <section class="section">
                <h5 class="section-title mb-3">Draft Rekonsiliasi</h5>
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="row g-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Buat Draft Baru</h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('import-drafts.store') }}" method="POST"
                                    enctype="multipart/form-data">

                                    @csrf

                                    <div class="mb-3">
                                        <label for="name" class="form-label">
                                            Nama Draft
                                        </label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ old('name') }}" placeholder="Contoh: Rekap 1–3 Oktober"
                                            required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="file_excel" class="form-label">
                                            File Excel
                                        </label>
                                        <input type="file" class="form-control" id="file_excel" name="file_excel"
                                            accept=".xlsx,.xls,.csv" required>
                                    </div>

                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-plus-circle me-1"></i>
                                        Buat Draft
                                    </button>
                                </form>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="row g-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Daftar Draft</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover align-middle">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Nama Draft</th>
                                                <th>File Excel</th>
                                                <th>Jumlah Baris</th>
                                                <th>Status</th>
                                                <th>Dibuat</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @forelse ($drafts as $draft)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $draft->name }}</td>
                                                    <td>{{ $draft->original_filename }}</td>
                                                    <td>{{ $draft->total_rows }}</td>
                                                    <td>
                                                        @if ($draft->status === 'selesai')
                                                            <span class="badge bg-success">
                                                                Selesai
                                                            </span>
                                                        @elseif ($draft->status === 'diproses')
                                                            <span class="badge bg-info">
                                                                Diproses
                                                            </span>
                                                        @else
                                                            <span class="badge bg-secondary">
                                                                Draft
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $draft->created_at->format('d/m/Y H:i') }}</td>
                                                    <td>
                                                        <div class="d-flex gap-1">
                                                            <a href="{{ route('import-drafts.show', $draft->id) }}"
                                                                class="btn btn-sm btn-primary">
                                                                <i class="bi bi-folder2-open"></i>
                                                                Buka
                                                            </a>

                                                            @if ($draft->status !== 'selesai')
                                                                <form
                                                                    action="{{ route('import-drafts.destroy', $draft->id) }}"
                                                                    method="POST"
                                                                    onsubmit="return confirm('Yakin ingin menghapus draft ini?')">
                                                                    @csrf
                                                                    @method('DELETE')

                                                                    <button type="submit"
                                                                        class="btn btn-sm btn-danger">
                                                                        <i class="bi bi-trash"></i>
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center text-muted py-4">
                                                        Belum ada draft rekonsiliasi.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </section>
        </div>
    </div>
    @include('footer')

