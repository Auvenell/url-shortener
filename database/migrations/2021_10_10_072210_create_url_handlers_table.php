<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUrlHandlersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('url_handlers', function (Blueprint $table) {
            $table->id();
            $table->string('long_url', 2048);
            $table->string('short_url', 100);
            $table->timestamp('created_at', $precision = 0);
            $table->integer('hit_count');
        });

        Schema::create('access_log', function (Blueprint $table) {
            $table->id();
            $table->integer('pair_id');
            $table->timestamps($precision = 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('url_handlers');
        Schema::dropIfExists('access_log');
    }
}
