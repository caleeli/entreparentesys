<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $row = [
            'name'     => 'Admin',
            'email'    => 'admin@entreparentesys.com',
            'password' => '$2y$10$9PEsmHuLg8Wn.YjFEWrHFOhtTu2P1MehHm8JlHv9MRenVUst2.Yxa',
        ];
        DB::table('users')->insert($row);
    }
}
