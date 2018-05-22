<?php

use Illuminate\Database\Seeder;

class ProjectTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $companies = \Rims\Domain\Company\Models\Company::limit(2)->get();

        $companies->each(function ($u) {
            $u->projects()->saveMany(factory(\Rims\Domain\Project\Models\Project::class, 5)->make());
        });

    }
}
