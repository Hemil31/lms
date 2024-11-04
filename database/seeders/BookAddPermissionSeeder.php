<?php

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use App\Enums\RoleEnum;
use App\Models\Role;

class BookAddPermissionSeeder extends Seeder
{
    protected $permissionSlugs;
    protected $moduleName = "Book";

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
                "slug" => $this->permissionSlugs["book"]["create"],
            ],
            [
                "module_name" => $this->moduleName,
                "sub_module_name" => "View",
                "slug" => $this->permissionSlugs["book"]["view"],
            ],
            [
                "module_name" => $this->moduleName,
                "sub_module_name" => "List",
                "slug" => $this->permissionSlugs["book"]["list"],
            ],
            [
                "module_name" => $this->moduleName,
                "sub_module_name" => "Update",
                "slug" => $this->permissionSlugs["book"]["update"],
            ],
            [
                "module_name" => $this->moduleName,
                "sub_module_name" => "Delete",
                "slug" => $this->permissionSlugs["book"]["delete"],
            ],
            [
                "module_name" => $this->moduleName,
                "sub_module_name" => "Export",
                "slug" => $this->permissionSlugs["book"]["export"],
            ],
        ]);
        // Attach permissions to Super Admin role
        $permissions = Permission::where('module_name', 'Book') // Ensure case-sensitivity is correct
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
        $viewPermission = Permission::where('slug', $this->permissionSlugs["book"]["list"],)
            ->pluck('id');

        $userRole = Role::find(RoleEnum::UserID->value);
        if ($userRole && $viewPermission) {
            $userRole->permissions()->attach($viewPermission);
        }
    }
}
