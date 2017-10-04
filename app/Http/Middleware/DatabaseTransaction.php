<?php

namespace App\Http\Middleware;

use Closure;
use DB;

class DatabaseTransaction
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        DB::beginTransaction();

        try {
            $response = $next($request);

            if ($response->exception)
                throw $response->exception;
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }

        DB::commit();

        return $response;
    }
}
