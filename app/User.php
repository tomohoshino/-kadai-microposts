<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *このユーザが所有する投稿。（ Micropostモデルとの関係を定義）
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     * このユーザがフォロー中のユーザ。（ Userモデルとの関係を定義）
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *このユーザをフォロー中のユーザ。（ Userモデルとの関係を定義）
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //cp10.1
    
    /**
     * このユーザが所有する投稿。（ Micropostモデルとの関係を定義）
     */
    public function microposts()
    {
        return $this->hasMany(Micropost::class);
        
    }
    
  
    
    public function loadRelationshipCounts()
    {
         $this->loadCount(['microposts', 'followings', 'followers','favorites']);
    }
    /**
     * このユーザがフォロー中のユーザ。（ Userモデルとの関係を定義）
     */
    public function followings()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
    }

    /**
     * このユーザをフォロー中のユーザ。（ Userモデルとの関係を定義）
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'follow_id', 'user_id')->withTimestamps();
    }

    /**
     * $userIdで指定されたユーザをフォローする。
     *
     * @param  int  $userId
     * @return bool
     */
    public function follow($userId)
    {
        // すでにフォローしているかの確認
        $exist = $this->is_following($userId);
        // 相手が自分自身かどうかの確認
        $its_me = $this->id == $userId;

        if ($exist || $its_me) {
            // すでにフォローしていれば何もしない
            return false;
        } else {
            // 未フォローであればフォローする
            $this->followings()->attach($userId);
            return true;
        }
    }

    /**
     * $userIdで指定されたユーザをアンフォローする。
     *
     * @param  int  $userId
     * @return bool
     */
    public function unfollow($userId)
    {
        // すでにフォローしているかの確認
        $exist = $this->is_following($userId);
        // 相手が自分自身かどうかの確認
        $its_me = $this->id == $userId;

        if ($exist && !$its_me) {
            // すでにフォローしていればフォローを外す
            $this->followings()->detach($userId);
            return true;
        } else {
            // 未フォローであれば何もしない
            return false;
        }
    }

    /**
     * 指定された $userIdのユーザをこのユーザがフォロー中であるか調べる。フォロー中ならtrueを返す。
     *
     * @param  int  $userId
     * @return bool
     */
    public function is_following($userId)
    {
        // フォロー中ユーザの中に $userIdのものが存在するか
        return $this->followings()->where('follow_id', $userId)->exists();
    }
    
    //ここまでモデル作成
    
    //cp11
    /**
     * このユーザとフォロー中ユーザの投稿に絞り込む。
     */
    public function feed_microposts()
    {
        // このユーザがフォロー中のユーザのidを取得して配列にする
        $userIds = $this->followings()->pluck('users.id')->toArray();
        //UserがフォローしているUserの id の配列を取得、pluck() は引数として与えられたテーブルのカラムの値だけを抜き出す命令
        
        // このユーザのidもその配列に追加
        $userIds[] = $this->id;
        // それらのユーザが所有する投稿に絞り込む
        return Micropost::whereIn('user_id', $userIds);
    }
    
    //ふぁぼ一覧取得
    public function favorites()
    {
            
        return $this->belongsToMany(Micropost::class, 'favorites','user_id', 'micropost_id')->withTimestamps();
        
    }
    
    //ふぁぼ追加
    public function favorite($micropostId)
    {
        // すでにお気に入りに追加しているかの確認
        $exist = $this->is_favoriting($micropostId);

        if ($exist) {
            // すでにお気に入りしていれば何もしない
            return false;
        } else {
            // お気に入りにまだ追加していなかったら追加する。
            $this->favorites()->attach($micropostId);
            return true;
        }
    }

    
    //ふぁぼ削除
    public function unfavorite($micropostId)
    {
            // すでにお気に入りに追加しているかの確認
        $exist = $this->is_favoriting($micropostId);

        if ($exist) {
            // すでにお気に入りしていれば何もしな 
            $this->favorites()->detach($micropostId);
            return true;
        } else {
            return false;
        }
    }
        
    public function is_favoriting($micropostId)
    {
         // ふぁぼ中投稿の中に $micropostIdのものが存在するか
        return $this->favorites()->where('micropost_id',$micropostId)->exists();
    }
 
}
