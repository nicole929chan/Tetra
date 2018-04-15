<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Generator as Faker;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {

      DB::table('comments')->insert([
            'creator_id' => 1,
            'version_id' => 1,
            'body' => $faker->realText(rand(20,50))
      ]);
    }
}
