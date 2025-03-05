<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enrollment_subject', function (Blueprint $table) {
            $table->decimal('grade', 5, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('enrollment_subject', function (Blueprint $table) {
            $table->dropColumn('grade');
        });
    }
};