<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         User::insert(
	[
            'name'=>'Abdurazzoq',
            'surname'=>'Negmatov',
            'login'=>'AlovesZ',
            'password'=>bcrypt('9050'),
            'phone'=>'+992928369050', 
            'role'=>'Разраб',
        ],);
        
    }
}
