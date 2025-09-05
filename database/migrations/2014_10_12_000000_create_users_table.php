<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('users', function (Blueprint $table) {
            
            $table->id()->comment('ID');
            $table->string('name')->comment('氏名');
            $table->string('email')->comment('メール');
            $table->boolean('is_admin')->comment('権限フラグ（1:管理者、2:一般ユーザ）');
            $table->string('password')->comment('パスワード');
            $table->integer('group_id')->nullable()->comment('グループID');
            $table->char('status', 1)->comment('ステータス（0:無効、1:有効）');
            $table->rememberToken()->comment('トークン');
            $table->binary('profile_image')->nullable()->comment('プロファイル画像');
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
        Schema::dropIfExists('users');
    }
}


