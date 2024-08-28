<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckedStatusCollectionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        $data = json_decode($response->getContent(), true);

        if ($response instanceof JsonResponse && $response->getStatusCode() == 200) {
            $response->setContent(json_encode(array_merge(['success' => true], $data)));

        } else if ($response->getStatusCode() !== 200) {
            $response->setContent(json_encode(array_merge(['success' => false], $data)));
        }

        return $response;
    }
}
