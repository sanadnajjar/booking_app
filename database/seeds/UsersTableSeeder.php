<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        //$faker = Faker\Factory::create('pl_PL');

        for ($i=1;$i<=10;$i++)
        {
            DB::table('users')->insert([

                'name' => $faker->firstName,
                'surname' => $faker->lastName,
                'email' => $faker->email,
                'password' => bcrypt('password')
            ]);
        }
    }
}
