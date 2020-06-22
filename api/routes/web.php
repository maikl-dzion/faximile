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


// MAIN
Route::get('/',
    ['as' => 'main', 'uses' => 'Front\\MainController@index']);

Route::get('/api-list',
    ['as' => 'api-list', 'uses' => 'Front\\MainController@apiList']);


// FAXIMILE
// --------- GET -------
Route::get('/documents/{entity}',
    ['as' => 'documents', 'uses' => 'Faximile\\FaximileController@getDocuments']);

Route::get('/documents/{entity}/{list_name}',
    ['as' => 'documents', 'uses' => 'Faximile\\FaximileController@getDocuments']);

Route::get('/faximile/get-table-data/{table_name}',
    ['as' => 'table-info', 'uses' => 'Faximile\\FaximileController@getTableData']);

Route::get('/faximile/get-person/{field_name}/{value}',
    ['as' => 'get-person', 'uses' => 'Faximile\\FaximileController@getPerson']);

Route::get('/faximile/phone/check/{type}/{id}/{prop}',
    ['as' => 'check-phone', 'uses' => 'Faximile\\FaximileController@checkCode']);

Route::get('/uploads/files/get/urls-list',
    ['as' => 'documents', 'uses' => 'Faximile\\FaximileController@getUploadFilesUrl']);

Route::get('/documents/sign/user/{user_id}/{document_id}',
    ['as' => 'sign-document', 'uses' => 'Faximile\\FaximileController@signDocumentUser']);

Route::get('/documents/sign/user/{user_id}/{document_id}/{sign_type}',
    ['as' => 'sign-document-user-info', 'uses' => 'Faximile\\FaximileController@signDocumentUser']);

Route::get('/documents/verify/user/{document_id}',
    ['as' => 'varify-document', 'uses' => 'Faximile\\FaximileController@verifyDocumentUser']);

Route::get('/documents/user/info/docs/{user_id}',
    ['as' => 'info-document', 'uses' => 'Faximile\\FaximileController@getUserInfoDocList']);

Route::get('/documents/user/info/doc-type-list/{user_type}',
    ['as' => 'doc-type-list-document', 'uses' => 'Faximile\\FaximileController@getUserInfoDocType']);

// --------- CHAT MESSAGES -------
Route::get('/chat/user/get-chat-users/{user_id}',
    ['as'   => 'user/chat-users',
     'uses' => 'Faximile\\ChatMessageController@getChatUsers']);

Route::get('/chat/user/get-chat-messages',
    ['as'   => 'user/chat-messages',
     'uses' => 'Faximile\\ChatMessageController@getChatMessages']);

Route::get('/chat/user/get-user-messages/{user_id}',
    ['as'   => 'user/user-messages',
        'uses' => 'Faximile\\ChatMessageController@getUserMessages']);

Route::get('/chat/user/get-personal-messages/{user_id_one}/{user_id_two}',
    ['as'   => 'user/personal-messages',
     'uses' => 'Faximile\\ChatMessageController@getPersonalMessages']);

Route::get('/chat/user/get-no-read-messages/{send_user_id}/{client_user_id}',
    ['as'   => 'user/no-read-messages',
     'uses' => 'Faximile\\ChatMessageController@getNoReadMessages']);


Route::post('/post/chat/user/send-message',
    ['as'   => 'user/send-messages',
     'uses' => 'Faximile\\ChatMessageController@sendMessage']);



/////////////////////////
/////////////////////////
// --------- POST -------
// app\Http\Middleware\VerifyCsrfToken.php  здесь прописываем маршруты для post-запросов
Route::post('/post/auth/login',
    ['as' => 'login', 'uses' => 'Auth\\AuthController@login']);

Route::post('/post/person/activation',
    ['as' => 'person-activation', 'uses' => 'Faximile\\FaximileController@personActivation']);

Route::post('/post/person/register',
    ['as' => 'person-register', 'uses' => 'Faximile\\FaximileController@personRegister']);

Route::post('/post/person/change/password',
    ['as' => 'change-password', 'uses' => 'Faximile\\FaximileController@changePassword']);

Route::post('/post/person/profile/edit',
    ['as' => 'person-edit', 'uses' => 'Faximile\\FaximileController@editPerson']);

Route::post('/post/person/load/file/doc-info',
    ['as' => 'doc-info-file', 'uses' => 'Faximile\\FaximileController@loadDocInfoFile']);


Route::post('/documents/add/{add_type}',
    ['as' => 'add-item', 'uses' => 'Faximile\\FaximileController@addItem']);

Route::post('/documents/add/upload_files',
    ['as' => 'upload-files', 'uses' => 'Faximile\\FaximileController@uploadFiles']);

// ТЕСТИРОВАНИЕ ---
Route::get('/sms/send/test/{user_id}/{message}',
    ['as' => 'sms-send', 'uses' => 'Faximile\\FaximileController@sendSmsTest']);


//Route::get('/documents/{entity}',
//           'Faximile\\FaximileController@getDocuments');
//
//Route::get('/documents/{entity}/{list_name}',
//           'Faximile\\FaximileController@getDocuments');
//
//Route::get('/faximile/get-table-data/{table_name}',
//           'Faximile\\FaximileController@getTableData');
//
//// app\Http\Middleware\VerifyCsrfToken.php  здесь прописываем маршруты для post-запросов
//Route::post('/faximile/auth/login',
//    ['as' => 'auth-login', 'uses' => 'Auth\\AuthController@login']);
//// 'Auth\\AuthController@login');
//
//Route::post('/documents/add/{add_type}',
//    'Faximile\\FaximileController@addItem');
//
//Route::post('/upload/documents',
//    'Faximile\\FaximileController@uploadFiles');

