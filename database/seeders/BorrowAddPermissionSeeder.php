<?php

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use App\Enums\RoleEnum;
use App\Models\Role;

class BorrowAddPermissionSeeder extends Seeder
{
    protected $permissionSlugs;
    protected $moduleName = "BorrowingRecords";

    public function __construct() {
        $this->permissionSlugs = PermissionEnum::Slugs->getAll();
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert permissions for the "User" module
        Permission::insert([
            [
                "module_name" => $this->moduleName,
                "sub_module_name" => "Create",
                "slug" => $this->permissionSlugs["borrowing"]["create"],
            ],
            [
                "module_name" => $this->moduleName,
                "sub_module_name" => "View",
                "slug" => $this->permissionSlugs["borrowing"]["view"],
            ],
            [
                "module_name" => $this->moduleName,
                "sub_module_name" => "List",
                "slug" => $this->permissionSlugs["borrowing"]["list"],
            ],
            [
                "module_name" => $this->moduleName,
                "sub_module_name" => "Update",
                "slug" => $this->permissionSlugs["borrowing"]["update"],
            ],
        ]);
        // Attach permissions to Super Admin role
        $permissions = Permission::where('module_name', 'BorrowingRecords') // Ensure case-sensitivity is correct
            ->whereNotIn('slug', [
            $this->permissionSlugs["borrowing"]["create"],
            $this->permissionSlugs["borrowing"]["update"],
            ])
            ->pluck("id");

        $superAdminRole = Role::find(RoleEnum::SuperAdminID->value); // Make sure this returns correct value
        if ($superAdminRole) {
            $superAdminRole->permissions()->attach($permissions);
        }
        // Attach all permissions to Admin role as well
        $adminRole = Role::find(RoleEnum::AdminID->value);
        if ($adminRole) {
            $adminRole->permissions()->attach($permissions); // Attach all permissions
        }
        // Attach "View" permission to User role
        $viewPermission = Permission::whereIn('slug', [
            $this->permissionSlugs["borrowing"]["create"],
            $this->permissionSlugs["borrowing"]["update"],
        ])->pluck('id');

        $userRole = Role::find(RoleEnum::UserID->value);
        if ($userRole && $viewPermission) {
            $userRole->permissions()->attach($viewPermission);
        }
    }
}
