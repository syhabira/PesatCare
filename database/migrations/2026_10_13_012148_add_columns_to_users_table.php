<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $t) {
            $t->string('username', 50)->unique()->after('name');
            $t->string('role', 10)->default('murid')->index()->after('password');
            $t->string('status', 10)->default('active')->after('role');
            $t->string('email')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $t) {
            $t->dropColumn(['username', 'role', 'status']);
        });
    }
};