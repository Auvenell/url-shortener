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
            $table->string('long_url', 2048)->default('');
            $table->string('short_url', 100)->default('');
            $table->tinyInteger('status')->default('1');
            $table->integer('hit_count')->default(0);
            $table->timestamp('created_at', $precision = 0)->nullable();
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
    }
}
