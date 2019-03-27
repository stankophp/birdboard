<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->
    <style>

    </style>
</head>
<body>
<div class="flex-center position-ref full-height">

    <div class="content">
        <div class="title m-b-md">
            Laravel
        </div>
        <ul>
            @forelse($projects as $project)
                <li>
                    <a href="{{ $project->path() }}">{{ $project->title }}</a>
                </li>
            @empty
                <li>No projects.</li>
            @endforelse
        </ul>
    </div>
</div>
</body>
</html>
