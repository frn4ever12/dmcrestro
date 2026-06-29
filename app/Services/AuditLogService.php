<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditLogService
{
    public function log(string $action, string $description, array $data = null): void
    {
        $user = Auth::user();

        AuditLog::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'tenant_id' => $user->tenant_id ?? null,
            'restaurant_id' => $user->restaurant_id ?? null,
            'branch_id' => $user->branch_id ?? null,
            'user_id' => $user->id ?? null,
            'user_type' => $user->user_type ?? 'system',
            'action' => $action,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'data' => $data,
        ]);
    }

    public function logLogin(): void
    {
        $user = Auth::user();
        
        $this->log('login', 'User logged in', [
            'email' => $user->email,
            'user_type' => $user->user_type,
        ]);
    }

    public function logLogout(): void
    {
        $user = Auth::user();
        
        $this->log('logout', 'User logged out', [
            'email' => $user->email,
        ]);
    }

    public function logCreate(string $model, int $modelId, array $data = null): void
    {
        $this->log('create', "Created {$model} #{$modelId}", [
            'model' => $model,
            'model_id' => $modelId,
            'data' => $data,
        ]);
    }

    public function logUpdate(string $model, int $modelId, array $oldData = null, array $newData = null): void
    {
        $this->log('update', "Updated {$model} #{$modelId}", [
            'model' => $model,
            'model_id' => $modelId,
            'old_data' => $oldData,
            'new_data' => $newData,
        ]);
    }

    public function logDelete(string $model, int $modelId, array $data = null): void
    {
        $this->log('delete', "Deleted {$model} #{$modelId}", [
            'model' => $model,
            'model_id' => $modelId,
            'data' => $data,
        ]);
    }

    public function logPayment(string $action, array $data): void
    {
        $this->log('payment', "Payment {$action}", $data);
    }

    public function logExport(string $model, array $filters = null): void
    {
        $this->log('export', "Exported {$model}", [
            'model' => $model,
            'filters' => $filters,
        ]);
    }

    public function getLogs(array $filters = [])
    {
        $query = AuditLog::query();

        if (isset($filters['tenant_id'])) {
            $query->where('tenant_id', $filters['tenant_id']);
        }

        if (isset($filters['restaurant_id'])) {
            $query->where('restaurant_id', $filters['restaurant_id']);
        }

        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (isset($filters['action'])) {
            $query->where('action', $filters['action']);
        }

        if (isset($filters['from_date']) && isset($filters['to_date'])) {
            $query->whereBetween('created_at', [$filters['from_date'], $filters['to_date']]);
        }

        return $query->with('user')->latest()->paginate(50);
    }
}
