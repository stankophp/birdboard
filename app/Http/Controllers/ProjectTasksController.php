<?php

namespace App\Http\Controllers;

use App\Project;
use App\Task;
use App\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class ProjectTasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Project $project
     * @return \Illuminate\Http\Response
     */
    public function store(Project $project)
    {
        /** @var User $user */
        $user = auth()->user();

        if ($user->isNot($project->owner)) {
            abort(SymfonyResponse::HTTP_FORBIDDEN);
        }

        request()->validate(['body' => 'required']);
        $project->addTask(request('body'));

        return redirect($project->path());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Project $project
     * @param Task $task
     * @return void
     */
    public function update(Request $request, Project $project, Task $task)
    {
        /** @var User $user */
        $user = auth()->user();

        if ($user->isNot($project->owner)) {
            abort(SymfonyResponse::HTTP_FORBIDDEN);
        }

        $task->update([
            'body' => $request->get('body'),
            'completed' => $request->has('completed'),
        ]);

        return redirect($project->path());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
