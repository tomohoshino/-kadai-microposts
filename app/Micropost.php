<?php
//cp9の内容：一対多
namespace App;

use Illuminate\Database\Eloquent\Model;

class Micropost extends Model
{
    //パスワードは隠そう
      protected $fillable = ['content'];
      
       /**
     * この投稿を所有するユーザ。（ Userモデルとの関係を定義）
     **/
    public function user()
    {
        return $this->belongsTo(User::class);
    }
   
}
