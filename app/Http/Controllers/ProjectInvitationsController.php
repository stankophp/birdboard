<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectInvitationsRequest;
use App\Project;
use App\Task;
use App\User;
use Illuminate\Http\Request;

class ProjectInvitationsController extends Controller
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
     * @param ProjectInvitationsRequest $request
     * @param Project $project
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectInvitationsRequest $request, Project $project)
    {
        $attributes = $request->validated();
        $user = User::whereEmail($attributes)->firstOrFail();
        $project->invite($user);

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
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Project $project, Task $task)
    {
        $this->authorize('update', $task->project);
        $attributes = $request->validate(['body' => 'required']);

        $task->update($attributes);

        ($request->get('completed')) ? $task->complete() : $task->incomplete();


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
