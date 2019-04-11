<?php

use App\Project;
use App\Task;
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

        foreach ($projects as $project) {
            factory(Task::class, 3)->create(['project_id' => $project->id]);
        }
    }
}
