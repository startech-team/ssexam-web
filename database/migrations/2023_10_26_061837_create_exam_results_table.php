<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_results', function (Blueprint $table) {
            $table->increments('exam_result_id')->comment('試験ID');
            $table->integer('acc_id')->comment('ユーザーID');
            $table->integer('exam_id')->comment('試験ーID');
            $table->string('status')->comment('ステータス');
            $table->string('resultmark')->comment('結果')->nullable();
            $table->string('take_exam_status')->comment('受験ステータス');
            $table->integer('win_mark')->comment('合格点');
            $table->integer('question_count')->comment('質問数');
            $table->string('mark')->comment('マーク')->nullable();
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
        Schema::dropIfExists('exam_results');
    }
}
