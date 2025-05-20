<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBancosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('bancos', function (Blueprint $table) {
        $table->id();
        $table->string('banco');
        $table->decimal('totalFinanciado', 10, 2);
        $table->decimal('porcentagem', 5, 2);
        $table->decimal('totalBruto', 10, 2);
        $table->decimal('totalLiquido', 10, 2);
        $table->decimal('desconto', 10, 2)->nullable();
        $table->enum('status', ['ativo', 'inativo'])->default('ativo');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bancos');
    }
}
