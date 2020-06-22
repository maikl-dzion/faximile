<?php

namespace App\Http\Controllers\Faximile;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

use App\Http\Controllers\Auth\JwtAuth;
use App\Http\Controllers\Auth\AuthController;
use App\Models\Message;

class ChatMessageController extends Controller
{
    private $model;

    public function __construct(){
        $this->getHttpHeaders();
        $this->userAccess();
        $this->model = new Message();
    }


    public function getChatUsers($userId = 0){
        $query = "SELECT * FROM person 
                  WHERE role IN(2, 4) 
                  AND id != {$userId}";
        $result = $this->model->querySelect($query);
        return $this->respond($result, 200);
    }

    public function getChatMessages(){
        $query = "SELECT * FROM message";
        $result = $this->model->querySelect($query);
        return $this->respond($result, 200);
    }

    public function getUserMessages($userId){
        $query = "SELECT * FROM message 
                  WHERE send_user_id = {$userId} 
                  OR    client_user_id = {$userId} 
                  ORDER BY id DESC";
        $result = $this->model->querySelect($query);
        return $this->respond($result, 200);
    }

    public function getNoReadMessages($sendUserId, $clientUserId){
//        $query = "SELECT * FROM message
//                  WHERE  send_user_id   = {$sendUserId}
//                  AND    client_user_id = {$clientUserId}
//                  AND    read_state IS NULL
//                  ORDER BY id DESC";

        $query = "SELECT send_user_id FROM message 
                  WHERE  read_state IS NULL
                  AND    send_user_id != {$sendUserId}
                  AND    client_user_id = $clientUserId";

        $result = array();
        $items  = $this->model->querySelect($query);

        foreach ($items as $key => $value) {
            if(!empty($result[$value->send_user_id])) {
                $result[$value->send_user_id]++;
            } else {
                $result[$value->send_user_id] = 1;
            }
        }
        // lg($result);
        return $this->respond($result, 200);
    }

    public function getPersonalMessages($userIdOne, $userIdTwo) {
        $query = "SELECT m.*,
                         p.id user_id,
                         p.username,
                         
                         p2.id client_id,
                         p2.username client_name
  
                  FROM message m
                  
                  LEFT JOIN person p  ON p.id = m.send_user_id
                  LEFT JOIN person p2 ON p2.id = m.client_user_id
                  
                  WHERE 
                  (send_user_id = {$userIdOne} AND 
                  client_user_id = {$userIdTwo} ) 
                  OR 
                  (send_user_id = {$userIdTwo} AND 
                  client_user_id = {$userIdOne} ) 
                  
                  ORDER BY id DESC";
        $result = $this->model->querySelect($query);

        if(!empty($result)) {
            foreach ($result as $key => $item) {
                if(!$item->read_state) {
                    $where = array($item->id, $userIdTwo);
                    $this->model->changeReadState($where);
                }
            }
        }

        return $this->respond($result, 200);
    }



    public function sendMessage(Request $request) {

        $sendId   = $request->input('send_user_id');
        $clientId = $request->input('client_user_id');
        $text     = $request->input('text');

        $data = array(
            'send_user_id'    => $sendId,
            'client_user_id'  => $clientId,
            'create_at'       => $this->getCurrentDate(),
            'text'            => $text);

        $result = $this->model->addItem('message', $data);
        return $this->respond($result, 200);
    }


}

// $tokenUrl = 'http://lite.ru/faximile/public/faximile/person-list/
              //eyJhbGciOiJIUzI1NiIsInR5cGUiOiJKV1QifQ==.eyJpZCI6MiwidXNlcm5hbWUiOiJcdTA0MjVcdTA0NDNcdTA0MzRcdTA0MzBcdTA0MzlcdTA0MzFcdTA0MzVcdTA0NDBcdTA0MzNcdTA0MzVcdTA0M2RcdTA0M2VcdTA0MzIgXHUwNDE4XHUwNDQxXHUwNDNjXHUwNDMwXHUwNDM4XHUwNDNiIiwibG9naW4iOiJpc21hIiwicGFzc3dvcmQiOiIxMjM0Iiwicm9sZSI6MywiY3JlYXRlX2F0IjpudWxsLCJ1cGRhdGVfYXQiOm51bGx9.7lvgqQCxr18yvl0DSnpwM2dtbUDhpSWA2+hWxk4B7l0=';