<?php

use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        for ($i = 1; $i <= 50; $i++)
        {
            DB::table('Comments')->insert([
                'content' => $faker->text(500),
                'commentable_type' => $faker->randomElement($array = array('App\TouristObject', 'App\Article')),
                'commentable_id' => $faker->numberBetween(1, 10),
                'rating' => $faker->numberBetween(1, 5),
                'user_id' => $faker->numberBetween(1, 10),
            ]);
        }
    }
}
