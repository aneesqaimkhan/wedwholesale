<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;
    
    // Specify connection to use 'master' database
    protected $connection = 'master';
    
    protected $fillable = [
        'name',
        'subdomain',
        'database_name',
        'database_host',
        'database_username',
        'database_password',
        'database_port',
        'is_active',
    ];
    
    protected $hidden = [
        'database_password',
    ];
}
