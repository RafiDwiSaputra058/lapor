<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ReportRepository; // Memanggil folder repository temanmu

class ProfileController extends Controller
{
    public function index()
    {
        // 1. Kita panggil mesin penghitung buatan temanmu
        $reportRepo = app(ReportRepository::class);

        // 2. Hitung jumlah laporan yang masih aktif (misalnya status 'pending')
        $aktif = $reportRepo->getReportsByResidentId('pending')->count();

        // 3. Hitung jumlah laporan yang sudah selesai (status 'completed')
        $selesai = $reportRepo->getReportsByResidentId('completed')->count();

        // 4. Kirim angkanya ke piring (file blade)
        return view('pages.app.profile', compact('aktif', 'selesai'));
    }
}