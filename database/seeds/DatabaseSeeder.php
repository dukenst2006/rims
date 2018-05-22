<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $this->call(PlanTableSeeder::class);
         $this->call(RoleTableSeeder::class);
         $this->call(ProjectTableSeeder::class);
         $this->call(CategoryTableSeeder::class);
         $this->call(EducationTableSeeder::class);
         $this->call(LevelTableSeeder::class);
         $this->call(SkillTableSeeder::class);
         $this->call(LanguageTableSeeder::class);
    }
}
