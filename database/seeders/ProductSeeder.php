<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $now = now();
        $chunks = [];

        for ($i = 0; $i < 10000; $i++) {
            $chunks[] = [
                'name' => $faker->words(rand(2, 4), true),
                'price' => $faker->randomFloat(2, 10000, 50000000),
                'description' => $faker->sentence(),
                'created_at' => $now->copy()->subDays(rand(0, 365)),
                'updated_at' => $now,
            ];

            if (count($chunks) === 500) {
                DB::table('products')->insert($chunks);
                $chunks = [];
            }
        }

        if ($chunks) {
            DB::table('products')->insert($chunks);
        }
    }
}
