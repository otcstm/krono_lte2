<?php

use Illuminate\Database\Seeder;
use App\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $roles = [
        [
            'id'         => 1,
            'title'      => 'Super Admin',
            'created_at' => '2019-08-27 09:37:29',
            'updated_at' => '2019-08-27 09:37:29',
            'deleted_at' => null,
        ],
        [
            'id'         => 2,
            'title'      => 'User',
            'created_at' => '2019-08-27 09:37:29',
            'updated_at' => '2019-08-27 09:37:29',
            'deleted_at' => null,
        ]
      ];

      Role::insert($roles);
    }
}
