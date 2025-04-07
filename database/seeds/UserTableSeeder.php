<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $repo = \App\Repositories\UserRepository::inst();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $repo->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $role_admin = Role::where('name', 'admin')->first();
        $role_partner = Role::where('name', 'partner')->first();
        $role_customer = Role::where('name', 'customer')->first();

        $admin = new User();
        $admin->first_name = 'Admin';
        $admin->last_name = 'Name';
        $admin->email = 'admin@booteam.co';
        $admin->password = bcrypt('booteam123');
        $admin->save();
        $admin->roles()->attach($role_admin);

        $partner = new User();
        $partner->first_name = 'Partner';
        $partner->last_name = 'Name';
        $partner->email = 'partner@booteam.co';
        $partner->password = bcrypt('booteam123');
        $partner->save();
        $partner->roles()->attach($role_partner);

        $customer = new User();
        $customer->first_name = 'Customer';
        $customer->last_name = 'Name';
        $customer->email = 'customer@booteam.co';
        $customer->password = bcrypt('booteam123');
        $customer->save();
        $customer->roles()->attach($role_customer);
    }
}
