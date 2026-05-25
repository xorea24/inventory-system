<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();

            $table->unique(['warehouse_id', 'name']);
        });

        Schema::create('aisles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zone_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();

            $table->unique(['zone_id', 'name']);
        });

        Schema::create('bins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aisle_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('barcode')->unique();
            $table->timestamps();

            $table->unique(['aisle_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bins');
        Schema::dropIfExists('aisles');
        Schema::dropIfExists('zones');
    }
};
