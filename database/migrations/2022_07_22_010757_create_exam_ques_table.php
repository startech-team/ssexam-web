<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamQuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_ques', function (Blueprint $table) {
           
            $table->integer('exam_id')->comment('試験ID');
            $table->integer('question_id')->comment('問題ID');
            $table->integer('ord_no')->comment('順番');
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
        Schema::dropIfExists('exam_ques');
    }
}
