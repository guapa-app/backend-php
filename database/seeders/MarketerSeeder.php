<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class MarketerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();
        try {
            $modules = [
                'Influencer',
                'Coupon',
                'ShareLink',
                'SocialMedia',
                'SupportMessageType',
            ];
            $permissions_arr = ['view_', 'create_', 'update_', 'delete_'];
            $permissions = [];

            foreach ($modules as $module) {
                foreach ($permissions_arr as $permission) {
                    $permissions[] = Permission::create([
                        'name' => $permission . Str::snake(Str::plural($module)),
                        'guard_name' => 'admin', // Ensure the guard_name is 'admin'
                    ]);
                }
            }

            $marketer = Role::create(['guard_name' => 'admin', 'name' => 'marketer']);

            $marketer->givePermissionTo(['view_influencers', 'create_influencers', 'update_influencers', 'delete_influencers']);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction in case of error
            throw $e;
        }
    }
}
