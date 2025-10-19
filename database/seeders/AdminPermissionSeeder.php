<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // DB::unprepared(
        //     'DELETE FROM model_has_permissions mhp WHERE NOT EXISTS ( SELECT 1 FROM permissions p WHERE mhp.permission_id = p.id);
        //         DELETE FROM role_has_permissions rhp WHERE NOT EXISTS ( SELECT 1 FROM permissions p WHERE rhp.permission_id = p.id);
        //         SET FOREIGN_KEY_CHECKS=0;
        //         TRUNCATE permissions;
        //         SET FOREIGN_KEY_CHECKS=1;'
        // );

        DB::beginTransaction();

        // fetch models names and concat it with permissions arr
        // create permissions
        $modules = [
            'Address',
            'Admin',
            'Appointment',
            'Taxonomy',
            'City',
            'Comment',
            'Coupon',
            'Device',
            'History',
            'Invoice',
            'Notifications',
            'Offer',
            'Order',
            'OrderItem',
            'Page',
            'Permission',
            'Post',
            'Product',
            'Review',
            'Role',
            'Setting',
            'SupportMessage',
            'ShareLink',
            'User',
            'Vendor',
            'WorkDay',
            'WalletChargingPackage',
            'WheelOfFortune',
            'Influencer',
            'SocialMedia',
            'SupportMessageType',
            'Consultation',
            'Staff',
            'subVendor'
            
        ];

        $permissions_arr = [
            'view_',
            'create_',
            'update_',
            'delete_',
            'approve_',
            'reject_',
            'cancel_',
            'send_',
        ];

        $permissions = [];
        foreach ($modules as $module) {
            foreach ($permissions_arr as $permission) {
                array_push($permissions, Permission::firstOrCreate([
                    'name' => $permission . Str::snake(str_plural($module)),
                    'guard_name' => 'admin',
                ]));
            }
        }

        Role::findByName('superadmin', 'admin')->syncPermissions($permissions);

        DB::commit();
    }
}
