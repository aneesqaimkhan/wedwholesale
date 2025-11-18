<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the users that have this role.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_role');
    }

    /**
     * Get the permissions for this role.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }

    /**
     * Check if role has a specific permission.
     */
    public function hasPermission($permission)
    {
        if (is_string($permission)) {
            return $this->permissions()->where('slug', $permission)->exists();
        }
        
        return $this->permissions->contains($permission);
    }

    /**
     * Assign permissions to role.
     */
    public function assignPermissions(array $permissions)
    {
        $permissionIds = Permission::whereIn('slug', $permissions)->pluck('id');
        $this->permissions()->sync($permissionIds);
    }
}
