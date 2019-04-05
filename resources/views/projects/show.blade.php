@extends('layouts.app')

@section('content')

    <h1>
        {{ $project->title }}
    </h1>
    <div>{{ $project->description }}</div>
    <a href="{{ route('projects.index') }}">Back</a>

@endsection