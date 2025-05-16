<?php
/**
* An example CORS-compliant method. It will allow any GET, POSt, or OPTIONS request from any origin.
*
* In a production environment, you prabably want to be more restrictive, but this give you the general
* idea of what is involved. For the nitty-gritty low-down, read:
* 
* - https://developer.mozilla.org/en/HTTP_access_control
* - https://fetch.spec.whatwg.org/#http-cors-protocol
*
*/

function cors() {

    // // Allow from any origin
    // if(isset($_SERVER['HTTP_ORIGIN'])) {
    //     // Decide if the origin in $_SERVER ['HTTP_ORIGIN'] is one
    //     // you want to allow, and if so:
    //     header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    //     header('Access-Control-Allow-Credentials: true');
    //     header('Access-Control-Max-Age: 86400');
    // }

    // // Access-Control headers are received during OPTIONS requests
    // if($_SERVER['REQUEST_METHOD'] == 'OPTIONS'){

    //     if(isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
    //         // may also be using PUT, PATCH, HEAD etc
    //         header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    //     if(isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
    //         header("Access-Control-Allow-Haeders: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    //     exit(0);
    // }

    // echo "You have CORS !";

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    // header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header('Access-Control-Allow-Headers: X-Requested-Width');
}