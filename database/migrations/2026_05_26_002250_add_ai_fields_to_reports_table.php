<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->string('ai_infrastructure_type')->nullable()->after('address');
            $table->enum('ai_severity', ['Ringan', 'Sedang', 'Berat'])->nullable()->after('ai_infrastructure_type');
            $table->string('ai_suggested_category')->nullable()->after('ai_severity');
            $table->text('ai_reasoning')->nullable()->after('ai_suggested_category');
            $table->integer('urgency_score')->nullable()->after('ai_reasoning');
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn([
                'ai_infrastructure_type',
                'ai_severity',
                'ai_suggested_category',
                'ai_reasoning',
                'urgency_score',
            ]);
        });
    }
};
