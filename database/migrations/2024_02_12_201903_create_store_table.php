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
        Schema::create('store', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('item_id');
            $table->integer('attach_index');
            $table->string('name', 1000);
            $table->text('text');
            $table->integer('price_in');
            $table->integer('price_out');
            $table->json('photos');
            $table->integer('created_at');
            $table->integer('updated_at');
            $table->integer('user_id');
            $table->integer('provider_id');
            $table->integer('count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store');
    }
};
