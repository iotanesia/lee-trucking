<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helper;
use App\Query\User;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Str;
use App\Exceptions\CustomException;
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
            if($token) {
                try {
                    if($token){
                        $credentials = Helper::decodeJwt($token);
                        dd($credentials);
                    }
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
