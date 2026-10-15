<?php

namespace App\Http\Controllers;

use App\Models\ImportDraft;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Akun;
use App\Models\ImportDraftItem;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use App\Models\Transaksi;
use App\Models\PemetaanUraianTujuan;
use Illuminate\Validation\ValidationException;



class ImportDraftController extends Controller
{
    public function index()
    {
        $drafts = ImportDraft::latest()->get();

        $data = [
            'title' => 'Draft Rekonsiliasi',
        ];

        return view('import-transaksi.drafts.index', compact('data', 'drafts'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'file_excel' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ], [
            'name.required' => 'Nama draft wajib diisi.',
            'file_excel.required' => 'File Excel wajib dipilih.',
            'file_excel.mimes' => 'File harus berformat XLSX, XLS, atau CSV.',
            'file_excel.max' => 'Ukuran Excel maksimal 10 MB.',
        ]);

        $file = $request->file('file_excel');
        $filePath = $file->store('import-drafts', 'local');

        try {
            $spreadsheet = IOFactory::load($file->getRealPath());

            $rows = $spreadsheet->getActiveSheet()->toArray(
                null,
                false,
                false,
                false
            );


            // Ambil seluruh akun dari database
            $akunByName = Akun::all()->keyBy(
                fn($akun) => mb_strtolower(trim($akun->name))
            );

            // Buat daftar baris Excel yang berisi data
            $barisExcel = [];

            foreach (array_slice($rows, 1, null, true) as $index => $row) {
                $row = array_pad($row, 8, null);

                $kosong = collect($row)->every(
                    fn($value) => trim((string) $value) === ''
                );

                if (!$kosong) {
                    $barisExcel[] = [
                        'row_number' => $index + 1,
                        'row' => array_slice($row, 0, 8),
                    ];
                }
            }

            $draft = DB::transaction(function () use ($request, $file, $filePath, $barisExcel, $akunByName) {
                $draft = ImportDraft::create([
                    'name' => $request->name,
                    'original_filename' => $file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'status' => 'draft',
                    'total_rows' => count($barisExcel),
                ]);

                foreach ($barisExcel as $baris) {
                    $row = array_map(
                        fn($value) => is_string($value)
                            ? trim($value)
                            : $value,
                        $baris['row']
                    );

                    [
                        $tanggal,
                        $kodeBukti,
                        $sumberDana,
                        $tujuan,
                        $nominal,
                        $keterangan,
                        $fileTransfer,
                        $fileNota,
                    ] = $row;

                    $errors = [];

                    // Periksa tanggal


                    $tanggalNormal = null;

                    try {
                        if (is_numeric($tanggal)) {
                            $tanggalNormal = Carbon::instance(
                                ExcelDate::excelToDateTimeObject((float) $tanggal)
                            )->format('Y-m-d');
                        } else {
                            $tanggalText = trim((string) $tanggal);

                            $hasilTanggal = Carbon::createFromFormat(
                                '!d/m/Y',
                                $tanggalText
                            );

                            if (
                                !$hasilTanggal ||
                                $hasilTanggal->format('d/m/Y') !== $tanggalText
                            ) {
                                throw new \RuntimeException('Format tanggal tidak valid.');
                            }

                            $tanggalNormal = $hasilTanggal->format('Y-m-d');
                        }
                    } catch (\Throwable $e) {
                        $errors[] = 'Tanggal tidak valid: ' . (string) $tanggal;
                    }



                    // Periksa kode bukti
                    if (trim((string) $kodeBukti) === '') {
                        $errors[] = 'Kode bukti wajib diisi.';
                    }

                    // Cari sumber dana
                    $namaSumber = mb_strtolower(
                        trim((string) $sumberDana)
                    );

                    $akunSumber = $namaSumber !== ''
                        ? $akunByName->get($namaSumber)
                        : null;

                    if (!$akunSumber) {
                        $errors[] = 'Sumber dana tidak ditemukan.';
                    }

                    // Cari tujuan jika diisi
                    $namaTujuan = mb_strtolower(
                        trim((string) $tujuan)
                    );

                    $akunTujuan = $namaTujuan !== ''
                        ? $akunByName->get($namaTujuan)
                        : null;

                    if ($namaTujuan !== '' && !$akunTujuan) {
                        $errors[] = 'Tujuan transaksi tidak ditemukan.';
                    }

                    // Periksa nominal
                    $nominalBersih = preg_replace(
                        '/[^\d]/',
                        '',
                        (string) $nominal
                    );

                    if (
                        $nominalBersih === ''
                        || (int) $nominalBersih < 1
                    ) {
                        $errors[] = 'Nominal harus berupa angka minimal 1.';
                        $nominalBersih = null;
                    }

                    // Periksa keterangan
                    if (trim((string) $keterangan) === '') {
                        $errors[] = 'Keterangan wajib diisi.';
                    }

                    // Simpan sebagai baris draft, bukan transaksi utama
                    ImportDraftItem::create([
                        'import_draft_id' => $draft->id,
                        'row_number' => $baris['row_number'],
                        'tanggal' => $tanggalNormal,
                        'kode_bukti' => (string) $kodeBukti,
                        'sumber_dana' => (string) $sumberDana,
                        'id_sumber_dana' => $akunSumber?->id,
                        'tujuan' => (string) $tujuan,
                        'id_tujuan_transaksi' => $akunTujuan?->id,
                        'nominal' => $nominalBersih,
                        'keterangan' => (string) $keterangan,
                        'file_transfer' => (string) $fileTransfer,
                        'file_nota' => (string) $fileNota,
                        'is_checked' => false,
                        'errors' => $errors ?: null,
                    ]);
                }

                return $draft;
            });

            return redirect()
                ->route('import-drafts.show', $draft->id)
                ->with('success', 'Draft dan baris Excel berhasil disimpan.');
        } catch (\Throwable $e) {
            Storage::disk('local')->delete($filePath);

            report($e);

            return back()
                ->withErrors([
                    'file_excel' => 'File gagal diproses. Periksa format Excel dan struktur database.',
                ])
                ->withInput();
        }
    }

