@extends('layouts.app')

@section('content')
    <header class="flex items-center mb-3 py-4">
        <div class="flex justify-between w-full items-end">
            <h1 class="text-grey text-xl font-normal">My Projects</h1>
            <a href="{{ route('projects.create') }}" class="button">New Project</a>
        </div>
    </header>

    <div class="flex flex-wrap -mx-3">

        @forelse($projects as $project)
            <div class="w-1/3 px-3 pb-6">
                <div class="bg-white mx-3 p-5 rounded-lg shadow" style="height: 200px">
                    <h3 class="font-normal text-xl py-4 mb-3 border-l-4 border-blue-light pl-4 -ml-5">
                        <a class="text-black no-underline" href="{{ route('projects.show', ['project' => $project->id]) }}">
                            {{ $project->title }}
                        </a>
                    </h3>
                    <div class="text-grey">{{ Str::limit($project->description, 200) }}</div>
                </div>
            </div>
        @empty
            <li>No projects.</li>
        @endforelse
    </div>

@endsection