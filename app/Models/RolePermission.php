<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'role',
        'permission_id',
        'is_granted'
    ];

    protected $casts = [
        'is_granted' => 'boolean',
    ];

    /**
     * Get the permission that owns the role permission
     */
    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }

    /**
     * Scope for granted permissions
     */
    public function scopeGranted($query)
    {
        return $query->where('is_granted', true);
    }

    /**
     * Scope for denied permissions
     */
    public function scopeDenied($query)
    {
        return $query->where('is_granted', false);
    }

    /**
     * Scope for specific role
     */
    public function scopeForRole($query, $role)
    {
        return $query->where('role', $role);
    }
}
