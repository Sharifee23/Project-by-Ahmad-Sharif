<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            "role-list",
            "role-create",
            "role-edit",
            "role-delete",
            "role-view",
            "product-list",
            "product-create",
            "product-edit",
            "product-delete",
            "product-view",
            "market-list",
            "market-create",
            "market-edit",
            "market-delete",
            "state-list",
            "state-create",
            "state-edit",
            "state-delete",
            "record-list",
            "record-create",
            "record-edit",
            "record-delete",
            "user-list",
            "user-create",
            "user-edit",
            "user-delete",
            "user-view",
            "dasboard-cards",
            "price-trend-chart",
            "recent-activities",
            "recent-record",
            "competative-price"

        ];

        foreach ($permissions as $key => $permission){
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }
    }
}

