@extends('layouts.app')

@section('content')
    <div class="flex items-center">
        <a href="/projects/create">New Project</a>
    </div>
    <div class="flex">

    {{--<ul>--}}
        @forelse($projects as $project)
            {{--<li>--}}
                {{--<a href="{{ $project->path() }}">{{ $project->title }}</a>--}}
            {{--</li>--}}
            <div class="bg-white mr-4 rounded shadow">
                <h3>{{ $project->title }}</h3>
                <div>{{ $project->description }}</div>
            </div>
        @empty
            <li>No projects.</li>
        @endforelse
    {{--</ul>--}}
    </div>

@endsection