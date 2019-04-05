@extends('layouts.app')

@section('content')

<h3>Project
    <small>» Create New Project</small>
</h3>

<form class="form-horizontal" role="form" method="POST" action="/projects">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <div class="field">
        <label for="title" class="label">
            Title
        </label>
        <div class="control">
            <input type="text" class="form-control" name="title" id="title" value="{{ old('title') }}">
        </div>
    </div>

    <div class="field">
        <label for="description" class="label">
            Description
        </label>
        <div class="control">
            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
        </div>
    </div>

    <div class="field">
        <div class="control">
            <button type="submit" class="button is-link btn btn-primary btn-md">
                Add New Project
            </button>
            <a href="{{ route('projects.index') }}">Cancel</a>
        </div>
    </div>

</form>

@endsection
