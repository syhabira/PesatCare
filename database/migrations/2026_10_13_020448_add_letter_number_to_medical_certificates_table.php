<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('medical_certificates', function (Blueprint $t) {
            $t->string('letter_number', 100)->nullable()->after('visit_id');
        });
    }

    public function down(): void
    {
        Schema::table('medical_certificates', function (Blueprint $t) {
            $t->dropColumn('letter_number');
        });
    }
};