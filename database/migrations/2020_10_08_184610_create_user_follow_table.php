<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserFollowTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //スキーマは関連そのものの意、だから関連とか関係をここで作ってるのではないか
        Schema::create('user_follow', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            //ch10で下の一文を追加
            $table->unsignedBigInteger('follow_id');
            
            $table->timestamps();
            
            //useridとfollowidが重要
            // 外部キー制約
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('follow_id')->references('id')->on('users')->onDelete('cascade');
            //CRUDに加えてユーザテーブルのデータが削除
            
            // user_idとfollow_idの組み合わせの重複を許さない←フォローの組み合わせの重複阻止
            $table->unique(['user_id', 'follow_id']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_follow');
    }
}
