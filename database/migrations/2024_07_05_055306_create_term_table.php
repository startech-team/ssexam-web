<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTermTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('term', function (Blueprint $table) {
            $table->increments('term_id')->comment('用語ID');
            $table->integer('category_id')->comment('カテゴリID');
            $table->string('word',500)->comment('言葉');
            $table->text('explanation')->comment('説明');
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
        Schema::dropIfExists('term');
    }
}
