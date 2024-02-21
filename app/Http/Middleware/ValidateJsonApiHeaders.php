<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response as RES;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ValidateJsonApiHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->header('accept') !== 'application/vnd.api+json') {
            throw new HttpException(RES::HTTP_NOT_ACCEPTABLE, __('Not Acceptable'));
        }

        if ($request->isMethod('POST') || $request->isMethod('PATCH')) {
            if ($request->header('content-type') !== 'application/vnd.api+json') {
                throw new HttpException(RES::HTTP_UNSUPPORTED_MEDIA_TYPE, __('Unsupported media type'));
            }
        }

        $response = $next($request);

        if ($response->getStatusCode() !== RES::HTTP_NO_CONTENT && $response->getStatusCode() !== RES::HTTP_NOT_MODIFIED) {
            $response->headers->set('Content-Type', 'application/vnd.api+json');
        }

        return $response;

        /*Marca error en el linter return $next($request)->withHeaders([
            'content-type' => 'application/vnd.api+json'
        ]); */
    }
}
