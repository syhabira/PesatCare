<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('visits', function (Blueprint $t) {
            $t->id();
            $t->foreignId('student_id')->nullable()->constrained('students')->restrictOnDelete();
            $t->foreignId('teacher_id')->nullable()->constrained('teachers')->restrictOnDelete();
            // snapshot kelas/unit saat kunjungan agar statistik historis tetap benar
            $t->foreignId('class_id')->nullable()->constrained('classes')->nullOnDelete();
            $t->foreignId('unit_id')->nullable()->constrained('units')->nullOnDelete();
            $t->foreignId('recorded_by')->constrained('users')->restrictOnDelete();
            $t->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $t->dateTime('visited_at');
            $t->decimal('temperature', 3, 1)->nullable();
            $t->unsignedSmallInteger('systolic')->nullable();
            $t->unsignedSmallInteger('diastolic')->nullable();
            $t->unsignedSmallInteger('pulse')->nullable();
            $t->text('complaint');
            $t->text('anamnesis')->nullable();
            $t->text('action')->nullable();
            $t->text('notes')->nullable();
            $t->enum('triage_level', ['hijau', 'kuning', 'merah'])->nullable();
            $t->text('triage_reason')->nullable();
            $t->json('triage_snapshot')->nullable();
            $t->dateTime('triage_deadline')->nullable();
            $t->enum('triage_status', ['not_required', 'pending', 'handled'])->default('not_required');
            $t->dateTime('triage_handled_at')->nullable();
            $t->dateTime('left_at')->nullable();
            $t->string('follow_up_status', 100)->nullable();
            $t->timestamps();
            $t->softDeletes();

            $t->index('visited_at');
            $t->index('triage_level');
            $t->index(['triage_status', 'triage_deadline']);
        });

        // Tepat satu pasien: siswa XOR guru
        DB::statement('ALTER TABLE visits ADD CONSTRAINT chk_visits_one_patient CHECK ((student_id IS NULL) <> (teacher_id IS NULL))');

        Schema::create('visit_diseases', function (Blueprint $t) {
            $t->id();
            $t->foreignId('visit_id')->constrained()->cascadeOnDelete();
            $t->foreignId('disease_id')->constrained()->restrictOnDelete();
            $t->unique(['visit_id', 'disease_id']);
        });

        Schema::create('visit_medicines', function (Blueprint $t) {
            $t->id();
            $t->foreignId('visit_id')->constrained()->cascadeOnDelete();
            $t->foreignId('medicine_batch_id')->constrained('medicine_batches')->restrictOnDelete();
            $t->unsignedInteger('quantity');
            $t->string('notes')->nullable();
            $t->timestamps();
        });

        Schema::create('medicine_stock_movements', function (Blueprint $t) {
            $t->id();
            $t->foreignId('medicine_batch_id')->constrained('medicine_batches')->restrictOnDelete();
            $t->enum('type', ['initial', 'in', 'out', 'return', 'adjustment']);
            $t->integer('quantity'); // delta bertanda: negatif = keluar
            $t->unsignedInteger('balance_after');
            $t->foreignId('visit_id')->nullable()->constrained('visits')->nullOnDelete();
            $t->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $t->string('notes')->nullable();
            $t->timestamp('created_at')->useCurrent();
            $t->index(['medicine_batch_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicine_stock_movements');
        Schema::dropIfExists('visit_medicines');
        Schema::dropIfExists('visit_diseases');
        Schema::dropIfExists('visits');
    }
};