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
        Schema::create('providers', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name');
            $table->string('type');
            $table->integer('type_id');
            $table->string('icon', 500);
            $table->integer('min_summ');
            $table->integer('created_at');
            $table->integer('updated_at');
            $table->integer('last_parser');
            $table->integer('is_group');
            $table->integer('user_id');
            $table->integer('favorites')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('providers');
    }
};
