<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class Tenant extends Model
{
    use HasFactory;

    protected $connection = 'master';

    protected $fillable = [
        'name',
        'domain',
        'database_name',
        'database_host',
        'database_port',
        'database_username',
        'database_password',
        'company_name',
        'contact_email',
        'contact_phone',
        'address',
        'logo',
        'license_start_date',
        'license_end_date',
        'is_active',
        'settings',
    ];

    protected $casts = [
        'license_start_date' => 'date',
        'license_end_date' => 'date',
        'is_active' => 'boolean',
        'settings' => 'array',
        'database_port' => 'integer',
    ];

    /**
     * Get the master users for this tenant
     */
    public function masterUsers(): HasMany
    {
        return $this->hasMany(MasterUser::class);
    }

    /**
     * Set the database connection for this tenant
     */
    public function configureDatabaseConnection(): void
    {
        Config::set('database.connections.tenant', [
            'driver' => 'mysql',
            'host' => $this->database_host,
            'port' => $this->database_port,
            'database' => $this->database_name,
            'username' => $this->database_username,
            'password' => $this->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
        ]);

        // Set the default connection to tenant
        Config::set('database.default', 'tenant');
        
        // Clear the connection cache
        DB::purge('tenant');
        DB::reconnect('tenant');
    }

    /**
     * Check if tenant is active and license is valid
     */
    public function isLicenseValid(): bool
    {
        return $this->is_active && 
               $this->license_start_date <= now() && 
               $this->license_end_date >= now();
    }

    /**
     * Find tenant by domain
     */
    public static function findByDomain(string $domain): ?self
    {
        return static::where('domain', $domain)->first();
    }
}
