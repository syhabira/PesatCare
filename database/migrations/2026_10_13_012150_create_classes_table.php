<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('classes', function (Blueprint $t) {
            $t->id();
            $t->string('class_name');
            $t->unsignedTinyInteger('grade_level');
            $t->enum('school_level', ['SMP', 'SMA', 'SMK'])->default('SMK');
            $t->string('academic_year', 9);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
            $t->unique(['class_name', 'academic_year']);
        });
    }

    public function down(): void { Schema::dropIfExists('classes'); }
};