<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('apr_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('apr_id')->constrained('aprs')->onDelete('cascade');
            $table->string('re')->comment('Registro do Empregado');
            $table->string('nome');
            $table->string('funcao');
            $table->timestamp('acknowledged_at')->nullable()->comment('Data/Hora do aceite/visto');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('apr_participants');
    }
};
