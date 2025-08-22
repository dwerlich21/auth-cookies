<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar se existe pelo menos um tenant
        $tenant = Tenant::first();
        if (! $tenant) {
            // Criar um tenant padrão se não existir
            $tenant = Tenant::create([
                'name' => 'Prefeitura de Angelina',
                'slug' => 'angelina',
                'active' => true,
            ]);
        }

        // Criar alguns usuários específicos para teste
        $specificUsers = [
            [
                'name' => 'Diego',
                'email' => 'dwerlich21@gmail.com',
                'password' => Hash::make('admin123'),
                'tenant_id' => $tenant->id,
                'active' => true,
            ],
            [
                'name' => 'Junior',
                'email' => 'junior@gmail.com',
                'password' => Hash::make('admin123'),
                'tenant_id' => $tenant->id,
                'active' => true,
            ],
            [
                'name' => 'Marivaldo',
                'email' => 'marivaldo@gmail.com',
                'password' => Hash::make('admin123'),
                'tenant_id' => $tenant->id,
                'active' => true,
            ],
        ];

        foreach ($specificUsers as $userData) {
            User::updateOrCreate(
                [
                    'email' => $userData['email'],
                ],
                [
                    'name' => $userData['name'],
                    'password' => $userData['password'],
                    'tenant_id' => $userData['tenant_id'],
                    'active' => $userData['active'],

                ]);
        }

        // Criar 195 usuários aleatórios (200 total - 1 admin - 4 específicos)
        //        $this->command->info('Criando 195 usuários aleatórios...');
        //
        //        User::factory()
        //            ->count(195)
        //            ->state([
        //                'tenant_id' => $tenant->id,
        //                'password'  => Hash::make('123456'), // Senha padrão para todos
        //            ])
        //            ->create();
        //
        //        $this->command->info('✅ 200 usuários criados com sucesso!');
    }
}
