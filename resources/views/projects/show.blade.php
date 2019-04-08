@extends('layouts.app')

@section('content')

    <header class="flex items-center mb-3 py-4">
        <div class="flex justify-between w-full items-end">
            <p class="text-grey text-xl font-normal">
                <a href="{{ route('projects.index') }}" class="text-grey text-xl font-normal no-underline">My Projects</a> / {{ $project->title }}
            </p>
            <a href="{{ route('projects.create') }}" class="button">New Project</a>
        </div>
    </header>

    <main>
        <div class="lg:flex -mx-3">
            <div class="lg:w-3/4 px-3">
                <div class="mb-8">
                    <h2 class="text-grey text-lg font-normal mb-3">Tasks</h2>
                    @foreach($project->tasks as $task)
                    <div class="card mb-3">
                        <form method="post" action="{{ $task->path() }}">
                            @method('PATCH')
                            {{ csrf_field() }}
                            <div class="flex">
                                <input type="text" name="body" id="body" value="{{ $task->body }}" class="w-full">
                                <input type="checkbox" name="completed" id="completed" onchange="this.form.submit()">

                            </div>
                        </form>
                    </div>
                    @endforeach
                </div>

                <div class="card mb-3">
                    <form action="{{ $project->path().'/tasks' }}" method="POST">
                        {{ csrf_field() }}
                        <input type="text" name="body" id="body" class="w-full" placeholder="Add new task...">
                    </form>
                </div>

                <div class="mb-8">
                    <h2 class="text-grey text-lg font-normal mb-3">General Notes</h2>
                    <textarea class="card w-full" style="min-height: 200px;">Lorem </textarea>
                </div>
            </div>
            <div class="lg:w-1/4 px-3">
                @include('projects.card')
            </div>
        </div>
    </main>


@endsection