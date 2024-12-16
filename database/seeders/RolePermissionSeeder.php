<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Create roles
         $adminRole = Role::create(['name' => 'admin']);
         $teacherRole = Role::create(['name' => 'enseignant']);
         $studentRole = Role::create(['name' => 'etudiant']);
 
         // Create permissions
         $permissions = [
             'create compte rendu',
             'edit compte rendu',
             'delete compte rendu',
             'create devoir',
             'edit devoir',
             'delete devoir',
         ];
 
         foreach ($permissions as $permission) {
             Permission::create(['name' => $permission]);
         }
 
         // Assign permissions to roles
         $adminRole->givePermissionTo(Permission::all());
         $teacherRole->givePermissionTo(['create compte rendu', 'edit compte rendu', 'delete compte rendu', 'create devoir', 'edit devoir', 'delete devoir']);
         $studentRole->givePermissionTo(['create compte rendu', 'edit compte rendu', 'delete compte rendu']);
    }
}
