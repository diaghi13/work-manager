<?php

return [
    'prefix' => 'wm_tenant_',
    'suffix' => '',

    /*
     * Create database for tenant when tenant is created
     * synced or in queue
     * Maybe you want to change this to false in production
     */
    'database_sync' => false,

    /*
     * Seed tenant database when tenant is created
     */
    'with_seeding' => true,
];
