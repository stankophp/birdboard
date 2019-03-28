@extends('layouts.app')

@section('content')

<h3>Project
    <small>» Create New Project</small>
</h3>

<form class="form-horizontal" role="form" method="POST" action="/projects">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <div class="form-group">
        <label for="title" class="col-md-3 control-label">
            Title
        </label>
        <div class="col-md-8">
            <input type="text" class="form-control" name="title" id="title" value="{{ old('title') }}">
        </div>
    </div>

    <div class="form-group">
        <label for="description" class="col-md-3 control-label">
            Description
        </label>
        <div class="col-md-8">
            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-7 col-md-offset-3">
            <button type="submit" class="btn btn-primary btn-md">
                <i class="fa fa-plus-circle"></i>
                Add New Project
            </button>
        </div>
    </div>

</form>

@endsection
