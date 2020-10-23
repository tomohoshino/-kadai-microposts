<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'MicropostsController@index');

//cp6.2のルーティング
//至れり尽くせりだが、登録機能も、ログイン機能も、すべてフレームワークになっている
Route::get('sighup','Auth\RegisterController@showRegistrationForm')->name('signup.get');
Route::post('signup', 'Auth\RegisterController@register')->name('signup.post');

//7.2ルーティング
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login')->name('login.post');
Route::get('logout', 'Auth\LoginController@logout')->name('logout.get');
//9.2
Route::group(['middleware' => ['auth']], function () {
      
      //cp10.2ルーティング
    Route::group(['prefix' => 'users/{id}'], function () {
        Route::post('follow', 'UserFollowController@store')->name('user.follow');
        Route::delete('unfollow', 'UserFollowController@destroy')->name('user.unfollow');
        Route::get('followings', 'UsersController@followings')->name('users.followings');
        Route::get('followers', 'UsersController@followers')->name('users.followers');
        Route::get('favorites', 'UsersController@favorites')->name('users.favorites');    // 追加
    });
    //nameの中に入ってるのが呼び出し、link_to_routeで引き出す
    Route::resource('users', 'UsersController', ['only' => ['index', 'show']]);
    
    //prefixはこれを作る時点で決まり事と判断している
    Route::group(['prefix' => 'microposts/{id}'], function () {
        Route::post('favorite', 'FavoritesController@store')->name('favorites.favorite');
        Route::delete('unfavorite', 'FavoritesController@destroy')->name('favorites.unfavorite');
    });
    
    Route::resource('microposts', 'MicropostsController', ['only' => ['store', 'destroy']]);
});