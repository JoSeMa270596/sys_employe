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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users', 'id')->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
            $table->enum('status', ['active', 'inactive', 'suspended', 'on_leave'])->default('active');
            $table->date('hire_date');
            $table->foreignId('department_id')->constrained('departments', 'id')->onDelete('cascade');
            $table->timestamps();
        });
        Schema::table('departments', function (Blueprint $table) {
            $table->foreignId('chief_employee_id')->nullable()->constrained('employees')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