    public function show(Request $request, ImportDraft $draft)
    {
        $items = $draft->items()
            ->orderBy('tanggal')
            ->orderBy('row_number')
            ->get();

        // Ambil daftar akun untuk pilihan filter
        $akun = Akun::whereIn('jenis_akun', ['Owner', 'Kas'])
            ->orderBy('name')
            ->get();

        $akunTujuan = Akun::orderBy('name')->get();

        $akunId = $request->input('akun_id');

        // Ambil tanggal dari Excel
        $tanggalExcel = $items
            ->pluck('tanggal')
            ->filter()
            ->map(fn($tanggal) => Carbon::parse($tanggal)->format('Y-m-d'));

        $tanggalAwal = $tanggalExcel->min();
        $tanggalAkhir = $tanggalExcel->max();

        $transaksi = collect();

        if ($tanggalAwal && $tanggalAkhir) {
            $query = Transaksi::with([
                'sumberDana',
                'tujuanTransaksi',
            ])
                ->whereBetween('date', [
                    $tanggalAwal . ' 00:00:00',
                    $tanggalAkhir . ' 23:59:59',
                ]);

            // Filter transaksi dari ATAU menuju akun yang dipilih
            if ($akunId) {
                $query->where(function ($q) use ($akunId) {
                    $q->where('id_sumber_dana', $akunId)
                        ->orWhere('id_tujuan_transaksi', $akunId);
                });
            }

            $transaksi = $query
                ->orderBy('date')
                ->get();
        }

        $itemsPerTanggal = $items->groupBy(
            fn($item) => $item->tanggal
                ? Carbon::parse($item->tanggal)->format('Y-m-d')
                : 'tanpa-tanggal'
        );

        $transaksiPerTanggal = $transaksi->groupBy(
            fn($item) => Carbon::parse($item->date)->format('Y-m-d')
        );

        $tanggal = $itemsPerTanggal->keys()
            ->merge($transaksiPerTanggal->keys())
            ->unique()
            ->reject(fn($tanggal) => $tanggal === 'tanpa-tanggal')
            ->sort()
            ->values();

        $data = [
            'title' => 'Rekonsiliasi Transaksi',
        ];

        return view('import-transaksi.drafts.show', compact(
            'data',
            'draft',
            'itemsPerTanggal',
            'transaksiPerTanggal',
            'tanggal',
            'akun',
            'akunId',
            'akunTujuan'
        ));
    }


