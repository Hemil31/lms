<?php

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use App\Enums\RoleEnum;
use App\Models\Role;

class UserAddPermissionSeeder extends Seeder
{
    protected $permissionSlugs;
    protected $moduleName = "User";

    public function __construct() {
        // Ensure this method correctly retrieves the slugs array from PermissionEnum
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
                "slug" => $this->permissionSlugs["user"]["create"],
            ],
            [
                "module_name" => $this->moduleName,
                "sub_module_name" => "View",
                "slug" => $this->permissionSlugs["user"]["view"],
            ],
            [
                "module_name" => $this->moduleName,
                "sub_module_name" => "List",
                "slug" => $this->permissionSlugs["user"]["list"],
            ],
            [
                "module_name" => $this->moduleName,
                "sub_module_name" => "Update",
                "slug" => $this->permissionSlugs["user"]["update"],
            ],
            [
                "module_name" => $this->moduleName,
                "sub_module_name" => "Delete",
                "slug" => $this->permissionSlugs["user"]["delete"],
            ],
        ]);
        // Attach permissions to Super Admin role
        $permissions = Permission::where('module_name', 'User') // Ensure case-sensitivity is correct
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
        $viewPermission = Permission::where('slug', $this->permissionSlugs["user"]["view"])
            ->pluck('id');

        $userRole = Role::find(RoleEnum::UserID->value);
        if ($userRole && $viewPermission) {
            $userRole->permissions()->attach($viewPermission);
        }
    }
}
