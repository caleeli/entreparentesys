<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $row = [
            'name' => 'Admin',
            'slug' => 'admin',
            'description' => 'Role para administradores', // optional
            'level' => 1, // optional, set to 1 by default
        ];
        DB::table('roles')->insert($row);
    }
}
