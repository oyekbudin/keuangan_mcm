@include('header')
@include('sidebar')
<!-- Main Content -->
<main class="main">
    <div class="main-content page-blank">
        <div class="blank-shell">
            <section class="section">
                <h5 class="section-title mb-3">{{ $data['title'] }}</h5>
                <div class="row g-4">
                    <div class="col-9">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Tambah Transaksi</h5>
                            </div>
                            <div class="card-body">

                                <form action="{{ route('transaksi.store') }}" method="POST"
                                    enctype="multipart/form-data">

                                    @csrf

                                    <div class="row g-3 align-items-start form-group">
                                        <div class="col-6">

                                            <label for="tanggalTransaksi" class="form-label">
                                                Tanggal Transaksi
                                            </label>

                                            <div class="input-group">
                                                <input type="date" class="form-control" id="tanggalTransaksi"
                                                    name="date" placeholder="Select date" data-picker="date"
                                                    data-default-date="today" value="{{ old('date') }}" required>   
                                                    
                                                    

                                                <span class="input-group-text">
                                                    <i class="bi bi-calendar3"></i>
                                                </span>
                                            </div>

                                        </div>

                                        <div class="col-6">
                                            <label for="sumberDana" class="form-label">
                                                Sumber Dana
                                            </label>

                                            <select class="form-select" id="sumberDana" name="id_sumber_dana" required>
                                                @foreach ($data['sumberDana'] as $sumber)
                                                    <option value="{{ $sumber->id }}"
                                                        {{ $sumber->name == 'Kas Kantor' ? 'selected' : '' }}>
                                                        {{ $sumber->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row g-3 align-items-start form-group">
                                        <div class="col-6">

                                            <label for="tujuanTransaksi" class="form-label">
                                                Tujuan Transaksi
                                            </label>

                                            <select class="form-select" id="tujuanTransaksi" name="id_tujuan_transaksi"
                                                data-choices data-choices-search="true" required>
                                                <option value="">Pilih tujuan transaksi...</option>

                                                @foreach ($data['tujuanTransaksi'] as $tujuan)
                                                    <option value="{{ $tujuan->id }}"
                                                        {{ old('id_tujuan_transaksi') == $tujuan->id ? 'selected' : '' }}>
                                                        {{ $tujuan->name }}
                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>

                                        <div class="col-6">

                                            <label for="nominal" class="form-label">
                                                Nominal
                                            </label>

                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>

                                                <input type="number" class="form-control" id="nominal" name="nominal"
                                                    placeholder="0" value="{{ old('nominal') }}" min="1"
                                                    required>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="row g-3 align-items-start form-group">
                                        <div class="col-6">

                                            <label for="keteranganTransaksi" class="form-label">
                                                Keterangan Transaksi
                                            </label>

                                            <textarea class="form-control" id="keteranganTransaksi" name="keterangan" rows="8" placeholder="Tulis Keterangan"
                                                required>{{ old('keterangan') }}</textarea>

                                        </div>

                                        <div class="col-6">

                                            <label for="buktiTransaksi" class="form-label">
                                                Bukti Transaksi
                                            </label>

                                            <div class="upload-dropzone upload-dropzone-image" id="dropzone2">

                                                <input type="file" id="dropzoneInputBuktiTransaksi" name="bukti"
                                                    accept="image/*" hidden>

                                                <div class="upload-dropzone-content">
                                                    <div class="upload-dropzone-icon">
                                                        <i class="bi bi-image"></i>
                                                    </div>

                                                    <h5 class="upload-dropzone-title">
                                                        Drop images here
                                                    </h5>

                                                    <p class="upload-dropzone-text">
                                                        PNG, JPG, GIF, WebP up to 5MB
                                                    </p>
                                                </div>

                                            </div>

                                        </div>
                                    </div>

                                    <div class="row g-3 align-items-center">
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary">
                                                Simpan Transaksi
                                            </button>
                                        </div>
                                    </div>

                                </form>

                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="row form-group">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Preview Bukti Transaksi</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="upload-image-preview" id="imagePreviewBuktiTransaksi">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Tambah Tujuan Transaksi</h5>
                                    </div>
                                    <div class="card-body">
                                        <!--form action="">
                                            <div class="row g-3 align-items-center form-group">
                                                <div class="col-12">
                                                    <label for="tujuanBaru" class="form-label">Nama Toko / Orang</label>
                                                    <input type="text" class="form-control" id="tujuanBaru"
                                                        placeholder="Enter text...">
                                                </div>
                                            </div>

                                            <div class="row align-items-center form-group">
                                                <div class="col-12">
                                                    <button type="submit" class="btn btn-primary">Tambah
                                                        Tujuan</button>
                                                </div>
                                            </div>
                                        </form-->
                                        <form action="{{ route('tujuan-transaksi.store') }}" method="POST">
                                            @csrf

                                            <div class="row g-3 align-items-center form-group">
                                                <div class="col-12">
                                                    <label for="tujuanBaru" class="form-label">Nama Toko /
                                                        Orang</label>

                                                    <input type="text" class="form-control" id="tujuanBaru"
                                                        name="name" placeholder="Nama toko / orang"
                                                        value="{{ old('name') }}" required>

                                                    @error('name')
                                                        <div class="text-danger mt-1">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="row align-items-center form-group">
                                                <div class="col-12">
                                                    <button type="submit" class="btn btn-primary">
                                                        Tambah Tujuan
                                                    </button>
                                                </div>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </section>
            <section>
                <div class="row g-4">

                    <!-- Data Transaksi -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Data Transaksi</h5>
                            </div>
                            <div class="card-body">

                                <div
                                    class="datatable-wrapper datatable-loading no-footer sortable searchable fixed-columns">
                                    <!--div class="datatable-top">
                                        <div class="datatable-dropdown">
                                            <label>
                                                <select class="datatable-selector" name="per-page">
                                                    <option value="5" selected="">5</option>
                                                    <option value="10">10</option>
                                                    <option value="20">20</option>
                                                    <option value="50">50</option>
                                                </select> {select} entries per page
                                            </label>
                                        </div>
                                        <div class="datatable-search">
                                            <input class="datatable-input" placeholder="Search orders..."
                                                type="search" name="search" title="Search within table"
                                                aria-controls="orderDataTable">
                                        </div>
                                    </div-->
                                    <div class="datatable-container">
                                        <table id="orderDataTable" class="table datatable-table">
                                            <thead>
                                                <tr>
                                                    <th data-sortable="true">TANGGAL</th>
                                                    <th data-sortable="true">URAIAN</th>
                                                    <th data-sortable="true">SUMBER DANA</th>
                                                    <th data-sortable="true">TUJUAN</th>
                                                    <th data-sortable="true">NOMINAL</th>
                                                    <th data-sortable="false">AKSI</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach ($data['transaksi'] as $transaksi)
                                                    <tr>
                                                        <td data-sort="{{ $transaksi->date->format('Y-m-d H:i:s') }}">
                                                            {{ $transaksi->date->format('d/m/Y H:i') }}
                                                        </td>

                                                        <td>
                                                            {{ $transaksi->keterangan }}
                                                        </td>

                                                        <td>
                                                            {{ $transaksi->sumberDana->name }}
                                                        </td>

                                                        <td>
                                                            {{ $transaksi->tujuanTransaksi->name }}
                                                        </td>

                                                        <td data-sort="{{ $transaksi->nominal }}">
                                                            Rp {{ number_format($transaksi->nominal, 0, ',', '.') }}
                                                        </td>

                                                        <td>
                                                            <div class="table-actions">

                                                                @if ($transaksi->bukti)
                                                                    <a href="{{ asset('storage/' . $transaksi->bukti) }}"
                                                                        target="_blank"
                                                                        class="btn btn-icon btn-sm btn-light"
                                                                        title="Lihat Bukti">
                                                                        <i class="bi bi-eye"></i>
                                                                    </a>
                                                                @endif

                                                                <form
                                                                    action="{{ route('transaksi.destroy', $transaksi->id) }}"
                                                                    method="POST" class="d-inline"
                                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?');">
                                                                    @csrf
                                                                    @method('DELETE')

                                                                    <button type="submit"
                                                                        class="btn btn-icon btn-sm btn-light"
                                                                        title="Hapus">
                                                                        <i class="bi bi-trash"></i>
                                                                    </button>
                                                                </form>

                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <!--div class="datatable-bottom">
                                        <div class="datatable-info">Showing 1 to 5 of 12 orders</div>
                                        <nav class="datatable-pagination">
                                            <ul class="datatable-pagination-list">
                                                <li
                                                    class="datatable-pagination-list-item datatable-hidden datatable-disabled">
                                                    <button data-page="1" class="datatable-pagination-list-item-link"
                                                        aria-label="Page 1">‹</button>
                                                </li>
                                                <li class="datatable-pagination-list-item datatable-active"><button
                                                        data-page="1" class="datatable-pagination-list-item-link"
                                                        aria-label="Page 1">1</button></li>
                                                <li class="datatable-pagination-list-item"><button data-page="2"
                                                        class="datatable-pagination-list-item-link"
                                                        aria-label="Page 2">2</button></li>
                                                <li class="datatable-pagination-list-item"><button data-page="3"
                                                        class="datatable-pagination-list-item-link"
                                                        aria-label="Page 3">3</button></li>
                                                <li class="datatable-pagination-list-item"><button data-page="2"
                                                        class="datatable-pagination-list-item-link"
                                                        aria-label="Page 2">›</button></li>
                                            </ul>
                                        </nav>
                                    </div-->
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    @include('footer')
