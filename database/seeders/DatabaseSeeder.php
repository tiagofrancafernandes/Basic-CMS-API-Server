<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $devEnvs = config('app-plus.seeders.envs.dev');
        $prodEnvs = config('app-plus.seeders.envs.prod');

        $seeders = [
            CategorySeeder::class => [
                'run_in_envs' => $devEnvs,
            ],
            PostSeeder::class => [
                'run_in_envs' => $devEnvs,
            ],
        ];

        foreach ($seeders as $seederClass => $info) {
            if (
                \in_array(
                    $seederClass,
                    config('app-plus.seeders.disabled_seeders', []),
                    true
                ) ||
                (
                    ($info['run_in_envs'] ?? []) &&
                    !app()->environment($info['run_in_envs'] ?? [])
                )
            ) {
                return;
            }

            \dump(
                \sprintf('%s %s', ...[
                    'Seeding...',
                    $seederClass,
                ])
            );

            $this->call($seederClass);
        }
    }
}
