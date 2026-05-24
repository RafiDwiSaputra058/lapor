<?php

namespace App\Interfaces;

interface ReportStatusRepositoryInterface
{
    public function getAllReportsStatuses();

    public function getReportById(int $id);

    public function createReport(array $data);

    public function updateReport(array $data, int $id);

    public function deleteReport(int $id);
}
