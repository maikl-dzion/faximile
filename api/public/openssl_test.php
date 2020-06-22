<?php

// createPkey();

//sign(file_get_contents('ssl/data.txt'),
//     file_get_contents('ssl/private_key.pem'));

//verify(file_get_contents('ssl/data.txt'),
//       file_get_contents('ssl/signature.dat'),
//       file_get_contents('ssl/public_key.pem'));

//function createPkey() {
//
//    $new_key_pair = openssl_pkey_new(array(
//        "private_key_bits" => 2048,
//        "private_key_type" => OPENSSL_KEYTYPE_RSA,
//    ));
//
//    openssl_pkey_export($new_key_pair, $private_key_pem);
//
//    $details = openssl_pkey_get_details($new_key_pair);
//    $public_key_pem = $details['key'];
//
//    //Сохраняем
//    file_put_contents('ssl/private_key.pem', $private_key_pem);
//    file_put_contents('ssl/public_key.pem' , $public_key_pem);
//    // file_put_contents('signature.dat', $signature);
//}
//
//
//function sign($data, $private_key_pem) {
//    //Вычисляем подпись
//    openssl_sign($data, $signature, $private_key_pem, OPENSSL_ALGO_SHA256);
//    file_put_contents('ssl/signature.dat', $signature);
//}
//
//function verify($data, $signature, $public_key_pem) {
//    $r = openssl_verify($data, $signature, $public_key_pem, "sha256WithRSAEncryption");
//    var_dump($r);
//    die;
//}

// session_start();

$data = 'ssl/data_test.txt';
// createPkey();
// sign(file_get_contents($data));
// verify(file_get_contents($data));

function createPkey() {

    $new_key_pair = openssl_pkey_new(array(
        "private_key_bits" => 2048,
        "private_key_type" => OPENSSL_KEYTYPE_RSA,
    ));

    openssl_pkey_export($new_key_pair, $private_key_pem);

    $details = openssl_pkey_get_details($new_key_pair);
    $public_key_pem = $details['key'];

    //Сохраняем
    $_SESSION['privat'] = $private_key_pem;
    $_SESSION['public'] = $public_key_pem;

    //file_put_contents('ssl/private_key.pem', $private_key_pem);
    //file_put_contents('ssl/public_key.pem' , $public_key_pem);
    // file_put_contents('signature.dat', $signature);
}


function sign($data) {
    //Вычисляем подпись
    openssl_sign($data, $signature, $_SESSION['privat'], OPENSSL_ALGO_SHA256);
    // file_put_contents('ssl/signature.dat', $signature);
    $_SESSION['sign'] = $signature;
}

function verify($data) {
    $r = openssl_verify($data,
                        $_SESSION['sign'],
                        $_SESSION['public'],
              "sha256WithRSAEncryption");
    var_dump($r);
    die;
}