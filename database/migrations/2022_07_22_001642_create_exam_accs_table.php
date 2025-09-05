<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamAccsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_accs', function (Blueprint $table) {          
            $table->integer('exam_id')->comment('試験ID');
            $table->integer('acc_id')->comment('ユーザーID');
            $table->char('take_exam_dt',10)->nullable()->comment('受験日');
            $table->char('remaing_time',10)->nullable()->comment('残り時間');
            $table->char('take_exam_end_flg',1)->nullable()->comment('試験終了フラグ（1:終了）');
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
        Schema::dropIfExists('exam_accs');
    }
}
