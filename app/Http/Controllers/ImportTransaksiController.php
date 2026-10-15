<?php

namespace App\Http\Controllers;

use App\Exports\TemplateTransaksiExport;
use App\Models\Akun;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use ZipArchive;

class ImportTransaksiController extends Controller
{
    public function index()
    {
        $data = ['title' => 'Import Transaksi'];

        return view('import-transaksi.index', compact('data'));
    }

    public function template()
    {
        return Excel::download(
            new TemplateTransaksiExport(),
            'Template-Import-Transaksi.xlsx'
        );
    }

    public function preview(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|file|mimes:xlsx,xls,csv|max:10240',
            'file_bukti' => 'required|file|mimes:zip|max:51200',
        ]);

        $token = Str::random(40);

        Storage::disk('local')->putFileAs(
            'import-transaksi-temp',
            $request->file('file_excel'),
            $token . '.xlsx'
        );

        Storage::disk('local')->putFileAs(
            'import-transaksi-temp',
            $request->file('file_bukti'),
            $token . '.zip'
        );

        try {
            $hasil = $this->bacaDanValidasi(
                Storage::disk('local')->path(
                    'import-transaksi-temp/' . $token . '.xlsx'
                ),
                Storage::disk('local')->path(
                    'import-transaksi-temp/' . $token . '.zip'
                )
            );
        } catch (\Throwable $e) {
            $this->hapusFileSementara($token);

            return back()->withErrors([
                'file_excel' => 'Gagal membaca file import. Periksa kembali Excel dan ZIP.',
            ])->withInput();
        }

        $jumlahValid = collect($hasil)
            ->where('errors', [])
            ->count();

        $jumlahError = count($hasil) - $jumlahValid;

        // Kode bukti harus unik di database dan di dalam file import.
        $kodeDalamImport = [];

        foreach ($hasil as &$item) {
            $kode = mb_strtolower(trim($item['kode_bukti']));

            if (isset($kodeDalamImport[$kode])) {
                $item['errors'][] = 'Kode bukti duplikat di dalam Excel.';
            }

            $kodeDalamImport[$kode] = true;

            if (
                Transaksi::where('kode_bukti', $item['kode_bukti'])->exists()
            ) {
                $item['errors'][] = 'Kode bukti sudah ada di database.';
            }
        }

        unset($item);

        $jumlahValid = collect($hasil)
            ->where('errors', [])
            ->count();

        $jumlahError = count($hasil) - $jumlahValid;

        return view('import-transaksi.preview', [
            'data' => ['title' => 'Pratinjau Import Transaksi'],
            'hasil' => $hasil,
            'token' => $token,
            'jumlahValid' => $jumlahValid,
            'jumlahError' => $jumlahError,
        ]);
    }

    private function bacaDanValidasi(string $pathExcel, string $pathZip): array
    {
        $spreadsheet = IOFactory::load($pathExcel);

        // Baca nilai asli Excel agar tanggal serial tidak berubah menjadi teks.
        $rows = $spreadsheet->getActiveSheet()->toArray(
            null,
            false,
            false,
            false
        );

        $akunByName = Akun::all()->keyBy(
            fn($akun) => mb_strtolower(trim($akun->name))
        );

        $zip = new ZipArchive();

        if ($zip->open($pathZip) !== true) {
            throw new \RuntimeException('ZIP tidak dapat dibuka.');
        }

        $fileZip = [];

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $nama = $zip->getNameIndex($i);

            if (!$nama || str_ends_with($nama, '/')) {
                continue;
            }

            // Simpan nama lengkap entry agar isi file dapat diambil kembali.
            $fileZip[mb_strtolower(basename($nama))] = $nama;
        }

        $zip->close();

        $hasil = [];

        foreach (array_slice($rows, 1, null, true) as $index => $row) {
            $row = array_pad($row, 8, null);

            if (
                collect($row)->every(
                    fn($v) => $v === null || trim((string) $v) === ''
                )
            ) {
                continue;
            }

            [
                $tanggal,
                $kodeBukti,
                $sumberDana,
                $tujuan,
                $nominal,
                $keterangan,
                $fileTransfer,
                $fileNota,
            ] = array_map(
                fn($v) => is_string($v) ? trim($v) : $v,
                array_slice($row, 0, 8)
            );

            $errors = [];
            $tanggalNormal = null;

            try {
                if (is_numeric($tanggal)) {
                    $tanggalNormal = Carbon::instance(
                        ExcelDate::excelToDateTimeObject((float) $tanggal)
                    )->format('Y-m-d');
                } else {
                    $tanggalText = trim((string) $tanggal);
                    $parsed = Carbon::createFromFormat(
                        '!d/m/Y',
                        $tanggalText
                    );

                    if (
                        !$parsed ||
                        $parsed->format('d/m/Y') !== $tanggalText
                    ) {
                        throw new \RuntimeException();
                    }

                    $tanggalNormal = $parsed->format('Y-m-d');
                }
            } catch (\Throwable $e) {
                $errors[] = 'Tanggal tidak valid.';
            }

            $kodeBukti = trim((string) $kodeBukti);
            $sumberDana = trim((string) $sumberDana);
            $tujuan = trim((string) $tujuan);
            $keterangan = trim((string) $keterangan);

            if ($kodeBukti === '') {
                $errors[] = 'Kode bukti wajib diisi.';
            }

            if ($keterangan === '') {
                $errors[] = 'Keterangan wajib diisi.';
            }

            $akunSumber = $akunByName[
                mb_strtolower($sumberDana)
            ] ?? null;

            $akunTujuan = $akunByName[
                mb_strtolower($tujuan)
            ] ?? null;

            if (!$akunSumber) {
                $errors[] = 'Sumber dana tidak ditemukan.';
            }

            if (!$akunTujuan) {
                $errors[] = 'Tujuan transaksi tidak ditemukan.';
            }

            $nominalBersih = preg_replace(
                '/[^\d]/',
                '',
                (string) $nominal
            );

            if (
                $nominalBersih === '' ||
                (int) $nominalBersih < 1
            ) {
                $errors[] = 'Nominal harus berupa angka minimal 1.';
            }

            $fileTransfer = trim((string) $fileTransfer);
            $fileNota = trim((string) $fileNota);

            $transferAda = $fileTransfer === '' ||
                isset($fileZip[mb_strtolower(basename($fileTransfer))]);

            $notaAda = $fileNota === '' ||
                isset($fileZip[mb_strtolower(basename($fileNota))]);

            if (!$transferAda) {
                $errors[] = "File transfer {$fileTransfer} tidak ditemukan di ZIP.";
            }

            if (!$notaAda) {
                $errors[] = "File nota {$fileNota} tidak ditemukan di ZIP.";
            }

            $hasil[] = [
                'baris' => $index + 1,
                'tanggal' => $tanggalNormal,
                'kode_bukti' => $kodeBukti,
                'sumber_dana' => $sumberDana,
                'tujuan' => $tujuan,
                'id_sumber_dana' => $akunSumber?->id,
                'id_tujuan_transaksi' => $akunTujuan?->id,
                'nominal' => $nominalBersih,
                'keterangan' => $keterangan,
                'file_transfer' => $fileTransfer,
                'file_nota' => $fileNota,
                'transfer_ada' => $transferAda,
                'nota_ada' => $notaAda,
                'errors' => $errors,
            ];
        }

        return $hasil;
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'token' => 'required|string|size:40|regex:/^[A-Za-z0-9]+$/',
        ]);

        $token = $request->token;

        $pathExcel = 'import-transaksi-temp/' . $token . '.xlsx';
        $pathZip = 'import-transaksi-temp/' . $token . '.zip';

        if (
            !Storage::disk('local')->exists($pathExcel) ||
            !Storage::disk('local')->exists($pathZip)
        ) {
            return redirect()
                ->route('import-transaksi.index')
                ->withErrors([
                    'file_excel' => 'File sementara sudah tidak tersedia. Silakan upload ulang.',
                ]);
        }

        $fileTersimpan = [];

        try {
            $hasil = $this->bacaDanValidasi(
                Storage::disk('local')->path($pathExcel),
                Storage::disk('local')->path($pathZip)
            );

            if (count($hasil) === 0) {
                throw new \RuntimeException('Tidak ada data transaksi.');
            }

            $kodeImport = [];

            foreach ($hasil as $item) {
                if (count($item['errors']) > 0) {
                    throw new \RuntimeException(
                        'Masih ada data tidak valid. Silakan periksa ulang Excel dan ZIP.'
                    );
                }

                $kode = mb_strtolower($item['kode_bukti']);

                if (isset($kodeImport[$kode])) {
                    throw new \RuntimeException('Ada kode bukti duplikat.');
                }

                $kodeImport[$kode] = true;

                if (
                    Transaksi::where(
                        'kode_bukti',
                        $item['kode_bukti']
                    )->exists()
                ) {
                    throw new \RuntimeException(
                        'Kode bukti ' . $item['kode_bukti'] .
                        ' sudah ada di database.'
                    );
                }
            }

            $zip = new ZipArchive();

            if ($zip->open(Storage::disk('local')->path($pathZip)) !== true) {
                throw new \RuntimeException('ZIP tidak dapat dibuka.');
            }

            DB::transaction(function () use ($hasil, $zip, &$fileTersimpan) {
                foreach ($hasil as $item) {
                    $tanggal = Carbon::createFromFormat(
                        'Y-m-d',
                        $item['tanggal']
                    )->setTimeFromTimeString(now()->format('H:i:s'));

                    $folder = 'bukti-transaksi/' . $tanggal->format('Y/m');

                    $pathTransfer = null;
                    $pathNota = null;

                    if ($item['file_transfer'] !== '') {
                        $pathTransfer = $this->simpanBuktiZip(
                            $zip,
                            $item['file_transfer'],
                            $folder,
                            $item['kode_bukti'],
                            false
                        );

                        $fileTersimpan[] = $pathTransfer;
                    }

                    if ($item['file_nota'] !== '') {
                        $pathNota = $this->simpanBuktiZip(
                            $zip,
                            $item['file_nota'],
                            $folder,
                            $item['kode_bukti'],
                            true
                        );

                        $fileTersimpan[] = $pathNota;
                    }

                    Transaksi::create([
                        'kode_bukti' => $item['kode_bukti'],
                        'date' => $tanggal,
                        'id_sumber_dana' => $item['id_sumber_dana'],
                        'id_tujuan_transaksi' => $item['id_tujuan_transaksi'],
                        'nominal' => (int) $item['nominal'],
                        'keterangan' => $item['keterangan'],
                        'bukti' => $pathTransfer,
                        'bukti_nota' => $pathNota,
                    ]);
                }
            });

            $zip->close();

            $this->hapusFileSementara($token);

            return redirect()
                ->route('import-transaksi.index')
                ->with('success', count($hasil) . ' transaksi berhasil diimport.');
        } catch (\Throwable $e) {
            if (isset($zip) && $zip instanceof ZipArchive) {
                $zip->close();
            }

            foreach ($fileTersimpan as $path) {
                Storage::disk('public')->delete($path);
            }

            report($e);

            return redirect()
                ->route('import-transaksi.index')
                ->withErrors([
                    'file_excel' => 'Import gagal: ' . $e->getMessage(),
                ]);
        }
    }

    private function simpanBuktiZip(
        ZipArchive $zip,
        string $namaFile,
        string $folder,
        string $kodeBukti,
        bool $nota
    ): string {
        $ext = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)) {
            throw new \RuntimeException(
                "Format gambar tidak didukung: {$namaFile}"
            );
        }

        $entry = null;

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $namaEntry = $zip->getNameIndex($i);

            if (
                $namaEntry &&
                mb_strtolower(basename($namaEntry)) ===
                mb_strtolower(basename($namaFile))
            ) {
                $entry = $namaEntry;
                break;
            }
        }

        if (!$entry) {
            throw new \RuntimeException(
                "File {$namaFile} tidak ditemukan di ZIP."
            );
        }

        $isi = $zip->getFromName($entry);

        if ($isi === false || @getimagesizefromstring($isi) === false) {
            throw new \RuntimeException(
                "File {$namaFile} bukan gambar yang valid."
            );
        }

        $namaSimpan = $kodeBukti .
            ($nota ? '-nota' : '') .
            '.' . $ext;

        $path = $folder . '/' . $namaSimpan;

        if (Storage::disk('public')->exists($path)) {
            throw new \RuntimeException(
                "File tujuan sudah ada: {$namaSimpan}"
            );
        }

        Storage::disk('public')->put($path, $isi);

        return $path;
    }

    private function hapusFileSementara(string $token): void
    {
        Storage::disk('local')->delete([
            'import-transaksi-temp/' . $token . '.xlsx',
            'import-transaksi-temp/' . $token . '.zip',
        ]);
    }
}
