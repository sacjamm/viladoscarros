<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('ramal')->nullable();
            $table->string('source')->nullable();
            $table->string('name');
            $table->string('phone');
            $table->string('phone2')->nullable();
            $table->string('email')->nullable();
            $table->string('city')->nullable();
            $table->string('neighbourhood')->nullable();
            $table->string('prop_ref')->nullable();
            $table->string('type_negotiation')->nullable(); // e.g., 'Compra'
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', 15, 2)->nullable();
            $table->string('url')->nullable();
            $table->text('body')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('leads');
    }
}
