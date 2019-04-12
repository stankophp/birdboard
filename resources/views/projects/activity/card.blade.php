<div class="card mt-3" style="height: 200px">
    <h3 class="font-normal text-xl py-4 mb-3 border-l-4 border-blue-light pl-4 -ml-5">
        Activity
    </h3>
    <div class="text-sm list-reset">
        @foreach($project->activity as $activity)
            <li>
                @include('projects.activity.'.$activity->description)
                <span class="text-grey">{{ $activity->created_at->diffForHumans() }}</span>
            </li>
        @endforeach
    </div>
</div>
