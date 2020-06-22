<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MainController extends Controller
{
    public function index(){
        $data = ['message' => 'Create success'];
        return $this->respond($data, 200);
    }

    public function apiList(){
        $file = 'api-list.txt';
        $aliUriList  = file($file);
        lg($aliUriList);
        return $this->respond($aliUriList, 200);
    }
}
