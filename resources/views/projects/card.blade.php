
<div class="card flex flex-col" style="height: 200px">
    <h3 class="font-normal text-xl py-4 mb-3 border-l-4 border-blue-light pl-4 -ml-5">
        <a class="text-black no-underline" href="{{ route('projects.show', ['project' => $project->id]) }}">
            {{ $project->title }}
        </a>
    </h3>
    <div class="text-grey mb-4 flex-1">{{ Str::limit($project->description, 150) }}</div>
    <footer>
        <form method="POST" class="text-right" action="{{ route('projects.destroy', ['project' => $project->id]) }}">
            @method('DELETE')
            {{ csrf_field() }}
            <button type="submit" class="button text-xs">Delete</button>
        </form>
    </footer>
</div>
