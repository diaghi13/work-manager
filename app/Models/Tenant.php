<?php

namespace App\Models;

use App\Exceptions\Tenant\TenantDatabaseNotProvidedException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tenant extends Model
{
    public $incrementing = false;

    protected $fillable = [
        'id',
        'selected',
        'data',
    ];

    protected function casts()
    {
        return [
            'selected' => 'boolean',
            'data' => 'array',
        ];
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function getUserCountAttribute()
    {
        return $this->users->count();
    }

    /**
     * @throws TenantDatabaseNotProvidedException
     */
    public static function switch(bool $root = false, string|null $databaseName = null): void
    {
        $database = $root ? 'mysql' : 'dynamic';

        if (!$root && !$databaseName) {
            throw new TenantDatabaseNotProvidedException();
        }

        $databaseName = $root ? env('DB_DATABASE') : $databaseName;

        config()->set("database.connections.{$database}.database", $databaseName);

        DB::setDefaultConnection($database);
        DB::reconnect($database);
    }
}
