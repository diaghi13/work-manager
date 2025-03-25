<?php

namespace App\Exceptions\Tenant;

class TenantDatabaseNotProvidedException extends \Exception
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        parent::__construct('Tenant database not provided.');
    }
}
