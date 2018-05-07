<?php

use Illuminate\Database\Seeder;

class QuestionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $faker = Faker\Factory::create();
        for($i = 0 ; $i < 1000 ; $i++){
            App\Question::create([
                'title' => '谁是'.$faker->name.'?',
                'answer' => $faker->name.'是'.$faker->emoji
            ]);
        }
    }
}
