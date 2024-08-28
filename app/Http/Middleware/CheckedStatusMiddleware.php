<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckedStatusMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $keyName = 'user'): Response
    {
        $response =  $next($request);

        if($response instanceof JsonResponse && $response->getStatusCode() == 200){
            $data = $response->getData(true);
            $response->setData([
                'status' => 'success',
                $keyName => $data
            ]);
        } elseif($response->getStatusCode() !== 200){
            $response = $response->setData(array_merge(['success' => false],$response->getData(true)));
        }
        return $response;
    }
}
