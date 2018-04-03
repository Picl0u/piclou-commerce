<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Entities\User;
use Ramsey\Uuid\Uuid;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
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
            'guard_name' => config('ikCommerce.superAdminRole'),
        ]);
        $this->command->info('Role superAdmin added !');

        $admins = config('ikCommerce.superAdminUsers');
        foreach($admins as $admin) {
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
