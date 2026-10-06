<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;


use App\Models\TujuanTransaksi;
use App\Models\Transaksi;
use App\Models\SumberDana;

class TransaksiCon extends Controller
{
    public function index(): View
    {
        $data = [
            'title' => "Buku Kas Umum",
            'tujuanTransaksi' => TujuanTransaksi::orderBy('name')->get(),
            'sumberDana' => SumberDana::orderBy('name')->get(),
            'transaksi' => Transaksi::with(['sumberDana', 'tujuanTransaksi'])
                ->orderBy('date', 'desc')
                ->get(),
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

    public function store(Request $request)
    {


        $request->validate([
            'date' => 'required',
            'id_sumber_dana' => 'required|exists:sumber_dana,id',
            'id_tujuan_transaksi' => 'required|exists:tujuan_transaksi,id',
            'nominal' => 'required|integer|min:1',
            'keterangan' => 'required|string',
            'bukti' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ]);

        // Generate kode bukti per bulan
        $date = \Carbon\Carbon::createFromFormat('d-m-Y', $request->date)
            ->setTimeFromTimeString(now()->format('H:i:s'));



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

        // Simpan transaksi ke database
        $transaksi = Transaksi::create([
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

    public function destroy(Transaksi $transaksi)
    {
        if ($transaksi->bukti) {
            \Storage::disk('public')->delete($transaksi->bukti);
        }

        $transaksi->delete();

        return back()->with('success', 'Transaksi berhasil dihapus.');
    }
}