    public function toggleCheckbox(
        Request $request,
        ImportDraft $draft,
        ImportDraftItem $item
    ) {
        // Pastikan item memang milik draft ini
        abort_unless(
            $item->import_draft_id === $draft->id,
            404
        );

        $validated = $request->validate([
            'is_checked' => ['required', 'boolean'],
        ]);

        $item->update([
            'is_checked' => $validated['is_checked'],
        ]);

        return back()->with('success', 'Status rekonsiliasi berhasil disimpan.');
    }


    public function saveCheckboxes(Request $request, ImportDraft $draft)
    {
        $validated = $request->validate([
            'item_ids' => ['required', 'array', 'min:1'],
            'item_ids.*' => ['required', 'integer', 'distinct'],
            'checked_ids' => ['nullable', 'array'],
            'checked_ids.*' => ['required', 'integer', 'distinct'],
            'items' => ['required', 'array'],
            'items.*.tanggal' => ['required', 'date_format:Y-m-d'],
            'items.*.nominal' => ['required', 'integer', 'min:1'],
        ]);

        $itemIds = array_map('intval', $validated['item_ids']);
        $checkedIds = array_map(
            'intval',
            $validated['checked_ids'] ?? []
        );

        // Pastikan semua item milik draft ini.
        $jumlahMilikDraft = $draft->items()
            ->whereIn('id', $itemIds)
            ->count();

        abort_unless(
            $jumlahMilikDraft === count($itemIds),
            422
        );

        // Pastikan checkbox dan data edit hanya untuk item yang dikirim.
        abort_unless(
            count(array_diff($checkedIds, $itemIds)) === 0,
            422
        );

        $dataItems = $validated['items'];

        abort_unless(
            count(array_diff($itemIds, array_map('intval', array_keys($dataItems)))) === 0
            && count(array_diff(array_map('intval', array_keys($dataItems)), $itemIds)) === 0,
            422
        );

        DB::transaction(function () use ($draft, $itemIds, $checkedIds, $dataItems) {
            foreach ($itemIds as $id) {
                $item = $draft->items()->whereKey($id)->firstOrFail();

                $item->update([
                    'tanggal' => $dataItems[$id]['tanggal'],
                    'nominal' => $dataItems[$id]['nominal'],
                    'is_checked' => in_array($id, $checkedIds, true),
                ]);
            }
        });

        /*return response()->json([
            'success' => true,
            'message' => 'Tanggal, nominal, dan pilihan rekonsiliasi berhasil disimpan.',
        ]);*/
        return response()->json([
            'success' => true,
            'message' => 'Draft berhasil disimpan.',
            'redirect_url' => route('import-drafts.show', [
                'draft' => $draft->id,
                'akun_id' => $request->input('akun_id'),
            ]),
        ]);
    }


    public function updateTujuanTransaksi(
        Request $request,
        ImportDraft $draft,
        Transaksi $transaksi
    ) {
        $validated = $request->validate([
            'id_tujuan_transaksi' => [
                'required',
                'integer',
                'exists:akun,id',
            ],
        ]);

        $transaksi->update([
            'id_tujuan_transaksi' => $validated['id_tujuan_transaksi'],
        ]);

        return back()->with(
            'success',
            'Tujuan transaksi berhasil diperbarui.'
        );
    }



    public function destroy(ImportDraft $draft)
    {
        if ($draft->status === 'selesai') {
            return back()->withErrors([
                'draft' => 'Draft yang sudah selesai tidak dapat dihapus.',
            ]);
        }

        Storage::disk('local')->delete($draft->file_path);
        $draft->delete();

        return redirect()
            ->route('import-drafts.index')
            ->with('success', 'Draft berhasil dihapus.');
    }



