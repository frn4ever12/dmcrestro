<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Tenant;
use Symfony\Component\HttpFoundation\Response;

class TenantMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = null;

        // Get tenant from subdomain
        $subdomain = $request->route('subdomain');
        if ($subdomain) {
            $tenant = Tenant::where('slug', $subdomain)->first();
        }

        // Get tenant from header
        if (!$tenant && $request->hasHeader('X-Tenant-ID')) {
            $tenant = Tenant::find($request->header('X-Tenant-ID'));
        }

        // Get tenant from query parameter
        if (!$tenant && $request->has('tenant_id')) {
            $tenant = Tenant::find($request->query('tenant_id'));
        }

        // Get tenant from authenticated user
        if (!$tenant && auth()->check() && auth()->user()->tenant_id) {
            $tenant = Tenant::find(auth()->user()->tenant_id);
        }

        if (!$tenant) {
            return response()->json([
                'message' => 'Tenant not found or not accessible',
            ], 404);
        }

        if (!$tenant->is_active) {
            return response()->json([
                'message' => 'Tenant account is suspended or inactive',
            ], 403);
        }

        if ($tenant->isExpired()) {
            return response()->json([
                'message' => 'Tenant subscription has expired',
            ], 403);
        }

        // Set tenant in request
        $request->merge(['tenant' => $tenant]);
        app()->instance('current_tenant', $tenant);

        // Set database connection for tenant
        $this->setTenantConnection($tenant);

        return $next($request);
    }

    protected function setTenantConnection(Tenant $tenant): void
    {
        config([
            'database.connections.tenant.database' => config('tenancy.database_prefix') . $tenant->id,
        ]);

        // For single database with tenant_id approach, we don't need to switch connection
        // Just set the tenant_id in a global scope
        if (config('tenancy.mode') === 'single_database') {
            TenantScope::addTenant($tenant->id);
        }
    }
}
