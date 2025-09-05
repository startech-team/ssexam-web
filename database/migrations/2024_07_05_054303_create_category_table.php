<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category', function (Blueprint $table) {
            $table->increments('category_id')->comment('カテゴリID');
            $table->string('category_nm')->comment('種別名');
            $table->char('category_type',1)->comment('カテゴリタイプL (1.問題、2. 勉強、3.用語)');
            $table->binary('category_icon')->comment('アイコン');
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
        Schema::dropIfExists('category');
    }
}
