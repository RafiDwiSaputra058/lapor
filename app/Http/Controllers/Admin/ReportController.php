<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\ReportRepositoryInterface;
use App\Interfaces\ReportCategoryRepositoryInterface;
use App\Interfaces\ResidentRepositoryInterface;
use App\Http\Requests\StoreReportRequest;
use RealRashid\SweetAlert\Facades\Alert as Swal;
use App\Http\Requests\UpdateReportRequest;
use App\Models\Report;
use App\Models\ReportStatus; // PENTING: Ini agar tidak error saat menghitung status

class ReportController extends Controller
{
    private ReportRepositoryInterface $reportRepository;
    private ReportCategoryRepositoryInterface $reportCategoryRepository;
    private ResidentRepositoryInterface $residentRepository;

    public function __construct(ReportRepositoryInterface $reportRepository, ReportCategoryRepositoryInterface $reportCategoryRepository, ResidentRepositoryInterface $residentRepository)
    {
        $this->reportRepository = $reportRepository;
        $this->reportCategoryRepository = $reportCategoryRepository;
        $this->residentRepository = $residentRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 1. Ambil data laporan untuk tabel
        $reports = Report::with(['resident.user', 'reportCategory', 'reportStatuses'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(5);
        
        // 2. Hitung statistik untuk Kartu (Card) di bagian atas
        $totalLaporan = Report::count();
        $selesai = ReportStatus::where('status', 'completed')->count();
        $diproses = ReportStatus::where('status', 'in_progress')->count();
        
        // Logika Pending: Total Laporan dikurangi yang sudah diproses & selesai
        $pending = $totalLaporan - ($selesai + $diproses);
        
        // 3. Kirim semua variabel hitungan ke file Blade
        return view('pages.admin.report.index', compact('reports', 'totalLaporan', 'selesai', 'diproses', 'pending'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $residents = $this->residentRepository->getAllResidents();
        $categories = $this->reportCategoryRepository->getAllReportCategories();

        return view('pages.admin.report.create', compact('residents', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReportRequest $request)
    {
        $data = $request->validated();

        $data['code'] = 'PAKLAPOR' . mt_rand(100000, 999999);
        $data['image'] = $request->file('image')->store('assets/report/image', 'public');

        $this->reportRepository->createReport($data);

        Swal::toast('Laporan berhasil dibuat.', 'success');

        return redirect()->route('admin.report.index');
    }
    
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $report = $this->reportRepository->getReportById($id);

        return view('pages.admin.report.show', compact('report'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $report = $this->reportRepository->getReportById($id);

        $residents = $this->residentRepository->getAllResidents();
        $categories = $this->reportCategoryRepository->getAllReportCategories();

        return view('pages.admin.report.edit', compact('report', 'residents', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReportRequest $request, string $id)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('assets/report/image', 'public');
        }

        $this->reportRepository->updateReport($data, $id);

        Swal::toast('Laporan berhasil diperbarui.', 'success');

        return redirect()->route('admin.report.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->reportRepository->deleteReport($id);

        Swal::toast('Laporan berhasil dihapus.', 'success');

        return redirect()->route('admin.report.index');
    }
}