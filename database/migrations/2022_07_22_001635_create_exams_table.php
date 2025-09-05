<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {          

            $table->increments('exam_id')->comment('試験ID');
            $table->char('exam_nm')->comment('試験名');
            $table->char('start_dt',10)->comment('有効期限開始');
            $table->char('end_dt',10)->comment('有効期限終了');
            $table->char('duration',5)->comment('期間');
            $table->char('win_rate')->comment('合格率');
            $table->integer('exam_type')->comment('種類');
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
        Schema::dropIfExists('exams');
    }
}
