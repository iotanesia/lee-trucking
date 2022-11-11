<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helper;
use App\Query\User;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Str;
use App\Exceptions\CustomException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
       try {
        
    $key = 'example_key';
    $payload = [
        'iss' => 'http://example.org',
        'aud' => 'http://example.com',
        'iat' => 1356999524,
        'nbf' => 1357000000
    ];
    $jwt = JWT::encode($payload, $key, 'HS256');
    dd($jwt);
    $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
    print_r($decoded);
    $decoded_array = (array) $decoded;
    JWT::$leeway = 60; // $leeway in seconds
    $decoded = JWT::decode($jwt, new Key($key, 'HS256'));


            $token = $request->bearerToken();
            dd($token);
            if($token) {
                try {
                    $credentials = Helper::decodeJwt($token);
                    
                } catch(ExpiredException $e) {
                    throw new \Exception("Expired Access Token.", 500);
                    // throw $e;
                } catch(\Throwable $e) {
                    // throw $th new \Exception("Invalid Access Token.", 500);
                    throw $e;
                } catch (\Throwable $th) {
                    throw $th;
                }
                $request->current_user = $credentials->sub;
            }

            return $next($request);
       } catch (\Throwable $th) {
            return Helper::setErrorResponse($th);
       }
    }
}
