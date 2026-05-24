<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\ReportCategoryRepositoryInterface;
use App\Interfaces\ReportRepositoryInterface;

class HomeController extends Controller
{
    private ReportCategoryRepositoryInterface $reportCategoryRepository;
    private ReportRepositoryInterface $reportRepository;

    public function __construct(ReportCategoryRepositoryInterface $reportCategoryRepository, ReportRepositoryInterface $reportRepository)
    {
        $this->reportCategoryRepository = $reportCategoryRepository;
        $this->reportRepository = $reportRepository;
    }


    public function index()
    {
        $categories = $this->reportCategoryRepository->getAllReportCategories();
        $reports = $this->reportRepository->getLatestReports();


        return view('pages.app.home', compact('categories', 'reports'));
    }
}
