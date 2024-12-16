<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class DatabaseService
{

    public function createTenantDb($dbName): void
    {
        DB::statement("Create Database " . $dbName);
    }

}
