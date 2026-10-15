<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use Illuminate\Http\Request;

class AkunController extends Controller
{
    public function index()
    {
        $data['akun'] = Akun::orderBy('jenis_akun')
            ->orderBy('name')
            ->get();

        return view('akun.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'jenis_akun' => 'required|in:Kas,Toko,Perorangan',
        ]);

        Akun::create([
            'name' => $request->name,
            'jenis_akun' => $request->jenis_akun,
        ]);

        return back()->with('success', 'Akun berhasil ditambahkan.');
    }

    public function destroy(Akun $akun)
    {
        if ($akun->transaksiSumber()->exists() || $akun->transaksiTujuan()->exists()) {
            return back()->with('error', 'Akun tidak dapat dihapus karena sudah digunakan dalam transaksi.');
        }

        $akun->delete();

        return back()->with('success', 'Akun berhasil dihapus.');
    }
}