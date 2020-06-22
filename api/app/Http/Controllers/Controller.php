<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Controllers\Auth\JwtAuth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    const JWT_TOKEN_NAME = 'X-User-Jwt-Token';
    const SMS_SEND_STATE  = false;

    protected $requestHeaders = array();
    protected $userRole = 0;
    protected $userInfo = array(
        'role'   => 0,
        'id'     => 0,
        'token'  => '',
        'company_id' => 0,
        'access'    => false,
    );

   // public function __construct(){}

    public function respond($data, $code = 200 ,$headers = []) {
        return response()->json($data, $code);
    }

    public function error($data, $title = '', $code = 200) {
        return response()->json(array('error' => $data, 'title' => $title), $code);
    }

    public function getHttpHeaders() {
        $this->requestHeaders = getallheaders();
        return $this->requestHeaders;
    }

    public function getJwtToken($tokenName = self::JWT_TOKEN_NAME) {
        $token = '';
        if(!empty($this->requestHeaders[$tokenName]))
            $token = trim($this->requestHeaders[$tokenName]);
        return $token;
    }

    protected function verify($token) {
        $jwtAuth = new JwtAuth();
        $result = $jwtAuth->verifyToken($token);
        if(!empty($result))
            return $result;
        return false;
    }

    protected function userAccess() {
        $token    = $this->getJwtToken();
        $userInfo = $this->verify($token);
        if(!empty($userInfo['id'])) {
            foreach ($userInfo as $key => $value) {
                if(isset($this->userInfo[$key])) {
                    $this->userInfo[$key] = $value;
                }
            }
            $this->userInfo['access'] = true;
            $this->userRole = $this->userInfo['role'];
        }
    }

    public function isValue($arr, $fname) {
       $result = '';
       if(!empty($arr[$fname]))
           $result = $arr[$fname];
       return $result;
    }

    public function getCurrentDate(){
        date_default_timezone_set('Europe/Moscow');
        $today = date("Y-m-d H:i:s");
        return $today;
    }

    public function getUploadFilesUrl() {
        $rootUrl = 'http://' .$_SERVER['SERVER_NAME'] . '/faximile/api/storage/app/public/';
        $urls =  array(
            'root_url'     => $rootUrl,
            'documents'    => 'documents',
            'users'        => 'users',
            'sign_company' => 'sign_company',
            'sign_user'    => 'sign_user',
        );
        return $this->respond($urls, 200);
    }

    public function testToken() {
        return 'eyJhbGciOiJIUzI1NiIsInR5cGUiOiJKV1QifQ==.eyJpZCI6MiwidXNlcm5hbWUiOiJcdTA0MjVcdTA0NDNcdTA0MzRcdTA0MzBcdTA0MzlcdTA0MzFcdTA0MzVcdTA0NDBcdTA0MzNcdTA0MzVcdTA0M2RcdTA0M2VcdTA0MzIgXHUwNDE4XHUwNDQxXHUwNDNjXHUwNDMwXHUwNDM4XHUwNDNiIiwibG9naW4iOiJpc21hIiwicGFzc3dvcmQiOiIxMjM0Iiwicm9sZSI6MywiY3JlYXRlX2F0IjpudWxsLCJ1cGRhdGVfYXQiOm51bGwsInBvc3QiOiJcdTA0MTNcdTA0NDBcdTA0NDNcdTA0MzdcdTA0NDdcdTA0MzhcdTA0M2EiLCJjb21wYW55X2lkIjoxLCJwaG9uZSI6bnVsbCwiZW1haWwiOm51bGx9.OUlXbVQ+QLLA48kpVAe/wV1+T67jP/EKrdSLn9iYECo=';
    }
}
