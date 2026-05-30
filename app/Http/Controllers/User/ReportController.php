<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\ReportRepositoryInterface;
use App\Interfaces\ReportCategoryRepositoryInterface;
use App\Http\Requests\StoreReportRequest;
use Illuminate\Support\Facades\Auth;
use App\Services\AIService;



class ReportController extends Controller
{

    private ReportRepositoryInterface $reportRepository;
    private ReportCategoryRepositoryInterface $reportCategoryRepository;
    private AIService $aiService;

    public function __construct(
        ReportRepositoryInterface $reportRepository,
        ReportCategoryRepositoryInterface $reportCategoryRepository,
        AIService $aiService
    ) {
        $this->reportRepository = $reportRepository;
        $this->reportCategoryRepository = $reportCategoryRepository;
        $this->aiService = $aiService;
    }


    public function index(Request $request)
    {
        if ($request->category) {
            $reports = $this->reportRepository->getReportsByCategory($request->category);
        } else {
            $reports = $this->reportRepository->getAllReports();
        }
        return view('pages.app.report.index', compact('reports'));
    }


    public function myReport(Request $request)
    {
        $reports = $this->reportRepository->getReportsByResidentId($request->status);

        return view('pages.app.report.my-report', compact('reports'));
    }


    public function show(string $code)
    {
        $report = $this->reportRepository->getReportByCode($code);

        return view('pages.app.report.show', compact('report'));
    }

    public function take()
    {
        return view('pages.app.report.take');
    }

    public function preview()
    {
        return view('pages.app.report.preview');
    }

    public function create()
    {
        $categories = $this->reportCategoryRepository->getAllReportCategories();
        return view('pages.app.report.create', compact('categories'));
    }


    public function store(StoreReportRequest $request)
    {
        $data = $request->validated();
        $data['code'] = 'PAKLAPOR' . mt_rand(100000, 999999);
        $data['resident_id'] = Auth::user()->resident->id;
        $data['image'] = $request->file('image')->store('assets/report/image', 'public');

        // Analisis AI
        $aiResult = $this->aiService->analyzeDamage($data['image']);
        $data['ai_infrastructure_type'] = $aiResult['infrastructure_type'];
        $data['ai_severity']            = $aiResult['severity'];
        $data['ai_suggested_category']  = $aiResult['suggested_category'];
        $data['ai_reasoning']           = $aiResult['reasoning'];

        // Hitung urgency score
$urgencyScore = $this->aiService->calculateUrgencyScore(
    $aiResult,
    (float) $data['latitude'],
    (float) $data['longitude']
);
$data['urgency_score'] = $urgencyScore;

        $this->reportRepository->createReport($data);

        return redirect()->route('report.success');
    }




    public function analyzeImage(Request $request)
    {
        $request->validate(['image' => 'required|image|max:5120']);

        $path = $request->file('image')->store('assets/report/temp', 'public');
        $result = $this->aiService->analyzeDamage($path);

        return response()->json($result);
    }

    public function success()
    {
        return view('pages.app.report.success');
    }
}
