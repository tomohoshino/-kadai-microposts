<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMicropostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('microposts', function (Blueprint $table) {
            $table->bigIncrements('id');
            //cp9.1 追加
            $table->unsignedBigInteger('user_id');
            $table->string('content');
            
            $table->timestamps();
            
            // 外部キー制約
            $table->foreign('user_id')->references('id')->on('users');
            /*$table->foreign(外部キーを設定するカラム名)->references
            (参照先のカラム名)->on(参照先のテーブル名);
            テーブルの整合性を担保するためのもの：外部キー制約
            ユーザIDとツイートを紐づけることで、
            つながりを保証し、迷子でMicropostに存在しないIDは省く*/
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('microposts');
    }
}
