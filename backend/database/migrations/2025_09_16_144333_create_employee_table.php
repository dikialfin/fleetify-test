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
        Schema::create('employee', function (Blueprint $table) {
            $table->uuid('id')->primary(true);
            $table->string('employee_id')->unique()->nullable(false);
            $table->uuid('departement_id')->nullable(false);
            $table->foreign('departement_id')->references('id')->on('departement');
            $table->string('name',255)->nullable(false);
            $table->text('address')->nullable(false);
            $table->timestamp('created_at')->nullable(false);
            $table->timestamp('updated_at')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee');
    }
};
