<?php

namespace App;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use App\Constants\ErrorCode as EC;
use App\Constants\ErrorMessage as EM;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
class Helper {


    
    // $key = 'example_key';
    // $payload = [
    //     'iss' => 'http://example.org',
    //     'aud' => 'http://example.com',
    //     'iat' => 1356999524,
    //     'nbf' => 1357000000
    // ];
    // $jwt = JWT::encode($payload, $key, 'HS256');
    // $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
    // print_r($decoded);
    // $decoded_array = (array) $decoded;
    // JWT::$leeway = 60; // $leeway in seconds
    // $decoded = JWT::decode($jwt, new Key($key, 'HS256'));


    
    static function createJwt($data = NULL, $is_refresh_token = FALSE) {
        $issued_at = time();
        $key = 'example_key';
        $payload = [
            'iss' => 'http://example.org', // Issuer of the token
            'aud' => 'http://example.com',
            'sub' => $data, // Subject of the token
            'iat' => $issued_at, // Time when JWT was issued.
            'exp' => $issued_at + 60*60*99999999999999999999999999999999
            // 'exp' => $is_refresh_token
            //     ?($issued_at + 60*60*24*30) // Waktu kadaluarsa 30 hari
            //     :($issued_at + 60*60*4) // Waktu kadaluarsa 1 jam
        ];

        JWT::$leeway = 60; // $leeway dalam detik
        return JWT::encode($payload, $key,'HS256');
    }

    static function decodeJwt($token) {
        try {        
            $key = 'example_key';
            return JWT::decode($token,new Key($key, 'HS256'));
        } catch(\Throwable $e) {
            throw $e;
        }

    }

    static function getMessageForPatner($data)
    {

        $message = json_decode($data,true);
        return isset($message['responseMessage']) ? $message['responseMessage'] : $data;
    }

    static function setErrorResponse($th){
        $headers = [
            'Access-Control-Allow-Origin'      => '*',
            'Access-Control-Allow-Methods'     => 'HEAD, POST, GET, OPTIONS, PUT, DELETE',
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Max-Age'           => '86400',
            'Access-Control-Allow-Headers'     => 'X-TIMESTAMP,X-CLIENT-KEY,X-CLIENT-SECRET,Content-Type,X-SIGNATURE,Accept,Authorization,Authorization-Customer,ORIGIN,X-PARTNER-ID,X-EXTERNAL-ID,X-IP-ADDRESS,X-DEVICE-ID,CHANNEL-ID,X-LATITUDE,X-LONGITUDE'

        ];

        $codeSt = $th->getCode() == 0 ? 500 : $th->getCode();
        $result = json_decode($th->getMessage());
        if($codeSt == 500) $result = [
            "responseCode" => $result->responseCode ?? $codeSt,
            "responseMessage" => self::getMessageForPatner($th->getMessage()),
            // "infoError" => $th
        ];
        return response()->json($result,$codeSt,$headers);
    }
}
