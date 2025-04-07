<?php

use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $repo = \App\Repositories\RoleUserRepository::inst();
        $roleRepo = \App\Repositories\RoleRepository::inst();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $repo->truncate();
        $roleRepo->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $role_admin = new Role();
        $role_admin->name = 'admin';
        $role_admin->description = 'A Admin User';
        $role_admin->save();

        $role_partner = new Role();
        $role_partner->name = 'partner';
        $role_partner->description = 'A Partner User';
        $role_partner->save();

        $role_customer = new Role();
        $role_customer->name = 'customer';
        $role_customer->description = 'A Customer User';
        $role_customer->save();
    }
}
