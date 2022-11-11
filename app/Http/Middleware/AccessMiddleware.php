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
            $token = $request->bearerToken();


            $key = 'example_key';
            $payload = [
                'iss' => 'http://example.org',
                'aud' => 'http://example.com',
                'iat' => 1356999524,
                'nbf' => 1357000000
            ];

            /**
             * IMPORTANT:
             * You must specify supported algorithms for your application. See
             * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
             * for a list of spec-compliant algorithms.
             */
            $jwt = JWT::encode($payload, $key, 'HS256');
            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));

            print_r($decoded);

            /*
            NOTE: This will now be an object instead of an associative array. To get
            an associative array, you will need to cast it as such:
            */

            $decoded_array = (array) $decoded;

            /**
             * You can add a leeway to account for when there is a clock skew times between
             * the signing and verifying servers. It is recommended that this leeway should
             * not be bigger than a few minutes.
             *
             * Source: http://self-issued.info/docs/draft-ietf-oauth-json-web-token.html#nbfDef
             */
            JWT::$leeway = 60; // $leeway in seconds
            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
dd($decoded);



            if($token) {
                try {
                    $credentials = Helper::decodeJwt($token);
                    
                } catch(ExpiredException $e) {
                    throw new \Exception("Expired Access Token.", 500);
                    // throw $e;
                } catch(\Throwable $e) {
                    throw new \Exception("Invalid Access Token.", 500);
                    // throw $e;
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
