<?php

namespace Modules\Admin\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Entities\User;
use Ramsey\Uuid\Uuid;
use Spatie\Permission\Models\Role;

class SeedAdminUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $role = Role::create([
            'uuid' => Uuid::uuid4()->toString(),
            'name' => config('ikCommerce.superAdminRole'),
            'guard_name' =>'super_admin',
        ]);
        $this->command->info('Role superAdmin added !');

        $this->createAdmin($role);

    }

    private function createAdmin($role)
    {
        $admins = config('ikCommerce.superAdminUsers');
        foreach($admins as $admin) {
            if(is_array($admin)) {
                foreach($admin as $a) {

                    $insert = [
                        'uuid' => Uuid::uuid4()->toString(),
                        'online' => 1,
                        'firstname' => $a['firstname'],
                        'lastname' => $a['lastname'],
                        'username' => str_slug($a['username']),
                        'email' => $a['email'],
                        'password' => bcrypt($a['password']),
                        'role' => 'admin',
                        'gender' => "M",
                        'newsletter' => 0,
                        'role_id' => $role->id,
                        'guard_name' => $role->name
                    ];

                    $user = User::create($insert);
                    $user->assignRole($role->name);
                    $this->command->info($admin['firstname'].' '.$admin['lastname'].' seeded!');
                }
            }else{
                $insert = [
                    'uuid' => Uuid::uuid4()->toString(),
                    'online' => 1,
                    'firstname' => $admin['firstname'],
                    'lastname' => $admin['lastname'],
                    'username' => str_slug($admin['username']),
                    'email' => $admin['email'],
                    'password' => bcrypt($admin['password']),
                    'role' => 'admin',
                    'gender' => "M",
                    'newsletter' => 0,
                    'role_id' => $role->id,
                    'guard_name' => $role->name
                ];

                $user = User::create($insert);
                $user->assignRole($role->name);
                $this->command->info($admin['firstname'].' '.$admin['lastname'].' seeded!');
            }
        }
    }
}
