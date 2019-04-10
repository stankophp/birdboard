<?php

use App\Project;
use Illuminate\Database\Seeder;

class ProjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Project::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $projects = factory(Project::class, 10)->create(['owner_id' => 1]);
    }
}
