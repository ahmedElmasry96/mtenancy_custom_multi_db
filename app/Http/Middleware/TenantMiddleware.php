<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use App\Services\DatabaseService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $subdomain = $request->getHost();
        if($subdomain && $subdomain != 'localhost') {
            $tenant = Tenant::where('subdomain', $subdomain)->first();
            if ($tenant) {
                (new DatabaseService())->connectToDb($tenant);
                // database migration
            } else {
                abort(404);
            }
        }
        return $next($request);
    }
}
