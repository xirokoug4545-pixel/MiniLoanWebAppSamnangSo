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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_code',20)->unique();
            $table->string('first_name',50);
            $table->string('last_name',50);
            $table->string('gender')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('phone',20);
            $table->string('email',100);
            $table->text('address')->nullable();
            $table->string('city',100);
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
