@extends('layouts.app')

@section('content')


@include('flash::message')

<form class="lg:w-1/2 lg:mx-auto bg-white p-6 md:py-12 md:px-16 rounded shadow"
      role="form" method="POST" action="/projects">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <h1 class="text-2xl font-normal mb-10 text-center">Create New Project</h1>

    @include('projects._form', ['project' => new App\Project, 'buttonText' => 'Create Project'])

</form>

@endsection
