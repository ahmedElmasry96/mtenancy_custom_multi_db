<?php

namespace App\Services;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class DatabaseService
{
    public function createTenantDb($dbName): void
    {
        $databases = DB::select("SHOW DATABASES");

        $exists = false;
        foreach ($databases as $database) {
            if ($database->Database === $dbName) {
                $exists = true;
                break;
            }
        }

        if (!$exists) {
            DB::statement("CREATE DATABASE `" . $dbName . "`");
        }
    }

    public function connectToDb($tenant): void
    {
        Config::set('database.connections.tenant.database', $tenant->database);
        DB::purge('tenant');
        DB::reconnect('tenant');
        Config::set('database.default', 'tenant');
    }

    public function migrateToDb(): void
    {
        Artisan::call('migrate', [
            '--path' => 'database/migrations/tenant',
        ]);
    }

}
