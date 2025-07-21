<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'module',
        'action',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the roles that have this permission
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions', 'permission_id', 'role', 'id', 'name');
    }

    /**
     * Check if a role has this permission
     */
    public function isGrantedToRole($role)
    {
        return $this->rolePermissions()
            ->where('role', $role)
            ->where('is_granted', true)
            ->exists();
    }

    /**
     * Get role permissions relationship
     */
    public function rolePermissions()
    {
        return $this->hasMany(RolePermission::class);
    }

    /**
     * Scope for active permissions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for permissions by module
     */
    public function scopeByModule($query, $module)
    {
        return $query->where('module', $module);
    }

    /**
     * Scope for permissions by action
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Get permission by name
     */
    public static function findByName($name)
    {
        return static::where('name', $name)->first();
    }

    /**
     * Check if permission exists by name
     */
    public static function existsByName($name)
    {
        return static::where('name', $name)->exists();
    }
}
