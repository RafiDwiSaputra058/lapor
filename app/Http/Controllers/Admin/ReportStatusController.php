<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReportStatusRequest;
use App\Interfaces\ReportRepositoryInterface;
use Illuminate\Http\Request;
use App\Interfaces\ReportStatusRepositoryInterface;
use App\Http\Requests\UpdateReportStatusRequest;

use RealRashid\SweetAlert\Facades\Alert as Swal;

class ReportStatusController extends Controller
{
    private ReportRepositoryInterface $reportRepository;
    private ReportStatusRepositoryInterface $reportStatusRepository;



    public function __construct(ReportRepositoryInterface $reportRepository, ReportStatusRepositoryInterface $reportStatusRepository)

    {
        $this->reportRepository = $reportRepository;
        $this->reportStatusRepository = $reportStatusRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(int $reportId)
    {
        $report = $this->reportRepository->getReportById($reportId);
        return view('pages.admin.report-status.create', compact('report'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReportStatusRequest $request)
    {
        $data = $request->validated();

        if ($request->image) {
            $data['image'] = $request->file('image')->store('assets/report-status/image', 'public');
        }

        $this->reportStatusRepository->createReport($data);

        Swal::toast('Data Progres Laporan berhasil ditambahkan', 'Succes')->timerProgressBar();
        return redirect()->route('admin.report.show', $request->report_id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $status = $this->reportStatusRepository->getReportById($id);

        return view('pages.admin.report-status.edit', compact('status'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReportStatusRequest $request, string $id)
    {
        $data = $request->validated();

        if ($request->image) {
            $data['image'] = $request->file('image')->store('assets/report-status/image', 'public');
        }

        $this->reportStatusRepository->updateReport($data, $id);

        Swal::toast('Data Progres Laporan berhasil diupdate', 'Succes')->timerProgressBar();
        return redirect()->route('admin.report.show', $request->report_id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $status = $this->reportStatusRepository->getReportById($id);
        $this->reportStatusRepository->deleteReport($id);

        Swal::toast('Data Progres Laporan berhasil dihapus', 'Succes')->timerProgressBar();
        return redirect()->route('admin.report.show', $status->report_id);
    }
}
