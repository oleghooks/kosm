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
        Schema::create('providers_items', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('provide_id');
            $table->text('text');
            $table->integer('price');
            $table->json('attachments');
            $table->integer('views');
            $table->integer('comments');
            $table->integer('likes');
            $table->integer('reposts');
            $table->integer('popular');
            $table->integer('post_id');
            $table->integer('created_at');
            $table->integer('updated_at');
            $table->integer('post_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('providers_items');
    }
};
