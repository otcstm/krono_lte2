<?php

use Illuminate\Database\Seeder;
use App\Permission;

class AmerPermissionSeeder extends Seeder
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
            'title'      => 'admin-nav-menu',
            'descr'      => 'Admin navigation menu',
            'created_at' => '2019-08-27 09:37:29',
            'updated_at' => '2019-08-27 09:37:29',
            'deleted_at' => null,
        ],
        [
            'id'         => 2,
            'title'      => 'user-nav-menu',
            'descr'      => 'User navigation menu',
            'created_at' => '2019-08-27 09:37:29',
            'updated_at' => '2019-08-27 09:37:29',
            'deleted_at' => null,
        ],
        [
            'id'         => 3,
            'title'      => 'ot-nav-menu',
            'descr'      => 'Overtime navigation menu',
            'created_at' => '2019-08-27 09:37:29',
            'updated_at' => '2019-08-27 09:37:29',
            'deleted_at' => null,
        ],
        [
            'id'         => 4,
            'title'      => 'rpt-nav-menu',
            'descr'      => 'Reports navigation menu',
            'created_at' => '2019-08-27 09:37:29',
            'updated_at' => '2019-08-27 09:37:29',
            'deleted_at' => null,
        ]
      ];

      Permission::insert($roles);

    }
}
