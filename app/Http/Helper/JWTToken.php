<?php

namespace App\Http\Helper;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class JWTToken{

    public static function CreateToken($userEmail, $userID){

        $key = env('JWT_KEY');
        $payload = [
            'iss' => 'laravel-token',
            //'aud' => 'http://example.com',
            'iat' => time(),
            'exp' => time() + (60*60*24*30), // 1 month
            'userEmail' => $userEmail,
            'userID' => $userID,
            //'nbf' => 1357000000
        ];

        return JWT::encode($payload, $key, 'HS256');
        
    }

    public static function VerifyToken($token):string|object{

        try{

            if($token == null){

                return 'unauthorized';

            }
            else{
                $key = env('JWT_KEY');
                $decoded = JWT::decode($token, new Key($key, 'HS256'));
                return $decoded;
            }

        }

        catch(Exception $e){

                return 'unauthorized';

        }
    }
}