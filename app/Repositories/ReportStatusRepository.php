<?php

namespace App\Repositories;

use App\Interfaces\ResidentRepositoryInterface;
use App\Models\Resident;

use App\Interfaces\ReportCategoryRepositoryInterface;
use App\Models\ReportCategory;
use App\Interfaces\ReportRepositoryInterface;
use App\Models\Report;
use App\Interfaces\ReportStatusRepositoryInterface;
use App\Models\ReportStatus;


class ReportStatusRepository implements ReportStatusRepositoryInterface
{
    public function getAllReportsStatuses()
    {
        return ReportStatus::all();
    }

    public function getReportById(int $id)
    {
        return ReportStatus::where('id', $id)->first();
    }

    public function createReport(array $data)
    {
        return ReportStatus::create($data);
    }

    public function updateReport(array $data, int $id)
    {
        $reportStatus = $this->getReportById($id);
        return $reportStatus->update($data);
    }

    public function deleteReport(int $id)
    {
        $reportStatus = $this->getReportById($id);
        return $reportStatus->delete();
    }
}
