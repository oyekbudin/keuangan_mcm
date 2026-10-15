<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

use App\Models\TujuanTransaksi;
use App\Models\Transaksi;
use App\Models\Akun;



class TransaksiCon extends Controller
{
    public function index(): View
    {
        // Total pengeluaran Bos berdasarkan akun tujuan
        $pengeluaranBos = Transaksi::query()
            ->join('akun as sumber', 'transaksi.id_sumber_dana', '=', 'sumber.id')
            ->where('sumber.jenis_akun', 'Owner')
            ->sum('transaksi.nominal');

        // Saldo setiap akun Kas

        $saldoKas = Akun::where('jenis_akun', 'Kas')
            ->orderBy('name')
            ->get()
            ->map(function ($akun) {
                // Semua uang masuk ke kas ini
                $masuk = Transaksi::where('id_tujuan_transaksi', $akun->id)
                    ->sum('nominal');

                // Semua uang keluar dari kas ini
                $keluar = Transaksi::where('id_sumber_dana', $akun->id)
                    ->sum('nominal');

                $akun->saldo = $masuk - $keluar;

                return $akun;
            });



        // Daftar nama toko
        $dataToko = Akun::whereIn('jenis_akun', ['Toko', 'Perorangan'])
            ->orderBy('name')
            ->get();


        $filterAkun = Akun::whereIn('jenis_akun', ['Owner', 'Kas'])
            ->orderBy('name')
            ->get();

        $filter = request('filter', 'semua');

        $akunTerpilih = $filterAkun->firstWhere('id', (int) $filter);

        $transaksi = Transaksi::with(['sumberDana', 'tujuanTransaksi'])
            ->when($akunTerpilih, function ($query) use ($akunTerpilih) {
                if ($akunTerpilih->jenis_akun === 'Owner') {
                    $query->where('id_sumber_dana', $akunTerpilih->id);
                } else {
                    $query->where(function ($q) use ($akunTerpilih) {
                        $q->where('id_sumber_dana', $akunTerpilih->id)
                            ->orWhere('id_tujuan_transaksi', $akunTerpilih->id);
                    });
                }
            })
            ->orderByDesc('date')
            ->get();




        $data = [
            'title' => 'Transaksi',

            'akun' => Akun::orderBy('jenis_akun')
                ->orderBy('name')
                ->get(),

            'filterAkun' => $filterAkun,
            'transaksi' => $transaksi,
            'pengeluaranBos' => $pengeluaranBos,
            'saldoKas' => $saldoKas,
            'dataToko' => $dataToko,
        ];

        return view('transaksi', compact('data'));
    }

    public function login(): View
    {
        return view('login');
    }
    public function cekdb()
    {
        try {
            DB::connection()->getPdo();
            $databaseStatus = 'Database Terhubung';
        } catch (\Exception $e) {
            $databaseStatus = 'Database Tidak Terhubung';
        }

        return view('cekdb', compact('databaseStatus'));
    }
    public function updateTujuan(Request $request, Transaksi $transaksi)
    {
        $validated = $request->validate([
            'id_tujuan_transaksi' => [
                'required',
                'integer',
                Rule::exists('akun', 'id'),
            ],
        ]);

        $akunTujuan = Akun::findOrFail($validated['id_tujuan_transaksi']);

        $transaksi->id_tujuan_transaksi = $akunTujuan->id;
        $transaksi->save();

        return response()->json([
            'success' => true,
            'message' => 'Tujuan transaksi berhasil diperbarui.',
        ]);
    }

