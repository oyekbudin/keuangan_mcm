@include('header')
@include('sidebar')
<!-- Main Content -->
<main class="main">
    <div class="main-content page-blank">
        <div class="blank-shell">
            <section class="section">
                <h5 class="section-title mb-3">
                    <a href="{{ route('import-transaksi.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>
                        Kembali
                    </a>
                </h5>
                <div class="row g-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Pratinjau Import Transaksi</h5>
                            </div>
                            <div class="card-body">
                                @php
                                    $jumlahValid = collect($hasil)->where('errors', [])->count();
                                    $jumlahError = count($hasil) - $jumlahValid;
                                @endphp

                                <div class="widget-banner-alert success mb-4">
                                    <div class="widget-banner-alert-icon">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </div>
                                    <div class="widget-banner-alert-content">
                                        <h6 class="widget-banner-alert-title">Pratinjau Data Import</h6>
                                        <p class="widget-banner-alert-text">Anda memiliki <span
                                                class="text-success">{{ $jumlahValid }}</span> dari <span
                                                class="text-success">{{ count($hasil) }}</span> data valid. Adapun data
                                            yang error berjumlah <span class="text-danger">{{ $jumlahError }}</span>.
                                        </p>
                                    </div>
                                </div>


                                <div class="table-responsive mb-4">
                                    <table class="table table-bordered table-hover align-middle">
                                        <thead>
                                            <tr>
                                                <th>Baris</th>
                                                <th>Tanggal</th>
                                                <th>Kode Bukti</th>
                                                <th>Sumber Dana</th>
                                                <th>Tujuan</th>
                                                <th>Nominal</th>
                                                <th>Transfer</th>
                                                <th>Nota</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @forelse ($hasil as $item)
                                                <tr>
                                                    <td>{{ $item['baris'] }}</td>
                                                    <td>{{ $item['tanggal'] ?? '-' }}</td>
                                                    <td>{{ $item['kode_bukti'] }}</td>
                                                    <td>{{ $item['sumber_dana'] }}</td>
                                                    <td>{{ $item['tujuan'] }}</td>
                                                    <td class="text-end">
                                                        Rp{{ number_format((float) $item['nominal'], 0, ',', '.') }}
                                                    </td>

                                                    <td>
                                                        @if ($item['file_transfer'] === '')
                                                            -
                                                        @elseif ($item['transfer_ada'])
                                                            <span class="text-success">
                                                                <i class="bi bi-check-circle"></i>
                                                                {{ $item['file_transfer'] }}
                                                            </span>
                                                        @else
                                                            <span class="text-danger">
                                                                <i class="bi bi-x-circle"></i>
                                                                {{ $item['file_transfer'] }}
                                                            </span>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        @if ($item['file_nota'] === '')
                                                            -
                                                        @elseif ($item['nota_ada'])
                                                            <span class="text-success">
                                                                <i class="bi bi-check-circle"></i>
                                                                {{ $item['file_nota'] }}
                                                            </span>
                                                        @else
                                                            <span class="text-danger">
                                                                <i class="bi bi-x-circle"></i>
                                                                {{ $item['file_nota'] }}
                                                            </span>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        @if (count($item['errors']) === 0)
                                                            <span class="badge bg-success">Valid</span>
                                                        @else
                                                            <span class="badge bg-danger">Perlu diperbaiki</span>

                                                            <ul class="text-danger small mt-2 mb-0 ps-3">
                                                                @foreach ($item['errors'] as $error)
                                                                    <li>{{ $error }}</li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="9" class="text-center text-muted">
                                                        Tidak ada data transaksi dalam Excel.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>


                                @if (isset($token) && $jumlahValid > 0 && $jumlahError === 0)
                                    <form action="{{ route('import-transaksi.simpan') }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <input type="hidden" name="token" value="{{ $token }}">

                                        <button type="submit" class="btn btn-primary"
                                            onclick="return confirm('Simpan seluruh transaksi dari Excel?')">
                                            <i class="bi bi-database-check me-1"></i>
                                            Konfirmasi & Simpan
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    @include('footer')
