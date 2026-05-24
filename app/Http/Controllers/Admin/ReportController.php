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
        $reports = $this->reportRepository->getAllReports();

        return view('pages.admin.report.index', compact('reports'));
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
