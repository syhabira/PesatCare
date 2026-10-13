<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('diseases', function (Blueprint $t) {
            $t->id();
            $t->string('name')->unique();
            $t->text('description')->nullable();
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('diseases'); }
};