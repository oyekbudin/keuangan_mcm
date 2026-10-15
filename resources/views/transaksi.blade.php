@include('header')
@include('sidebar')

<!-- Main Content -->
<main class="main">
    <div class="main-content page-blank">
        <div class="blank-shell">

            <section class="section">

                <h5 class="section-title mb-3">{{ $data['title'] }}</h5>

                <div class="row g-4">

                    <!-- Form Transaksi -->
                    <div class="col-6 col-md-9">

                        <div class="card">

                            <div class="card-header">
                                <h5 class="card-title">Tambah Transaksi</h5>
                            </div>

                            <div class="card-body">

                                <form action="{{ route('transaksi.store') }}" method="POST"
                                    enctype="multipart/form-data">

                                    @csrf

                                    <!-- Tanggal & Sumber Dana -->
                                    <div class="row g-3 align-items-start form-group">

                                        <div class="col-12">

                                            <label for="tanggalTransaksi" class="form-label">
                                                Tanggal Transaksi
                                            </label>

                                            <div class="input-group">

                                                <input type="date" class="form-control" id="tanggalTransaksi"
                                                    name="date" value="{{ old('date', now()->format('Y-m-d')) }}"
                                                    required>

                                                <span class="input-group-text">
                                                    <i class="bi bi-calendar3"></i>
                                                </span>

                                            </div>

                                            @error('date')
                                                <div class="text-danger mt-1">
                                                    {{ $message }}
                                                </div>
                                            @enderror

                                        </div>
                                    </div>
                                    <div class="row g-3 align-items-start form-group">

                                        <div class="col-12">
                                            <label for="sumberDana" class="form-label">
                                                Sumber Dana
                                            </label>

                                            <select class="form-select" id="sumberDana" name="id_sumber_dana"
                                                data-choices required>

                                                @foreach ($data['akun'] as $akun)
                                                    @if (in_array($akun->jenis_akun, ['Owner', 'Kas']))
                                                        @php
                                                            $saldo =
                                                                collect($data['saldoKas'])->firstWhere('id', $akun->id)
                                                                    ?->saldo ?? 0;
                                                        @endphp

                                                        <option value="{{ $akun->id }}"
                                                            data-jenis="{{ $akun->jenis_akun }}"
                                                            data-saldo="{{ $saldo }}"
                                                            {{ old('id_sumber_dana', $akun->name == 'Kas Kantor' ? $akun->id : '') == $akun->id ? 'selected' : '' }}>
                                                            {{ $akun->name }}
                                                        </option>
                                                    @endif
                                                @endforeach

                                            </select>

                                            <div id="infoSaldo" class="form-text mt-2 text-success"
                                                style="display: none;">
                                                Saldo tersedia: <strong id="nominalSaldo">Rp0</strong>
                                            </div>

                                            @error('id_sumber_dana')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                    </div>


                                    <!-- Tujuan & Nominal -->
                                    <div class="row g-3 align-items-start form-group">

                                        <div class="col-12">

                                            <label for="tujuanTransaksi" class="form-label">
                                                Tujuan Transaksi
                                            </label>

                                            <select class="form-select" id="tujuanTransaksi" name="id_tujuan_transaksi"
                                                data-choices data-choices-search="true" required>

                                                <option value="">
                                                    Pilih tujuan transaksi...
                                                </option>

                                                @foreach ($data['akun'] as $akun)
                                                    <option value="{{ $akun->id }}"
                                                        {{ old('id_tujuan_transaksi') == $akun->id ? 'selected' : '' }}>
                                                        {{ $akun->name }}
                                                    </option>
                                                @endforeach

                                            </select>

                                            @error('id_tujuan_transaksi')
                                                <div class="text-danger mt-1">
                                                    {{ $message }}
                                                </div>
                                            @enderror

                                        </div>
                                    </div>
                                    <div class="row g-3 align-items-start form-group">


                                        <div class="col-12">
                                            <label for="nominal" class="form-label">
                                                Nominal
                                            </label>

                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>

                                                <input type="number" class="form-control" id="nominal" name="nominal"
                                                    placeholder="0" value="{{ old('nominal') }}" min="1"
                                                    step="1" required
                                                    oninvalid="this.setCustomValidity(
    this.validity.valueMissing
        ? 'Nominal wajib diisi.'
        : this.validity.rangeUnderflow
            ? 'Nominal minimal Rp1.'
            : this.validity.stepMismatch
                ? 'Nominal harus berupa angka bulat.'
                : ''
)"
                                                    oninput="this.setCustomValidity('')">
                                            </div>

                                            <div id="errorSaldo" class="text-danger mt-1" style="display: none;">
                                                Nominal tidak boleh melebihi saldo tersedia.
                                            </div>

                                            @error('nominal')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                    </div>


                                    <!-- Keterangan & Bukti -->
                                    <div class="row g-3 align-items-start form-group">

                                        <div class="col-12">

                                            <label for="keteranganTransaksi" class="form-label">
                                                Keterangan Transaksi
                                            </label>

                                            <textarea class="form-control" id="keteranganTransaksi" name="keterangan" rows="1" placeholder="Tulis Keterangan"
                                                required>{{ old('keterangan') }}</textarea>

                                            @error('keterangan')
                                                <div class="text-danger mt-1">
                                                    {{ $message }}
                                                </div>
                                            @enderror

                                        </div>
                                    </div>
                                    <div class="row g-3 align-items-start form-group">


                                        <div class="col-12">

                                            <label for="buktiTransaksi" class="form-label">
                                                Bukti Transfer
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

                                            @error('bukti')
                                                <div class="text-danger mt-1">
                                                    {{ $message }}
                                                </div>
                                            @enderror

                                        </div>
                                        <div class="col-12">

                                            <label for="dropzoneInputNota" class="form-label">
                                                Nota / Kwitansi
                                            </label>

                                            <div class="upload-dropzone upload-dropzone-image" id="dropzoneNota">

                                                <input type="file" id="dropzoneInputNota" name="bukti_nota"
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

                                            @error('bukti_nota')
                                                <div class="text-danger mt-1">
                                                    {{ $message }}
                                                </div>
                                            @enderror

                                        </div>

                                    </div>


                                    <!-- Submit -->
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


                    <!-- Sidebar Kanan -->
                    <div class="col-6 col-md-3">
                        <div class="row form-group">

                            <div class="col-12">

                                <div class="card">

                                    <div class="card-header">
                                        <span class="card-title">
                                            Tujuan Transaksi atau Sumber Dana belum ada?
                                        </span>
                                    </div>

                                    <div class="card-body">

                                        <form action="{{ route('akun.store') }}" method="POST">

                                            @csrf

                                            <div class="row g-3 align-items-center form-group">

                                                <div class="col-12">

                                                    <!--label for="namaAkun" class="form-label">
                                                        Nama
                                                    </label-->

                                                    <input type="text" class="form-control" id="namaAkun"
                                                        name="name" placeholder="Nama toko / orang / kas"
                                                        value="{{ old('name') }}" required>

                                                    @error('name')
                                                        <div class="text-danger mt-1">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror

                                                </div>

                                            </div>


                                            <div class="row g-3 align-items-center form-group">

                                                <div class="col-12">

                                                    <!--label for="jenisAkun" class="form-label">
                                                        Pilih Tipe
                                                    </label-->

                                                    <select class="form-select" id="jenisAkun" name="jenis_akun"
                                                        required>

                                                        <option value="">
                                                            Pilih Kategori
                                                        </option>

                                                        <option value="Kas"
                                                            {{ old('jenis_akun') == 'Kas' ? 'selected' : '' }}>
                                                            Kas / Sumber Dana
                                                        </option>

                                                        <option value="Toko"
                                                            {{ old('jenis_akun') == 'Toko' ? 'selected' : '' }}>
                                                            Toko
                                                        </option>

                                                        <option value="Perorangan"
                                                            {{ old('jenis_akun') == 'Perorangan' ? 'selected' : '' }}>
                                                            Perorangan
                                                        </option>

                                                    </select>

                                                    @error('jenis_akun')
                                                        <div class="text-danger mt-1">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror

                                                </div>

                                            </div>


                                            <div class="row align-items-center form-group">

                                                <div class="col-12">

                                                    <button type="submit" class="btn btn-primary">
                                                        Tambahkan
                                                    </button>

                                                </div>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <!-- Preview Bukti -->
                        <div class="row form-group">

                            <div class="col-12">

                                <div class="card">

                                    <div class="card-header">
                                        <h5 class="card-title">
                                            Preview Bukti Transaksi
                                        </h5>
                                    </div>

                                    <div class="card-body">

                                        <div class="upload-image-preview" id="imagePreviewBuktiTransaksi">

                                        </div>

                                    </div>

                                    <div class="card-body">

                                        <div class="upload-image-preview" id="imagePreviewNota">
                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>




                    </div>

                </div>

            </section>

            <section>
                <div class="row g-4">
                    <div class="col-6">
                        <div class="row form-group">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Pengeluaran Bos</h5>
                                    </div>
                                    <div class="card-body">
                                        <div
                                            class="datatable-wrapper datatable-loading no-footer sortable searchable fixed-columns">

                                            <div class="datatable-container">


                                                <table id="pengeluaranBosTable" class="table datatable-table">
                                                    <tbody>
                                                        <tr>
                                                            <th>Total Pengeluaran Bos</th>
                                                            <th>
                                                                Rp
                                                                {{ number_format($data['pengeluaranBos'], 0, ',', '.') }}
                                                            </th>
                                                        </tr>
                                                    </tbody>
                                                </table>



                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-12">

                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Saldo Kas</h5>
                                    </div>
                                    <div class="card-body">
                                        <div
                                            class="datatable-wrapper datatable-loading no-footer sortable searchable fixed-columns">

                                            <div class="datatable-container">

                                                <table id="saldoKasTable" class="table datatable-table">

                                                    <tbody>
                                                        @foreach ($data['saldoKas'] as $akun)
                                                            <tr>
                                                                <td>{{ $akun->name }}</td>
                                                                <td>
                                                                    Rp
                                                                    {{ number_format($akun->saldo, 0, ',', '.') }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>

                                                </table>

                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>


                    </div>




                    <div class="col-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Data Toko</h5>
                            </div>
                            <div class="card-body">
                                <div
                                    class="datatable-wrapper datatable-loading no-footer sortable searchable fixed-columns">

                                    <div class="datatable-container table-responsive"
                                        style="max-height: 250px; overflow-y: auto;">

                                        <table id="dataTokoTable" class="table table-sm">

                                            <tbody>
                                                @foreach ($data['dataToko'] as $akun)
                                                    <tr>
                                                        <td>{{ $akun->name }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>

                                        </table>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>

        </div>
        </section>


        <!-- Data Transaksi -->
        <section>

            <div class="row g-4">

                <div class="col-12">

                    <div class="card">

                        <div class="card-header">
                            <h5 class="card-title">
                                Data Transaksi
                            </h5>

                            @php
                                $filter = request('filter', 'semua');
                            @endphp

                            <div class="dropdown">
                                <button class="btn btn-sm btn-light" data-bs-toggle="dropdown" aria-expanded="false">
                                    @if ($filter === 'semua')
                                        Semua Transaksi
                                    @else
                                        {{ $data['filterAkun']->firstWhere('id', (int) $filter)?->name ?? 'Semua Transaksi' }}
                                    @endif

                                    <i class="bi bi-chevron-down ms-1"></i>
                                </button>

                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item {{ $filter === 'semua' ? 'active' : '' }}"
                                            href="{{ url()->current() }}?filter=semua">
                                            Semua Transaksi
                                        </a>
                                    </li>

                                    @foreach ($data['filterAkun'] as $akun)
                                        <li>
                                            <a class="dropdown-item {{ (string) $filter === (string) $akun->id ? 'active' : '' }}"
                                                href="{{ url()->current() }}?filter={{ $akun->id }}">
                                                {{ $akun->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>


                        </div>

                        <div class="card-body">

                            <div
                                class="datatable-wrapper datatable-loading no-footer sortable searchable fixed-columns">


                                <div class="datatable-container table-responsive"
                                    style="max-height: 70vh; overflow-y: auto;">

                                    <table id="orderDataTable" class="table datatable-table">

                                        <thead>

                                            <tr>

                                                <th data-sortable="true">
                                                    TANGGAL
                                                </th>

                                                <th data-sortable="true">
                                                    URAIAN
                                                </th>

                                                <th data-sortable="true">
                                                    SUMBER DANA
                                                </th>

                                                <th data-sortable="true">
                                                    TUJUAN
                                                </th>

                                                <th data-sortable="true">
                                                    NOMINAL
                                                </th>

                                                <th data-sortable="false">
                                                    AKSI
                                                </th>

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
                                                        @if ($filter !== 'semua' && $transaksi->id_tujuan_transaksi == (int) $filter)
                                                            <span class="text-success">
                                                                + Rp
                                                                {{ number_format($transaksi->nominal, 0, ',', '.') }}
                                                            </span>
                                                        @elseif ($filter !== 'semua' && $transaksi->id_sumber_dana == (int) $filter)
                                                            <span class="text-danger">
                                                                - Rp
                                                                {{ number_format($transaksi->nominal, 0, ',', '.') }}
                                                            </span>
                                                        @else
                                                            Rp {{ number_format($transaksi->nominal, 0, ',', '.') }}
                                                        @endif
                                                    </td>






                                                    <td>

                                                        <div class="table-actions">

                                                            @if ($transaksi->bukti)
                                                                <a href="{{ asset('storage/' . $transaksi->bukti) }}"
                                                                    target="_blank"
                                                                    class="btn btn-icon btn-sm btn-light"
                                                                    title="Lihat Bukti Transfer">
                                                                    <i class="bi bi-eye"></i>
                                                                </a>
                                                            @endif

                                                            @if ($transaksi->bukti_nota)
                                                                <a href="{{ asset('storage/' . $transaksi->bukti_nota) }}"
                                                                    target="_blank"
                                                                    class="btn btn-icon btn-sm btn-light"
                                                                    title="Lihat Nota / Kwitansi">
                                                                    <i class="bi bi-receipt"></i>
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

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>

    </div>
    </div>

    @include('footer')
