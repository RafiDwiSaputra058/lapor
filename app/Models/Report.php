<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'resident_id',
        'report_category_id',
        'title',
        'description',
        'image',
        'latitude',
        'longitude',
        'address',
        'ai_infrastructure_type',
        'ai_severity',
        'ai_suggested_category',
        'ai_reasoning',
        'urgency_score',
    ];
    //satu laporan dimiliki oleh satu resident
    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function reportCategory()
    {
        return $this->belongsTo(ReportCategory::class);
    }

    public function reportStatuses()
    {
        //satu laporan bisa punya banyak status
        return $this->hasMany(ReportStatus::class);
    }
}
