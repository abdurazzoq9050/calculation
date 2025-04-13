<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name'=>'Abdurazzoq',
        //     'surname'=>'Negmatov',
        //     'login'=>'AlovesZ',
        //     'password'=>'9050',
        //     'phone'=>'+992928369050',
        //     'role'=>'Разраб',
        // ]);

        $this->call([
            UserSeeder::class,
        ]);
    }
}
