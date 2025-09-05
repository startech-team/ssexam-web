<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {           

            $table->increments('question_id')->comment('問題ID');  
            $table->integer('question_type')->comment('カテゴリID');
            $table->char('title')->comment('タイトル');
            $table->text('body')->comment('問題内容');
            $table->text('option1')->comment('選択肢１');
            $table->text('option2')->comment('選択肢２');
            $table->text('option3')->nullable()->comment('選択肢３');
            $table->text('option4')->nullable()->comment('選択肢４');
            $table->char('correct_answer',10)->comment('正当な回答');
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
        Schema::dropIfExists('questions');
    }
}