    public function oldstore(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'id_sumber_dana' => 'required|exists:akun,id',
            'id_tujuan_transaksi' => 'required|exists:akun,id',
            'nominal' => 'required|integer|min:1',
            'keterangan' => 'required|string',
            'bukti' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ]);

        // Tanggal transaksi
        $date = \Carbon\Carbon::createFromFormat('Y-m-d', $request->date)
            ->setTimeFromTimeString(now()->format('H:i:s'));

        // Generate kode bukti per bulan
        $nomor = Transaksi::whereYear('date', $date->year)
            ->whereMonth('date', $date->month)
            ->count() + 1;

        $kodeBukti = 'BPU' . str_pad($nomor, 3, '0', STR_PAD_LEFT);

        // Upload bukti terlebih dahulu
        $path = null;

        if ($request->hasFile('bukti')) {
            $file = $request->file('bukti');

            $namaFile = $kodeBukti . '.' . $file->getClientOriginalExtension();

            $path = $file->storeAs(
                'bukti-transaksi/' . $date->format('Y/m'),
                $namaFile,
                'public'
            );
        }

        // Simpan transaksi
        Transaksi::create([
            'kode_bukti' => $kodeBukti,
            'date' => $date,
            'id_sumber_dana' => $request->id_sumber_dana,
            'id_tujuan_transaksi' => $request->id_tujuan_transaksi,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
            'bukti' => $path,
        ]);

        return back()->with('success', 'Transaksi berhasil disimpan.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'id_sumber_dana' => 'required|exists:akun,id',
            'id_tujuan_transaksi' => 'required|exists:akun,id',
            'nominal' => 'required|integer|min:1',
            'keterangan' => 'required|string',
            'bukti' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
            'bukti_nota' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ]);

        $akunSumber = Akun::findOrFail($request->id_sumber_dana);

        if ($akunSumber->jenis_akun === 'Kas') {
            $saldoMasuk = Transaksi::where(
                'id_tujuan_transaksi',
                $akunSumber->id
            )->sum('nominal');

            $saldoKeluar = Transaksi::where(
                'id_sumber_dana',
                $akunSumber->id
            )->sum('nominal');

            $saldoTersedia = $saldoMasuk - $saldoKeluar;

            if ($request->nominal > $saldoTersedia) {
                return back()
                    ->withErrors([
                        'nominal' => 'Nominal melebihi saldo tersedia: Rp' .
                            number_format($saldoTersedia, 0, ',', '.') . '.',
                    ])
                    ->withInput();
            }
        }

        $date = \Carbon\Carbon::createFromFormat('Y-m-d', $request->date)
            ->setTimeFromTimeString(now()->format('H:i:s'));

        $nomor = Transaksi::whereYear('date', $date->year)
            ->whereMonth('date', $date->month)
            ->count() + 1;

        $kodeBukti = 'BPU' . str_pad($nomor, 3, '0', STR_PAD_LEFT);

        // Upload bukti transfer
        $path = null;

        if ($request->hasFile('bukti')) {
            $file = $request->file('bukti');

            $namaFile = $kodeBukti . '.' . $file->getClientOriginalExtension();

            $path = $file->storeAs(
                'bukti-transaksi/' . $date->format('Y/m'),
                $namaFile,
                'public'
            );
        }

        // Upload nota / kwitansi
        $pathNota = null;

        if ($request->hasFile('bukti_nota')) {
            $fileNota = $request->file('bukti_nota');

            $namaFileNota = $kodeBukti . '-nota.' . $fileNota->getClientOriginalExtension();

            $pathNota = $fileNota->storeAs(
                'bukti-transaksi/' . $date->format('Y/m'),
                $namaFileNota,
                'public'
            );
        }

        Transaksi::create([
            'kode_bukti' => $kodeBukti,
            'date' => $date,
            'id_sumber_dana' => $request->id_sumber_dana,
            'id_tujuan_transaksi' => $request->id_tujuan_transaksi,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
            'bukti' => $path,
            'bukti_nota' => $pathNota,
        ]);

        return back()->with('success', 'Transaksi berhasil disimpan.');
    }

    public function olddestroy(Transaksi $transaksi)
    {
        if ($transaksi->bukti) {
            \Storage::disk('public')->delete($transaksi->bukti);
        }

        $transaksi->delete();

        return back()->with('success', 'Transaksi berhasil dihapus.');
    }

    public function destroy(Transaksi $transaksi)
    {
        if ($transaksi->bukti) {
            \Storage::disk('public')->delete($transaksi->bukti);
        }

        if ($transaksi->bukti_nota) {
            \Storage::disk('public')->delete($transaksi->bukti_nota);
        }

        $transaksi->delete();

        return back()->with('success', 'Transaksi berhasil dihapus.');
    }
}
