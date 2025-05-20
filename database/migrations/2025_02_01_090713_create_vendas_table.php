<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('vendas', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id');
        $table->string('como_nos_conheceu')->nullable();
        $table->string('nomeCliente')->nullable();
        $table->string('cpfCliente', 14)->nullable();
        $table->date('dataVenda');
        $table->decimal('valorVendaAvista', 10, 2)->nullable();
        $table->date('dataPagamentoFinanciamento')->nullable();
        $table->string('veiculo')->nullable();
        $table->string('placa', 8)->nullable();
        $table->decimal('valorTotalVeiculo', 10, 2);
        $table->decimal('valorTotalFinanciamento', 10, 2)->nullable();
        $table->string('financeira')->nullable();
        $table->enum('status', ['pendente', 'aprovado', 'cancelado'])->default('pendente');
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
        Schema::dropIfExists('vendas');
    }
}
