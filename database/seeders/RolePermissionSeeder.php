<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'content.view', 'content.create', 'content.update', 'content.delete', 'content.publish',
            'engagement.view', 'engagement.update',
            'structure.view', 'structure.update',
            'system.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $superAdmin = Role::findOrCreate('super_admin', 'web');
        $superAdmin->syncPermissions(Permission::all());

        $admin = Role::findOrCreate('admin', 'web');
        $admin->syncPermissions($permissions);

        $editor = Role::findOrCreate('editor', 'web');
        $editor->syncPermissions([
            'content.view', 'content.create', 'content.update', 'content.publish',
            'engagement.view',
        ]);

        $hr = Role::findOrCreate('hr', 'web');
        $hr->syncPermissions(['content.view', 'engagement.view', 'engagement.update']);

        $user = User::query()->where('email', 'admin@iksaudi.com')->first();
        $user?->assignRole('super_admin');
    }
}