    public function tujuan(ImportDraft $draft)
    {
        $items = $draft->items()
            ->where('is_checked', false)
            ->orderBy('tanggal')
            ->orderBy('row_number')
            ->get();



        $sumberDana = Akun::whereIn('jenis_akun', ['Owner', 'Kas'])
            ->orderBy('name')
            ->get();

        // Ambil rekomendasi dari tabel transaksi utama.
        $riwayatTujuan = Transaksi::query()
            ->whereNotNull('keterangan')
            ->where('keterangan', '<>', '')
            ->whereNotNull('id_tujuan_transaksi')
            ->get([
                'keterangan',
                'id_tujuan_transaksi',
            ])
            ->groupBy('keterangan');

        $kelompok = $items
            ->groupBy('keterangan')
            ->map(function ($items, $uraian) use ($riwayatTujuan) {
                $riwayat = $riwayatTujuan->get($uraian, collect());

                // Pilih tujuan yang paling sering digunakan untuk uraian tersebut.
                $rekomendasi = $riwayat
                    ->countBy('id_tujuan_transaksi')
                    ->sortDesc()
                    ->keys()
                    ->first();

                $pertama = $items->first();

                // Pertahankan tujuan draft yang sudah ada.
                $idTujuan = $pertama->id_tujuan_transaksi ?: $rekomendasi;
                $id_sumber_dana = $pertama->id_sumber_dana ?: "";

                return [
                    'uraian' => $uraian,
                    'id_sumber_dana' => $id_sumber_dana,
                    'jumlah' => $items->count(),
                    'total_nominal' => $items->sum('nominal'),
                    'item_ids' => $items->pluck('id')->all(),
                    'id_tujuan_transaksi' => $idTujuan,
                    'sudah_ada_tujuan' => $idTujuan !== null,
                ];
            })
            ->sort(function ($a, $b) {
                // Yang belum punya tujuan ditampilkan lebih dahulu.
                $status = (int) $a['sudah_ada_tujuan']
                    <=> (int) $b['sudah_ada_tujuan'];

                if ($status !== 0) {
                    return $status;
                }

                // Urutkan uraian berdasarkan abjad A-Z.
                return strcasecmp($a['uraian'] ?? '', $b['uraian'] ?? '');
            })
            ->values();

        $akunTujuan = Akun::orderBy('name')->get();

        $data = [
            'title' => 'Penentuan Tujuan Transaksi',
        ];

        return view('import-transaksi.drafts.tujuan', compact(
            'data',
            'draft',
            'kelompok',
            'akunTujuan',
            'sumberDana'
        ));
    }


    public function simpanTujuan(Request $request, ImportDraft $draft)
    {
        $validated = $request->validate([
            'tujuan' => ['required', 'array'],
            'tujuan.*' => ['required', 'integer', 'exists:akun,id'],
            'uraian' => ['required', 'array'],
            'uraian.*' => ['required', 'string'],
        ]);

        abort_unless(
            array_diff(
                array_keys($validated['tujuan']),
                array_keys($validated['uraian'])
            ) === [],
            422
        );

        DB::transaction(function () use ($validated) {
            foreach ($validated['tujuan'] as $hash => $akunId) {
                $uraian = $validated['uraian'][$hash];

                // Pastikan hash cocok dengan uraian yang dikirim.
                abort_unless(
                    hash_equals($hash, hash('sha256', $uraian)),
                    422
                );

                PemetaanUraianTujuan::updateOrCreate(
                    ['uraian_hash' => $hash],
                    [
                        'uraian' => $uraian,
                        'id_tujuan_transaksi' => $akunId,
                    ]
                );
            }
        });

        return redirect()
            ->route('import-drafts.tujuan', $draft)
            ->with('success', 'Pemetaan tujuan berhasil disimpan.');
    }

