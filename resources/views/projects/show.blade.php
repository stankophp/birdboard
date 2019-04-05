@extends('layouts.app')

@section('content')
    <header>

    </header>

    <main>
        <h1>
            {{ $project->title }}
        </h1>
        <div>{{ $project->description }}</div>
    </main>

@endsection