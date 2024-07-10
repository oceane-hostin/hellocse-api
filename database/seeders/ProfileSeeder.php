<?php

namespace Database\Seeders;

use App\Enums\StatusEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('profiles')->insert([
            'lastname' => 'Doe',
            'firstname' => 'John',
            'image' => 'https://picsum.photos/200/300',
            'status' => StatusEnum::ACTIVE,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('profiles')->insert([
            'lastname' => 'Doe',
            'firstname' => 'Jane',
            'image' => 'https://picsum.photos/200/300',
            'status' => StatusEnum::INACTIVE,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('profiles')->insert([
            'lastname' => 'Green',
            'firstname' => 'Mary',
            'image' => 'https://picsum.photos/200/300',
            'status' => StatusEnum::AWAITING,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
