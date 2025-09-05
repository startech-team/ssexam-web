<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupTbTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_tbs', function (Blueprint $table) {           

            $table->increments('group_id')->comment('グループID');      
            $table->char('group_name')->comment('グループ名');
            $table->integer('order')->comment('並び順');
            $table->string('group_icon')->nullable()->comment('グループアイコン');
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
        Schema::dropIfExists('group_tb');
    }
}
