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
            @include('projects.card')
            </div>
        @empty
            <li>No projects.</li>
        @endforelse
    </div>

@endsection