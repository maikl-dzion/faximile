<?php

namespace App\Http\Controllers\Faximile;

use App\Models\Document;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Auth\JwtAuth;
use App\Models\Faximile;

class SigningDocsController extends Controller
{
    // private $model;
//    public function __construct($userId){
//        $this->model = new Faximile();
//    }

    public function createKeyPair() {

        $new_key_pair = openssl_pkey_new(array(
            "private_key_bits" => 2048,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        ));

        openssl_pkey_export($new_key_pair, $private_key_pem);

        $details = openssl_pkey_get_details($new_key_pair);
        $public_key_pem = $details['key'];

        return array(
            'private_key' => $private_key_pem,
            'public_key'  => $public_key_pem
        );
    }

    public function signFile($file, $privateKey) {
        // Подписываем документ
        openssl_sign($file, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        return $signature;
    }

    public function verifyFile($file, $signature, $publicKey) {
        $result = openssl_verify($file, $signature, $publicKey,"sha256WithRSAEncryption");
        return $result;
    }

    public function getDocument($path) {
        return file_get_contents($path);
    }

}

