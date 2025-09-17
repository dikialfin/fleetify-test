<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attendance', function (Blueprint $table) {
            $table->uuid('id')->primary(true);
            $table->string('employee_id')->nullable(false);
            $table->foreign('employee_id')->references('employee_id')->on('employee');
            $table->timestamp('clock_in')->nullable(false);
            $table->timestamp('clock_out')->nullable(false);
            $table->timestamp('created_at')->nullable(false);
            $table->timestamp('updated_at')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance');
    }
};
