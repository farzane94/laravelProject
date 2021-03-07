<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Skill;
use App\Models\User;
use Database\Factories\SkillFactory;
use Database\Factories\UserFactory;
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
        $level = ['basic','intermediate','advanced'];
        \Database\Factories\CountryFactory::new()->has(UserFactory::new()->hasAttached(SkillFactory::new()
            ->count(1),
            ['level'=> $level[array_rand($level)]]
        )->count(5))->count(5)->create();

//        User::factory()
//            ->count(5)
//            ->for(Country::factory())
//            ->hasAttached(SkillFactory::new()
//                ->count(1),
//                ['level'=> $level[array_rand($level)]]
//            )->create();


    }
}
