<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Tabel LMS (acuan BMI-for-age). Diisi dari CSV acuan resmi yang disetujui sekolah.
        Schema::create('bmi_references', function (Blueprint $t) {
            $t->id();
            $t->enum('sex', ['L', 'P']);
            $t->unsignedSmallInteger('age_months');
            $t->decimal('l', 10, 5);
            $t->decimal('m', 10, 5);
            $t->decimal('s', 10, 5);
            $t->unique(['sex', 'age_months']);
        });

        Schema::create('screenings', function (Blueprint $t) {
            $t->id();
            $t->foreignId('student_id')->constrained()->restrictOnDelete();
            $t->foreignId('class_id')->nullable()->constrained('classes')->nullOnDelete();
            $t->foreignId('recorded_by')->constrained('users')->restrictOnDelete();
            $t->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $t->date('screened_at');
            $t->unsignedSmallInteger('age_months')->nullable();
            $t->decimal('height_cm', 4, 1);
            $t->decimal('weight_kg', 4, 1);
            $t->decimal('bmi', 4, 1);
            $t->string('weight_indicator_code', 20);
            $t->string('weight_indicator');
            $t->decimal('waist_cm', 4, 1)->nullable();
            $t->string('waist_category')->nullable();
            $t->unsignedSmallInteger('systolic')->nullable();
            $t->unsignedSmallInteger('diastolic')->nullable();
            $t->string('blood_pressure_category')->nullable();
            $t->timestamps();
            $t->index(['student_id', 'screened_at']);
            $t->index('screened_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('screenings');
        Schema::dropIfExists('bmi_references');
    }
};