<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('beneficiaries', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('id_number')->unique();
            $table->string('phone');
            $table->string('address');
            $table->integer('family_size');
            $table->decimal('monthly_income', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beneficiaries');
    }
};
