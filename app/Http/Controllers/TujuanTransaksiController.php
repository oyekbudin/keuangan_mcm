<?php

namespace App\Http\Controllers;

use App\Models\TujuanTransaksi;
use Illuminate\Http\Request;

class TujuanTransaksiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
        ]);

        TujuanTransaksi::create([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Tujuan transaksi berhasil ditambahkan.');
    }
}