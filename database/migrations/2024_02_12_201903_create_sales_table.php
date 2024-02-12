<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('store_id');
            $table->integer('count');
            $table->string('source');
            $table->integer('price_out');
            $table->string('name');
            $table->string('phone');
            $table->text('note');
            $table->integer('created_at');
            $table->integer('updated_at');
            $table->integer('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }
};
