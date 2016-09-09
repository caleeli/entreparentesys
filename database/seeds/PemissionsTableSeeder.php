<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $row = [
            'name' => 'Create users',
            'slug' => 'create.users',
            'description' => '', // optional
        ];
        DB::table('permissions')->insert($row);
    }
}
