<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Micropost;

class FavoritesController extends Controller
{
    //
     /**
     * ユーザをフォローするアクション。
     *
     * @param  $id  相手ユーザのid
     * @return \Illuminate\Http\Response
     */
    public function store($id)
    {
        // 認証済みユーザ（閲覧者）が、 idのMicropostをふぁぼする
        \Auth::user()->favorite($id);
        // 前のURLへリダイレクトさせる
        return back();
    }

    /**
     * ユーザをアンフォローするアクション。
     *
     * @param  $id  相手ユーザのid
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // 認証済みユーザ（閲覧者）が、 idのマイクロポストをアンふぁぼする
        \Auth::user()->unfavorite($id);
        // 前のURLへリダイレクトさせる
        return back();
    }
}
