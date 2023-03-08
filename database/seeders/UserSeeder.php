<?php

    namespace Database\Seeders;

    use App\Models\User;
    use Illuminate\Database\Console\Seeds\WithoutModelEvents;
    use Illuminate\Database\Seeder;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Hash;
    use Spatie\Permission\Models\Permission;
    use Spatie\Permission\Models\Role;

    class UserSeeder extends Seeder
    {
        /**
         * Run the database seeds.
         */
        public function run(): void
        {
            //creazione ruoli e permessi
            $dev = Role::firstOrCreate(['name' => 'developer']);
            $pj = Role::firstOrCreate(['name' => 'project manager']);

            $pj_perm1 = Permission::firstOrCreate(['name' => 'add-client']);
            $pj_perm2 = Permission::firstOrCreate(['name' => 'add-project']);
            $pj_perm3 = Permission::firstOrCreate(['name' => 'add-task']);
            $pj_perm4 = Permission::firstOrCreate(['name' => 'add-dev']);

            $dev_perm = Permission::firstOrCreate(['name' => 'edit-status']);

            $dev->givePermissionTo('edit-status');
            $pj->givePermissionTo(['add-client', 'add-project', 'add-task', 'add-dev']);


            //creazione utenti

            $developers = [
                [
                    'name' => 'Lorenzo Bernini',
                    'email' => 'info@berninize.com',
                    'password' => Hash::make('password'),
                ],
                [
                    'name' => 'Albert Einstein',
                    'email' => 'albert@berninize.com',
                    'password' => Hash::make('password'),
                ],
                [
                    'name' => 'Isaac Newton',
                    'email' => 'isaac@berninize.com',
                    'password' => Hash::make('password'),
                ],
                [
                    'name' => 'Marie Curie',
                    'email' => 'marie@berninize.com',
                    'password' => Hash::make('password'),
                ],
            ];
            $project_managers = [
                [
                    'name' => 'Sandro Pertini',
                    'email' => 'sandro@berninize.com',
                    'password' => Hash::make('password'),
                ],
                [
                    'name' => 'Mahatma Gandhi',
                    'email' => 'mahatma@berninize.com',
                    'password' => Hash::make('password'),
                ],
            ];

            foreach($developers as $deve){
               $user = User::create($deve);
               $user->assignRole('developer');
        }

            foreach($project_managers as $pj){
                $user = User::create($pj);
                $user->assignRole('project manager');
            }
        }
    }