    /*public function autosaveTujuan(Request $request, ImportDraft $draft)
    {
        $validated = $request->validate([
            'jenis' => ['required', 'in:tujuan,sumber_dana'],
            'nilai' => ['required', 'integer', 'exists:akun,id'],
            'item_ids' => ['required', 'array', 'min:1'],
            'item_ids.*' => ['required', 'integer', 'distinct'],
        ]);

        $akun = Akun::findOrFail($validated['nilai']);

        // Validasi jenis akun sesuai dropdown yang dipilih.
        if (
            $validated['jenis'] === 'sumber_dana'
            && !in_array($akun->jenis_akun, ['Owner', 'Kas'])
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Akun tersebut bukan sumber dana yang valid.',
            ], 422);
        }

        $items = $draft->items()
            ->whereIn('id', $validated['item_ids'])
            ->get();

        abort_if(
            $items->count() !== count($validated['item_ids']),
            422,
            'Data transaksi tidak valid.'
        );

        foreach ($items as $item) {
            if ($validated['jenis'] === 'tujuan') {
                $item->update([
                    'id_tujuan_transaksi' => $akun->id,
                    'tujuan' => $akun->name,
                ]);
            } else {
                $item->update([
                    'id_sumber_dana' => $akun->id,
                    'sumber_dana' => $akun->name,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Pilihan berhasil disimpan.',
            'jenis' => $validated['jenis'],
            'nama' => $akun->name,
        ]);
    }*/
    public function autosaveTujuan(Request $request, ImportDraft $draft)
    {
        $validated = $request->validate([
            'jenis' => ['required', 'in:tujuan,sumber_dana'],
            'nilai' => ['required', 'integer', 'exists:akun,id'],
            'item_ids' => ['required', 'array', 'min:1'],
            'item_ids.*' => ['required', 'integer', 'distinct'],
        ]);

        $akun = Akun::findOrFail($validated['nilai']);

        if (
            $validated['jenis'] === 'sumber_dana'
            && !in_array($akun->jenis_akun, ['Owner', 'Kas'])
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Akun tersebut bukan sumber dana yang valid.',
            ], 422);
        }

        DB::transaction(function () use ($draft, $validated, $akun) {
            $items = $draft->items()
                ->whereIn('id', $validated['item_ids'])
                ->where('is_checked', false)
                ->get();

            if ($items->count() !== count($validated['item_ids'])) {
                abort(422, 'Data transaksi tidak valid atau sudah dicentang.');
            }

            foreach ($items as $item) {
                if ($validated['jenis'] === 'tujuan') {
                    $item->id_tujuan_transaksi = $akun->id;
                    $item->tujuan = $akun->name;
                } else {
                    $item->id_sumber_dana = $akun->id;
                    $item->sumber_dana = $akun->name;
                }

                $item->save();
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Pilihan berhasil disimpan.',
            'jenis' => $validated['jenis'],
            'nama' => $akun->name,
        ]);
    }


    public function simpanTransaksi(ImportDraft $draft)
    {
        $items = $draft->items()
            ->where('is_checked', false)
            ->orderBy('tanggal')
            ->orderBy('row_number')
            ->get();

        if ($items->isEmpty()) {
            return back()->with('error', 'Tidak ada data draft yang perlu disimpan.');
        }

        DB::transaction(function () use ($items, $draft) {
            foreach ($items as $item) {
                if (
                    !$item->tanggal ||
                    !$item->id_sumber_dana ||
                    !$item->id_tujuan_transaksi ||
                    !$item->nominal ||
                    $item->nominal < 1 ||
                    trim($item->keterangan ?? '') === ''
                ) {
                    throw ValidationException::withMessages([
                        'import' => 'Data pada baris Excel nomor '
                            . $item->row_number
                            . ' belum lengkap. Periksa tanggal, sumber dana, tujuan, nominal, dan keterangan.',
                    ]);
                }

                $akunSumber = Akun::find($item->id_sumber_dana);
                $akunTujuan = Akun::find($item->id_tujuan_transaksi);

                if (!$akunSumber || !$akunTujuan) {
                    throw ValidationException::withMessages([
                        'import' => 'Sumber dana atau tujuan pada baris Excel nomor '
                            . $item->row_number
                            . ' tidak ditemukan.',
                    ]);
                }

                $tanggal = Carbon::parse($item->tanggal);

                // Gunakan kode bukti dari draft jika tersedia.
                // Jika kosong, buat kode BPU berdasarkan bulan transaksi.
                $kodeBukti = $item->kode_bukti;

                if (!$kodeBukti) {
                    $nomor = Transaksi::whereYear('date', $tanggal->year)
                        ->whereMonth('date', $tanggal->month)
                        ->count() + 1;

                    do {
                        $kodeBukti = 'BPU' . str_pad(
                            $nomor++,
                            3,
                            '0',
                            STR_PAD_LEFT
                        );
                    } while (
                        Transaksi::where('kode_bukti', $kodeBukti)->exists()
                    );
                }

                Transaksi::create([
                    'kode_bukti' => $kodeBukti,
                    'date' => $tanggal,
                    'id_sumber_dana' => $item->id_sumber_dana,
                    'id_tujuan_transaksi' => $item->id_tujuan_transaksi,
                    'nominal' => $item->nominal,
                    'keterangan' => $item->keterangan,
                    'bukti' => null,
                    'bukti_nota' => null,
                ]);
            }
            $draft->update([
                'status' => 'selesai',
                'finalized_at' => now(),
            ]);
        });

        return redirect()
            //->route('import-drafts.show', $draft)
            ->route('import-drafts.index')
            ->with('success', 'Data draft berhasil disimpan ke transaksi.');
    }


}