<?php

use Illuminate\Database\Seeder;

class SuperadminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {    
        if(!DB::table('user')->whereLogin('superadmin')->exists())
            DB::table('user')->insert([
                'name' => 'SuperAmdin',
                'login' => 'superadmin',
                'password' => bcrypt('Ohcasey!@#'),
                'superadmin' => 1
            ]);
    }
}
