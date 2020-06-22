<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Faximile;
use App\Http\Controllers\Auth\JwtAuth;

class AuthController extends Controller
{
    public function login(Request $request){

        $jwtToken = $status = '';
        $jwtAuth = new JwtAuth();
        $model   = new Faximile();

        $login    = $request->input('login');
        $password = $request->input('password');
        $data = $model->findPerson($login, 'login');
        $hash = $data['password'];
        if (password_verify($password, $hash)) {
            $jwtToken = $jwtAuth->makeJwtToken($data);
            $status = true;
        }

        $result = array(
            'data'      => $data,
            'jwt_token' => $jwtToken,
            'status'    => $status,
            'role'         => $data['role'],
            'username'     => $data['username'],
            'user_id'      => $data['id'],
            'company_id'   => $data['company_id']
        );

        return $this->respond($result, 200);
    }

    public function register($data){
        $result = array();
        return $this->respond($result, 200);
    }

    public function logout($userId){}
}