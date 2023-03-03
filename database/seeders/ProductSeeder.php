<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');

        for($i=1; $i<=50; $i++){
            \DB::table("products")->insert([
                'product_name' => $faker->sentence($nbWords = 4, $variableNbWords=true),
                'product_code' => $faker->word,
                'price' => $faker->randomFloat($nbMaxDecimals= NULL, $min=45000, $max=10000000),
                'created_at' => $faker->dateTimeBetween($startDate = '-2 years', $endDate = 'now'),
                'updated_at' => $faker->dateTimeBetween($startDate = '-2 years', $endDate = 'now'),
            ]);
        }
    }
}
