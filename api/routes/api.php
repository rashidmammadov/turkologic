<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

header('Access-Control-Allow-Origins:*');
header('Access-Control-Allow-Methods:*');

Route::group(['middleware' => 'cors', 'prefix' => '/v1'], function () {
    Route::post('/editor', 'EditorController@post');
    Route::put('/editor', 'EditorController@put');

    Route::get('/languages', 'LanguageController@get');

    Route::get('/tdk', 'TDKController@get');

    Route::get('/lexemes', 'LexemeController@getLexemesByLanguage');
    Route::get('/lexeme', 'LexemeController@getLexemeById');

    Route::get('/semantics', 'SemanticsController@getSemanticsById');

    Route::get('/search', 'SearchController@get');

    Route::get('/report', 'ReportController@get');
});
