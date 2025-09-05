<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamAccDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_acc_details', function (Blueprint $table) {         

            $table->integer('exam_id')->comment('試験ID');
            $table->integer('acc_id')->comment('ユーザーID');
            $table->integer('question_id')->comment('問題ID');
            $table->char('my_answer',1)->nullable()->comment('回答');
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
        Schema::dropIfExists('exam_acc_details');
    }
}
