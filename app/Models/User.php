<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'company',
        'address',
        'role', // Keep for backward compatibility
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get the roles that belong to the user.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role');
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles()->where('slug', $role)->exists();
        }
        
        return $this->roles->contains($role);
    }

    /**
     * Check if user has any of the given roles.
     */
    public function hasAnyRole(array $roles)
    {
        return $this->roles()->whereIn('slug', $roles)->exists();
    }

    /**
     * Get all permissions for the user through their roles.
     */
    public function permissions()
    {
        return $this->roles()->with('permissions')->get()->pluck('permissions')->flatten()->unique('id');
    }

    /**
     * Check if user has a specific permission.
     */
    public function hasPermission($permission)
    {
        // Backward compatibility: Users with old 'admin' role have all permissions
        if ($this->role === 'admin' && !$this->roles()->exists()) {
            return true;
        }
        
        // Check direct role-based permissions
        foreach ($this->roles as $role) {
            if ($role->hasPermission($permission)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Check if user has any of the given permissions.
     */
    public function hasAnyPermission(array $permissions)
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Assign roles to user.
     */
    public function assignRoles(array $roles)
    {
        $roleIds = Role::whereIn('slug', $roles)->pluck('id');
        $this->roles()->sync($roleIds);
    }

    /**
     * Check if user is admin (for backward compatibility).
     */
    public function isAdmin()
    {
        return $this->hasRole('admin') || $this->role === 'admin';
    }

    /**
     * Check if user is manager (for backward compatibility).
     */
    public function isManager()
    {
        return $this->hasRole('manager') || $this->role === 'manager';
    }
}
