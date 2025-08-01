<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('apr_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('apr_id')->constrained('aprs')->onDelete('cascade');
            $table->integer('numero_item');
            $table->text('tarefa');
            $table->text('risco');
            $table->text('recomendacao')->comment('RECOMENDAÇÕES E/OU PROCEDIMENTOS');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('apr_items');
    }
};