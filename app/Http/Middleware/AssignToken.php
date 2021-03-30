<?php

namespace App\Http\Middleware;

use Closure;
use Google_Client;
use Google_Service_Drive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next , Google_Client $client)
    {
        $client->refreshToken(Auth::user()->refresh_token);
        $this->drive = new Google_Service_Drive($client);
        return $next($request);
    }
}
